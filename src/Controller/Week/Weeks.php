<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Week\Weeks as Service;

use Jeff\Code\View\Display\Attributes;
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
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_week', 'submit', 'New'),
		]);
		echo new Table(new WeekMetadata(), $this->service->getAll(), new Attributes(['class' => 'half-width']));
	}
}

class WeekMetadata extends Metadata 
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'format' => 'Jeff\Code\Controller\Week\WeekEditAction',
				'class' => 'fit-width',
			],
			'job_count' => [
				'format' => 'Jeff\Code\Controller\Week\WeekViewAction',
				'class' => 'fit-width',
			],
			'start_date' => [
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'end_date' => [
				'format' => 'Jeff\Code\View\Elements\Date',
			],
		];
	}
}

class WeekEditAction extends EditAction
{
	protected static function getType(): string
	{
		return 'week';
	}
}

class WeekViewAction extends WeekEditAction
{
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
