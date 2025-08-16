<?php
namespace Jeff\Code\Controller\Week;

use Exception;
use Jeff\Code\Model\Week\Week;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Date;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;

class WeekEdit extends WeekAdd
{
	public function processing(): void
	{
		$this->mode = 'edit';
		$this->title = 'Edit';

		$this->week = Week::load($this->post['week_id']);
		if (!empty($this->post['save_week']))
		{
			$message = '';
			try
			{
				$this->week->start_date = $this->post['start_date'];
				$this->week->end_date = $this->post['end_date'];
				$has_saved = $this->week->save();
				if ($has_saved)
				{
					$message = "This week updated successfully";
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
}
