<?php
namespace Jeff\Code\Controller\Company;

use Exception;
use Jeff\Code\Model\Company\Company;

class CompanyEdit extends CompanyAdd
{
	public function processing(): void
	{
		$this->mode = 'edit';
		$this->title = 'Edit';

		$this->company = Company::load($this->post['company_id']);
		if (!empty($this->post['save_company']))
		{
			$message = '';
			try
			{
				$this->company->name = $this->post['name'];
				$this->company->email = $this->post['email'];
				$this->company->website = $this->post['website'];
				$has_saved = $this->company->save();
				if ($has_saved)
				{
					$message = "This company updated successfully";
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
}
