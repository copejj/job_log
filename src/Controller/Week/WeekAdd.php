<?php
namespace Jeff\Code\Controller\Week;

use Exception;
use Jeff\Code\Model\Week\Week;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Date;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;

class WeekAdd extends Weeks
{
	protected bool $acted = false;

	public function processing(): void
	{
		if (empty($post['save_week']))
		{
			$message = '';
			try
			{
				$result = Week::create($this->post);
				if (!empty($result))
				{
					$message = "This week created successfully";
					$this->acted = true;
				}
			}
			catch (Exception $e)
			{
				$message = "This week already exists, use that or create a different range";
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "Add Week";
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
				new Date('start_date', $this->post['start_date'] ?? '', date('Y-m-d', strtotime('last sunday')), 'Start Date'),
				new Date('end_date', $this->post['end_date'] ?? '', date('Y-m-d', strtotime('next saturday')), 'End Date'),
			], 'date', 'date'),
			new Input('save_week', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_week', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'add_form']));
	}
}
