<?php
namespace Jeff\Code\Controller\Log;

use Exception;
use Jeff\Code\Model\Log\Log;
use Jeff\Code\Model\Log\Action\LogAction;
use Jeff\Code\Model\Log\Method\LogMethod;

use Jeff\Code\View\Display\Attributes;

use Jeff\Code\View\Elements\Checkboxes;
use Jeff\Code\View\Elements\Date;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\Elements\Select;

use Jeff\Code\Model\Entities\Actions;
use Jeff\Code\Model\Entities\Methods;
use Jeff\Code\Model\Entities\Weeks;
use Jeff\Code\Model\Entities\Companies;

class LogAdd extends Logs
{
	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	protected Log $log;

	protected Actions $actions;
	protected Methods $methods;
	protected Weeks $weeks;
	protected Companies $companies;

	public function init(): void
	{
		$this->actions = new Actions();
		$this->methods = new Methods();
		$this->weeks = new Weeks();
		$this->companies = new Companies();
	}

	public function processing(): void
	{
		if (!empty($this->post['save_job_log']))
		{
			$message = '';
			try
			{
				$log = Log::create($this->post);
				if (empty($log))
				{
					$message = "Log entry save failed";
				}
				else
				{
					$this->log = $log;
					$this->saveActions($this->log->job_log_id, $this->post['actions'] ?? []);
					$this->saveMethods($this->log->job_log_id, $this->post['methods'] ?? []);

					$message = "Log entry saved";
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

	protected function saveActions(int $job_log_id, array $actions): bool
	{
		// clear the actions out of the way
		LogAction::delete([Log::getKey() => $job_log_id]);
		// walk through and create the new actions
		if (!empty($actions))
		{
			foreach ($actions as $action_id => $val)
			{
				LogAction::create([Log::getKey() => $job_log_id, 'action_id' => $action_id]);
			}
		}
		return true;
	}

	protected function saveMethods(int $job_log_id, array $methods): bool
	{
		// clear the actions out of the way
		LogMethod::delete([Log::getKey() => $job_log_id]);
		// walk through and create the new actions
		if (!empty($methods))
		{
			foreach ($methods as $method_id => $val)
			{
				LogMethod::create([Log::getKey() => $job_log_id, 'method_id' => $method_id]);
			}
		}
		return true;
	}

	public function getTitle(): string
	{
		return "{$this->title} Log";
	}

	public function formatData(array $data)
	{
		foreach (['action', 'method'] as $type)
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
		$data = $this->log->data ?? $this->post;
		$data = $this->formatData($data);

		?>
		<script>
			function save_form()
			{
				$('#log_form').append("<?=new Input('action', 'hidden', $this->mode)?>");
				return true;
			}
		</script>
		<?php
		echo new Form([
			new Inputs([ 
				new Input('job_log_id', 'hidden', $data['job_log_id'] ?? ''),
				new Select('week_id', $this->weeks->data, $data['week_id'] ?? 0, $this->weeks->default, 'Week', '[ Select a work week ]'),
				new Select('company_id', $this->companies->data, $data['company_id'] ?? 0, $this->companies->default, 'Company', '[ Select a company ]'),
				new Input('title', 'text', $data['title'] ?? '', '', 'Title'),
				new Date('action_date', $data['action_date'] ?? '', date('Y-m-d'), 'Action Date'),
				new Inputs(new Checkboxes('actions', $this->actions->data, $data['actions'] ?? []), 'Actions', 'actions'),
				new Inputs(new Checkboxes('methods', $this->methods->data, $data['methods'] ?? []), 'Methods', 'methods'),
				new Input('job_number', 'text', $data['job_number'] ?? '', '', 'Job Number'),
				new Input('next_step', 'text', $data['next_step'] ?? '', '', 'Next Step'),
			], 'logs', 'logs'),
			new Input('save_job_log', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_job', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'log_form']));
	}
}