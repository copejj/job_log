<?php
namespace Jeff\Code\Controller\Company;

use Exception;

use Jeff\Code\Model\Company\Company as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\Elements\Select;
use Jeff\Code\View\HeaderedContent;

use Jeff\Code\Model\Company\Address\CompanyAddress;
use Jeff\Code\Model\Entities\AddressTypes;
use Jeff\Code\Model\Entities\States;
use Jeff\Code\Model\Meta\Labels;

class Company extends HeaderedContent
{
	protected Service $company;

	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	protected AddressTypes $address_types;
	protected States $states;
	protected Labels $labels;

	public function init(): void
	{
		$this->address_types = new AddressTypes();
		$this->states = new States();
		$this->labels = new Labels();

		if (!empty($this->post['company_id']))
		{
			$this->company = Service::load($this->post['company_id']);
			$this->mode = 'edit';
			$this->title = 'Edit';
		}
	}

	public function processing(): void
	{
		if (!empty($this->post['save_company']))
		{
			$message = '';
			try
			{
				if (empty($this->company))
				{
					// check if company already exists
					$company = Service::getByName($this->post['name'] ?? '');
					if (!empty($company))
					{
						throw new Exception("Company '{$this->post['name']}' already exists");
					}

					// create a new company
					$company = Service::create($this->post);
					if (!empty($company))
					{
						$message = "This company created successfully";
						$this->company = $company;
						$this->acted = true;
						$this->mode = 'edit';
						$this->title = 'Edit';
					}
				}
				else
				{
					$this->company->name = $this->post['name'];
					$this->company->email = $this->post['email'];
					$this->company->website = $this->post['website'];
					$this->company->phone = $this->post['phone'];
					$this->company->fax = $this->post['fax'];
					if ($this->company->save())
					{
						$message = "This company updated successfully";
						$this->acted = true;
					}
				}

				if (!empty($this->company))
				{
					if (!empty($this->post['addresses']))
					{
						foreach ($this->post['addresses'] as $address)
						{
							$address['company_id'] = empty($address['company_id']) ? $this->company->company_id : $address['company_id'];
							$company_address = CompanyAddress::getInstance($address);
							if ($company_address->save(true))
							{
								$this->acted = true;
							}
						}
					}
				}

				if ($this->acted)
				{
					$this->company = Service::load($this->company->company_id);
				}
			}
			catch (Exception $e)
			{
				\Jeff\Code\Util\D::p('exception', $e);
				$message = $e->getMessage();
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "{$this->title} Company";
	}

	public function formatData(array $data)
	{
		if (!empty($data["addresses_json"]))
		{
			$targets = json_decode($data["addresses_json"], true);
			foreach ($targets as $target)
			{
				$data['addresses'][$target['address_type_id']] = $target;
			}

			unset($data['addresses_json']);
		}
		return $data;
	}

	public function content(): void
	{
		$data = $this->formatData($this->company->data ?? $this->post);

		?>
		<script>
			function save_form()
			{
				$('#company_form').append("<?=new Input('action', 'hidden', $this->mode)?>");
				return true;
			}
			function parse_address(element)
			{
				let full = element.value;
				let street = '';
				let street_ext = '';
				let city = '';
				let state = '';
				let zip = '';

				// Simple US address parsing
				let parts = full.split(',');
				switch (parts.length)
				{
					case (3):
						street = parts[0].trim();
						city = parts[1].trim();
						if (parts[2].trim().match(/([A-Za-z]+),? ([0-9\-]+)/))
						{
							state = RegExp.$1;
							zip = RegExp.$2;
						}
						break;
					case (4):
						street = parts[0].trim();
						if (parts[3].trim().match(/([A-Za-z]+),? ([0-9\-]+)/))
						{
							street_ext = parts[1].trim();
							city = parts[2].trim();
							state = RegExp.$1;
							zip = RegExp.$2;
						}
						else
						{
							city = parts[1].trim();
							state = parts[2].trim();
							zip = parts[3].trim();
						}
						break;
					case (5):
						street = parts[0].trim();
						street_ext = parts[1].trim();
						city = parts[2].trim();
						state = parts[3].trim();
						zip = parts[4].trim();
						break;
					default:
						street = full;
						return;
				}

				element.value = street;
				let parent = $(element).closest('.inputs-address-group');
				parent.find('input[name$="[street_ext]"]').val(street_ext);
				parent.find('input[name$="[city]"]').val(city);
				parent.find('select[name$="[state_id]"] option').each(function(){
					if ($(this).text().trim().includes(state))
					{
						$(this).prop('selected', true);
					}
				});
				parent.find('input[name$="[zip]"]').val(zip);
			}
		</script>
		<?php
		$address_inputs = [];
		foreach ($this->address_types->data as $id => $type)
		{
			$address_inputs[] = new Inputs([
				new Input("addresses[{$id}][company_address_id]", 'hidden', $data['addresses'][$id]['company_address_id'] ?? 0),
				new Input("addresses[{$id}][company_id]", 'hidden', $data['addresses'][$id]['company_id'] ?? $data['company_id'] ?? 0),
				new Input("addresses[{$id}][address_type_id]", 'hidden', $data['addresses'][$id]['address_type_id'] ?? $id),
				new Input("addresses[{$id}][address_id]", 'hidden', $data['addresses'][$id]['address_id'] ?? ''),
				new Input("addresses[{$id}][street]", 'text', '', $data['addresses'][$id]['street'] ?? '', $this->labels->street, new Attributes(['onchange' => 'parse_address(this)'])),
				new Input("addresses[{$id}][street_ext]", 'text', '', $data['addresses'][$id]['street_ext'] ?? '', $this->labels->street_ext),
				new Input("addresses[{$id}][city]", 'text', '', $data['addresses'][$id]['city'] ?? '', $this->labels->city),
				new Select("addresses[{$id}][state_id]", $this->states->data, 0, (int) ($data['addresses'][$id]['state_id'] ?? 0), $this->labels->state, "[ Selected a state ]"),
				new Input("addresses[{$id}][zip]", 'text', '', $data['addresses'][$id]['zip'] ?? '', $this->labels->zip),
			], $type, "address-type-" . strtolower($type), 'address-group', new Attributes(['class' => 'inputs-address-group']));
		}
		echo new Form([
			new Inputs([ 
				new Input('company_id', 'hidden', $data['company_id'] ?? ''),
				new Input('name', 'text', $data['name'] ?? '', '', $this->labels->name),
				new Input('email', 'text', $data['email'] ?? '', '', $this->labels->email),
				new Input('website', 'text', $data['website'] ?? '', '', $this->labels->website),
				new Input('phone', 'text', $data['phone'] ?? '', '', $this->labels->phone),
				new Input('fax', 'text', $data['fax'] ?? '', '', $this->labels->fax),
				new Inputs($address_inputs, '', 'addresses', 'addresses'),
			], 'date', 'date'),
			new Input('save_company', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_company', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'company_form']));
	}
}
