<?php
namespace Jeff\Code\Controller\Log;

use Exception;
use Jeff\Code\Model\Log\Log;

class LogEdit extends LogAdd
{
	public function init(): void
	{
		parent::init();

		$this->mode = 'edit';
		$this->title = 'Edit';
	}

	public function processing(): void
	{
		$this->log = Log::load($this->post['job_log_id']);
		if (!empty($this->post['save_job_log']))
		{
			$message = '';
			try
			{
				$this->log->week_id = $this->post['week_id'];
				$this->log->action_date = $this->post['action_date'];
				$this->log->company_id = $this->post['company_id'] ?? null;
				$this->log->contact_id = $this->post['contact_id'] ?? null;
				$this->log->title = $this->post['title'] ?? null;
				$this->log->job_number = $this->post['job_number'] ?? null;
				$this->log->next_step = $this->post['next_step'] ?? null;

				$has_saved = $this->log->save();
				$this->saveActions($this->log->job_log_id, $this->post['actions'] ?? []);
				$this->saveMethods($this->log->job_log_id, $this->post['methods'] ?? []);
				if ($has_saved)
				{
					$this->log = Log::load($this->log->job_log_id);
					$message = "This log updated successfully";
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