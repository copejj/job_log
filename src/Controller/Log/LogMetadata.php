<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Display\Formatter;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;

class LogMetadata extends Metadata implements Formatter
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Week\WeekMetaData',
			],
		];
	}

	public static function format(string $key, Record $data): string
	{
		$id = $data->log_job_id;
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