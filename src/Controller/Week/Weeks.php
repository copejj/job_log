<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Week\Weeks as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\DataTableAttributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditAction;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

use Jeff\Code\Model\Record;

class Weeks extends HeaderedContent
{
	protected Service $service;

	public function processing(): void
	{
		$this->service = Service::init();
	}

	public function getTitle(): string
	{
		return "Work Weeks";
	}

	public function content(): void
	{
		echo new Table(new WeekMetadata(), $this->service->getAll(), new Attributes([]), new DataTableAttributes(['order' => '[[2, "desc"], [3, "desc"]]']));
	}
}

class WeekMetadata extends Metadata 
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'format' => 'Jeff\Code\Controller\Week\WeekAction',
				'class' => 'fit-width',
			],
			'job_count' => [
				'format' => 'Jeff\Code\Controller\Week\WeekViewAction',
				'class' => 'fit-width',
			],
			'start_date' => [ 'format' => 'Jeff\Code\View\Elements\Date' ], 
			'end_date' => [ 'format' => 'Jeff\Code\View\Elements\Date' ],
		];
	}
}

class WeekAction extends EditAction
{
	protected static function getForm(string $action, string $type, Record $data): string
	{
		$type_id = "week_id";
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
			new Input("detail_{$type}", 'button', 'Details', '', '', new Attributes(['onclick' => "openModal(\"" . htmlentities($query) . "\")"])),
		], 'post', static::getAttributes());
	}

	protected static function getType(): string
	{
		return 'week';
	}
}

class WeekViewAction extends EditAction
{
	protected static function getType(): string
	{
		return 'week';
	}

	protected static function getAttributes(): Attributes
	{
		return new Attributes([
			'action' => '/?page=log',
		]);
	}

	protected static function getAction(): string
	{
		return 'view';
	}

	protected static function getText(Record $data): string
	{
		$text = (int) $data->job_count;
		return "View: {$text}";
	}
}
