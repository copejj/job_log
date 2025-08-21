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

use Jeff\Code\Model\Address\Address;
use Jeff\Code\Model\Company\Address\CompanyAddress;
use Jeff\Code\Model\Entities\AddressTypes;
use Jeff\Code\Model\Entities\States;

class Company extends HeaderedContent
{
	protected Service $company;

	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	protected AddressTypes $address_types;
	protected States $states;

	public function init(): void
	{
		$this->address_types = new AddressTypes();
		$this->states = new States();

		if (!empty($this->post['company_id']))
		{
			$this->company = Service::load($this->post['company_id']);
			$this->mode = 'edit';
			$this->title= 'Edit';
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
				$message = "Exception: " . $e->getMessage();
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
		$data = $this->company->data ?? $this->post;
		$data = $this->formatData($data);
		?>
		<script>
			function save_form()
			{
				$('#company_form').append("<?=new Input('action', 'hidden', $this->mode)?>");
				return true;
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
				new Input("addresses[{$id}][street]", 'text', '', $data['addresses'][$id]['street'] ?? '', 'Street'),
				new Input("addresses[{$id}][street_ext]", 'text', '', $data['addresses'][$id]['street_ext'] ?? '', 'Street Ext'),
				new Input("addresses[{$id}][city]", 'text', '', $data['addresses'][$id]['city'] ?? '', 'City'),
				new Select("addresses[{$id}][state_id]", $this->states->data, 0, (int) ($data['addresses'][$id]['state_id'] ?? 0), 'State', "[ Selected a state ]"),
				new Input("addresses[{$id}][zip]", 'text', '', $data['addresses'][$id]['zip'] ?? '', 'Zip'),
			], $type, "address-type-" . strtolower($type), 'address-group', new Attributes(['class' => 'inputs-address-group']));
		}
		echo new Form([
			new Inputs([ 
				new Input('company_id', 'hidden', $data['company_id'] ?? ''),
				new Input('name', 'text', $data['name'] ?? '', '', 'Company Name'),
				new Input('email', 'text', $data['email'] ?? '', '', 'Email'),
				new Input('website', 'text', $data['website'] ?? '', '', 'Website'),
				new Inputs($address_inputs, '', 'addresses', 'addresses'),
			], 'date', 'date'),
			new Input('save_company', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_company', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'company_form']));
	}
}
