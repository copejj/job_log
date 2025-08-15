<?php
namespace Jeff\Code\Page\Week;

use Exception;
use Jeff\Code\Helper\Week\Week;
use Jeff\Code\Template\Display\Attributes;
use Jeff\Code\Template\Elements\Date;
use Jeff\Code\Template\Elements\Form;
use Jeff\Code\Template\Elements\Input;
use Jeff\Code\Template\Elements\Inputs;

class WeekEdit extends Weeks
{
	protected Week $current;

	public function processing(): void
	{
		$this->current = Week::load($this->post['week_id']);
		if (!empty($this->post['save_week']))
		{
			$message = '';
			try
			{
				$this->current->start_date = $this->post['start_date'];
				$this->current->end_date = $this->post['end_date'];
				$has_saved = $this->current->save();
				if ($has_saved)
				{
					$message = "This week updated successfully";
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
		return "Edit Week";
	}

	public function content(): void
	{
		?>
		<script>
			function save_form()
			{
				$('#edit_form').append("<?=new Input('action', 'hidden', 'edit')?>");
				return true;
			}
		</script>
		<?php
		echo new Form([
			new Inputs([ 
				new Input('week_id', 'hidden', $this->post['week_id']),
				new Date('start_date', $this->current->start_date ?? '', date('Y-m-d', strtotime('last sunday')), 'Start Date'),
				new Date('end_date', $this->current->end_date ?? '', date('Y-m-d', strtotime('next saturday')), 'End Date'),
			], 'date', 'date'),
			new Input('save_week', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_week', 'submit', 'Cancel'),
		], 'post', new Attributes(['id' => 'edit_form']));
	}
}
