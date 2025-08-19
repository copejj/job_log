<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Format\EditButton;
use Jeff\Code\View\Elements\Format\ViewButton;

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
