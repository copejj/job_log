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

use Jeff\Code\Model\Entities\Actions;
use Jeff\Code\Model\Entities\Methods;

class LogAdd extends Logs
{
	protected bool $acted = false;
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		$this->actions = new Actions();
		$this->methods = new Methods();
		if (!empty($this->post['save_job']))
		{
			\Jeff\Code\Util\D::p('post', $this->post);
			$message = '';
			try
			{
				$result = Log::create($this->post);
				if ($result)
				{
					$message = "Log entry saved";
				}
				else
				{
					$message = "Log entry save failed";
				}
				$this->acted = true;
			}
			catch (Exception $e)
			{
				$message = "This log already exists, use that or create a different range";
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "Add Log";
	}

	public function content(): void
	{
		?>
		<script>
			function save_form()
			{
				$('#add_form').append("<?=new Input('action', 'hidden', 'add')?>");
				return true;
			}
		</script>
		<?php
		echo new Form([
			new Inputs([ 
				new Date('action_date', $this->post['action_date'] ?? '', date('Y-m-d'), 'Action Date'),
				new Input('title', 'text', $this->post['title'] ?? '', '', 'Title'),
				new Inputs(new Checkboxes('actions', $this->actions->data, $this->post['actions'] ?? []), 'Actions', 'actions'),
				new Inputs(new Checkboxes('methods', $this->methods->data, $this->post['methods'] ?? []), 'Methods', 'methods'),
				new Input('job_number', 'text', $this->post['job_number'] ?? '', '', 'Job Number'),
				new Input('next_step', 'text', $this->post['next_step'] ?? '', '', 'Next Step'),
			], 'logs', 'logs'),
			new Input('save_job', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_job', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'add_form']));
	}
}