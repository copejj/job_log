<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs as Service;
use Jeff\Code\Model\Entities\Weeks;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditButton;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Select;
use Jeff\Code\View\HeaderedContent;

class Logs extends HeaderedContent
{
	protected Service $service;
	protected Weeks $weeks;

	public function processing(): void
	{
		$this->weeks = new Weeks();
		if (empty($this->post['action']))
		{
			$this->service = Service::init();
		}
		else
		{
			$this->service = Service::init($this->post);
		}
	}

	public function getTitle(): string
	{
		return "Logs";
	}

	public function content(): void
	{
		?>
		<script>
			function filter_week(select)
			{
				$(select).closest('form').submit();
			}
		</script>
		<?php
		$attrs = new Attributes(['class' => 'inline-form']);
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_log', 'submit', 'New'),
		], 'post', $attrs);
		echo new Form([
			new Input('action', 'hidden', 'view'),
			new Select('week_id', $this->weeks->data, (int) ($this->post['week_id'] ?? 0), 0, 'Week', '[ Filter on a week ]', new Attributes(['onchange' => 'filter_week(this)'])),
		], 'post', $attrs);
		echo new Table(new LogMetadata(), $this->service->getAll());
	}
}


class LogMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Log\LogEditButton',
			],
			'title' => [
				'label' => 'Job Title',
			],
			'company_name' => [
				'label' => 'Employer',
			],
			'action_date' => [
				'label' => 'Contact Date',
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'job_number' => [
				'label' => 'Job Number',
			],
			'next_step' => [
				'label' => 'Next Step',
			],
		];
	}
}

class LogEditButton extends EditButton
{
	protected static function getType(): string
	{
		return 'job_log';
	}
}