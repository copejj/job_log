<?php
namespace Jeff\Code\Controller\Company;

use Exception;

use Jeff\Code\Model\Company\Company as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;

class Company extends HeaderedContent
{
	protected Service $company;

	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	public function init(): void
	{
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

	public function content(): void
	{
		$data = $this->company->data ?? $this->post;
		?>
		<script>
			function save_form()
			{
				$('#company_form').append("<?=new Input('action', 'hidden', $this->mode)?>");
				return true;
			}
		</script>
		<?php
		echo new Form([
			new Inputs([ 
				new Input('company_id', 'hidden', $data['company_id'] ?? ''),
				new Input('name', 'text', $data['name'] ?? '', '', 'Company Name'),
				new Input('email', 'text', $data['email'] ?? '', '', 'Email'),
				new Input('website', 'text', $data['website'] ?? '', '', 'Website'),
			], 'date', 'date'),
			new Input('save_company', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_company', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'company_form']));
	}
}
