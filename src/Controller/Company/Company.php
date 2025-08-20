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
						$this->title= 'Edit';
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
			}
			catch (Exception $e)
			{
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
		foreach (['address_type'] as $type)
		{
			if (!empty($data["{$type}s_json"]))
			{
				$data_arr = [];
				$targets = json_decode($data["{$type}s_json"], true);
				foreach ($targets as $target)
				{
					$data_arr[$target["{$type}_id"]] = $target["job_log_{$type}_id"];
				}
				unset($data["{$type}s_json"]);
				$data["{$type}s"] = $data_arr;
			}
		}
		return $data;
	}

	public function content(): void
	{
		$data = $this->company->data ?? $this->post;
		$data = $this->formatData($data);
		\Jeff\Code\Util\D::p('data', $data);
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
				new Input("address[{$id}][company_id]", 'hidden', $data['company_id']),
				new Input("address[{$id}][address_type_id]", 'hidden', $id),
				new Input("address[{$id}][address_id]", 'hidden', $data['address_id'] ?? ''),
				new Input("address[{$id}][street]", 'text', '', '', 'Street'),
				new Input("address[{$id}][street_ext]", 'text', '', '', 'Street Ext'),
				new Input("address[{$id}][city]", 'text', '', '', 'City'),
				new Select("address[{$id}][state_id]", $this->states->data, 0, 0, 'State', "[ Selected a state ]"),
				new Input("address[{$id}][zip]", 'text', '', '', 'Zip'),
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
