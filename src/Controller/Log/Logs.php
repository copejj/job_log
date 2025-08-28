<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs as Service;
use Jeff\Code\Model\Record;
use Jeff\Code\Model\Entities\Companies;
use Jeff\Code\Model\Entities\Weeks;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditAction;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Select;
use Jeff\Code\View\HeaderedContent;

class Logs extends HeaderedContent
{
	protected Companies $companies;
	protected Service $service;
	protected Weeks $weeks;

	public function processing(): void
	{
		$this->weeks = new Weeks();
		$this->companies = new Companies();

		$args = [];
		if (empty($this->post['action']))
		{
			$args = [
				'action' => 'view',
				'week_id' => $this->weeks->default,
			];
		}
		else 
		{
			switch (true)
			{
				case !empty($this->post['week_id']):
				case !empty($this->post['company_id']):
					$args = $this->post;
			}
		}
		$this->service = Service::init($args);
	}

	public function getTitle(): string
	{
		return "Logs";
	}

	public function content(): void
	{
		?>
		<script>
			function set_filter(select)
			{
				$(select).closest('form').submit();
			}
		</script>
		<?php
		$week_default = (empty($this->post['action'])) ? $this->weeks->default : 0;
		$attrs = new Attributes(['class' => 'inline-form']);
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_log', 'submit', 'New'),
		], 'post', $attrs);
		echo new Form([
			new Input('action', 'hidden', 'view'),
			new Select('week_id', $this->weeks->data, (int) ($this->post['week_id'] ?? 0), $week_default, 'Week', '[ All weeks ]', new Attributes(['onchange' => 'set_filter(this)'])),
			new Select('company_id', $this->companies->data, (int) ($this->post['company_id'] ?? 0), 0, 'Company', '[ All companies ]', new Attributes(['onchange' => 'set_filter(this)'])),
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
				'format' => 'Jeff\Code\Controller\Log\LogAction',
				'class' => 'fit-width',
			],
			'title' => [ ],
			'name' => [ ],
			'action_date' => [ 'format' => 'Jeff\Code\View\Elements\Date' ],
			'job_number' => [ ],
		];
	}
}

class LogAction extends EditAction
{
	protected static function getForm(string $action, string $type, Record $data): string
	{
		$type_id = "job_log_id";
		$id = $data->$type_id;
		if (empty($id))
		{
			return '';
		}

		$query = '/?' . http_build_query([
			'page' => 'log',
			'action' => 'details',
			$type_id => $id, 
		]);
		return new Form([
			new Input('action', 'hidden', $action),
			new Input($type_id, 'hidden', $id),
			new Input("edit_{$type}", 'submit', 'Edit'),
			new Input("detail_{$type}", 'button', 'Details', '', '', new Attributes(['onclick' => "openModal(\"" . htmlentities($query) . "\")"])),
		], 'post', static::getAttributes());
	}

	protected static function getType(): string
	{
		return 'job_log';
	}
}