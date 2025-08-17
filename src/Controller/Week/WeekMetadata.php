<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Format\Formatter;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;

class WeekMetadata extends Metadata implements Formatter
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Week\WeekMetaData',
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
			]
		];
	}

	public static function format(string $key, Record $data): string
	{
		$id = $data->week_id;
		if (empty($id))
		{
			return '';
		}

		return new Form([
			new Input('action', 'hidden', 'edit'),
			new Input('week_id', 'hidden', $id),
			new Input('edit_week', 'submit', 'Edit'),
		]);
	}
}