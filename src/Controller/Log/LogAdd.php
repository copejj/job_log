<?php
namespace Jeff\Code\Controller\Log;

use Exception;
use Jeff\Code\Model\Log\Log;

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

class LogAdd extends Logs
{
	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';
	protected Log $log;

	protected Actions $actions;
	protected Methods $methods;
	protected Weeks $weeks;

	public function processing(): void
	{
		$this->actions = new Actions();
		$this->methods = new Methods();
		$this->weeks = new Weeks();
		if (!empty($this->post['save_job']))
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
					$message = "Log entry saved";
					$this->acted = true;
					$this->log = $log;
				}
			}
			catch (Exception $e)
			{
				$message = $e->getMessage();
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "{$this->title} Log";
	}

	public function content(): void
	{
		$data = $this->log->data ?? $this->post;
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
				new Date('action_date', $data['action_date'] ?? '', date('Y-m-d'), 'Action Date'),
				new Select('week_id', $this->weeks->data, $this->weeks->default ?? 0, $data['week_id'] ?? 0, '[ Select a work week ]'),
				new Input('title', 'text', $data['title'] ?? '', '', 'Title'),
				new Inputs(new Checkboxes('actions', $this->actions->data, $data['actions'] ?? []), 'Actions', 'actions'),
				new Inputs(new Checkboxes('methods', $this->methods->data, $data['methods'] ?? []), 'Methods', 'methods'),
				new Input('job_number', 'text', $data['job_number'] ?? '', '', 'Job Number'),
				new Input('next_step', 'text', $data['next_step'] ?? '', '', 'Next Step'),
			], 'logs', 'logs'),
			new Input('save_job', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_job', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'log_form']));
	}
}