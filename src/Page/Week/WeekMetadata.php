<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Data\Record;
use Jeff\Code\Template\Display\Formatter;
use Jeff\Code\Template\Display\Metadata;
use Jeff\Code\Template\Elements\Form;
use Jeff\Code\Template\Elements\Input;

class WeekMetadata extends Metadata implements Formatter
{
	public function init(): void
	{
		$this->metadata = [
			'week_id' => [
				'label' => 'ID',
				'format' => 'Jeff\Code\Page\Week\WeekMetaData',
			],
			'start_date' => [
				'label' => 'Start Date',
				'format' => 'Jeff\Code\Template\Elements\Date',
			],
			'end_date' => [
				'label' => 'End Date',
				'format' => 'Jeff\Code\Template\Elements\Date',
			],
		];
	}

	public static function format(string $key, Record $data): string
	{
		$id = $data->$key;
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