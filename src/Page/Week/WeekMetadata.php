<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Data\Record;
use Jeff\Code\Template\Display\Formatter;
use Jeff\Code\Template\Display\Metadata;

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
		return 
			"<form method='post'>
				<input type='hidden' name='action' value='edit' />
				<input type='hidden' name='week_id' value='{$id}' />
				<input type='submit' name='edit_week' value='Edit' />
			</form>";
	}
}