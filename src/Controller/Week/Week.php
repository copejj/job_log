<?php
namespace Jeff\Code\Controller\Week;

use Exception;
use Jeff\Code\Model\Week\Week as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Date;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;

class Week extends HeaderedContent
{
	protected Service $week;

	protected bool $acted = false;
	protected string $mode = 'add';
	protected string $title = 'Add';

	public function init(): void
	{
		if (!empty($this->post['week_id']))
		{
			$this->week = Service::load($this->post['week_id']);
			$this->mode = 'edit';
			$this->title= 'Edit';
		}
	}

	public function processing(): void
	{
		\Jeff\Code\Util\D::p(__FUNCTION__);
		if (!empty($this->post['save_week']))
		{
		\Jeff\Code\Util\D::p('save_week');
			$message = '';
			if (!empty($this->week))
			{
		\Jeff\Code\Util\D::p('edit');

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
			}
			else
			{
		\Jeff\Code\Util\D::p('new');
				try
				{
					$week = Service::create($this->post);
					if (!empty($week))
					{
						$message = "This week created successfully";
						$this->week = $week;
						$this->acted = true;
						$this->mode = 'edit';
						$this->title= 'Edit';
					}
				}
				catch (Exception $e)
				{
					$message = "This week already exists, use that or create a different range";
				}
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "{$this->title} Week";
	}

	public function content(): void
	{
		$data = $this->week->data ?? $this->post;
		\Jeff\Code\Util\D::p('data', $data);
		?>
		<script>
			function save_form()
			{
				$('#week_form').append("<?=new Input('action', 'hidden', $this->mode)?>");
				return true;
			}
		</script>
		<?php
		echo new Form([
			new Inputs([ 
				new Input('week_id', 'hidden', $data['week_id'] ?? ''),
				new Date('start_date', $data['start_date'] ?? '', date('Y-m-d', strtotime('last sunday')), 'Start Date'),
				new Date('end_date', $data['end_date'] ?? '', date('Y-m-d', strtotime('next saturday')), 'End Date'),
			], 'date', 'date'),
			new Input('save_week', 'submit', 'Submit', '', '', new Attributes(['onclick' => 'return save_form()'])),
			new Input('cancel_week', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
		], 'post', new Attributes(['id' => 'week_form']));
	}
}
