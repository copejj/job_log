<?php
namespace Jeff\Code\Page\Week;

use Exception;
use Jeff\Code\Helper\Week\Week;
use Jeff\Code\Template\Elements\Date;
use Jeff\Code\Template\Elements\Input;

class WeekEdit extends Weeks
{
	protected Week $current;

	public function processing(): void
	{
		$this->current = Week::load($this->post['week_id']);
		\Jeff\Code\D::p('here', $this->current);
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
		return "Edit " . parent::getTitle();
	}

	public function content(): void
	{
		$inputs = [];
		$inputs[] =	new Input('week_id', 'hidden', $this->post['week_id']);
		$inputs[] = new Date('start_date', $this->current->start_date ?? '', date('Y-m-d', strtotime('last sunday')), 'Start Date');
		$inputs[] = new Date('end_date', $this->current->end_date ?? '', date('Y-m-d', strtotime('next saturday')), 'End Date');
		?>
		<script>
			function save_form()
			{
				$('#edit_form').append("<input type='hidden' name='action' value='edit' />");
				return true;
			}
		</script>
		<form id='edit_form' method='post'>
			<?=implode($inputs)?>
			<input type='submit' name='save_week' value='Submit' onclick='return save_form()' />
			<input type='submit' name='cancel_week' value='Cancel' />
		</form>
		<?php
	}
}
