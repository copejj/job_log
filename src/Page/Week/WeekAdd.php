<?php
namespace Jeff\Code\Page\Week;

use Exception;
use Jeff\Code\Template\Elements\Date;
use Jeff\Code\Helper\Week\Week;

class WeekAdd extends Weeks
{
	public function processing(): void
	{
		if (empty($post['save_week']))
		{
			try
			{
				$result = Week::create($this->post);
				if (!empty($result))
				{
					$message = "This week created successfully";
				}
			}
			catch (Exception $e)
			{
				$message = "This week already exists, use that or create a different range";
			}

			$this->message = $message;
		}
	}

	public function content(): void
	{
		$inputs = [];
		$inputs[] = new Date('start_date', $this->post['start_date'] ?? '', date('Y-m-d', strtotime('last sunday')), 'Start Date');
		$inputs[] = new Date('end_date', $this->post['end_date'] ?? '', date('Y-m-d', strtotime('next saturday')), 'End Date');
		?>
		<form method='post'>
			<?=implode($inputs)?>
			<input type='submit' name='save_week' value='Submit' />
		</form>
		<?php
	}
}
