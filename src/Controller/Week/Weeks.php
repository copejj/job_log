<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Week\Weeks as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditButton;
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
				'label' => '',
				'format' => 'Jeff\Code\Controller\Week\WeekEditButton',
			],
			'start_date' => [
				'label' => 'Start Date',
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'end_date' => [
				'label' => 'End Date',
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'job_count' => [
				'label' => '# Logs',
				'format' => 'Jeff\Code\Controller\Week\WeekViewButton',
			]
		];
	}
}

class WeekEditButton extends EditButton
{
	protected static function getType(): string
	{
		return 'week';
	}
}

class WeekViewButton extends WeekEditButton
{
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
