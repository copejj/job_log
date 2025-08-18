<?php
namespace Jeff\Code\Controller\Company;

use Exception;
use Jeff\Code\Model\Company\Company;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;

class CompanyAdd extends Companies
{
	protected Company $company;

	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	public function processing(): void
	{
		if (empty($post['save_company']))
		{
			$message = '';
			try
			{
				$company = Company::create($this->post);
				if (!empty($company))
				{
					$message = "This company created successfully";
					$this->company = $company;
					$this->acted = true;
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
