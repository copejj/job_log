<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Record;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Format\Formatter;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;

class LogMetadata extends Metadata implements Formatter
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Log\LogMetaData',
			],
			'week_id' => [
				'label' => 'Week',
				'format' => 'Jeff\Code\Model\Entities\Weeks',
			],
			'company_name' => [
				'label' => 'Company',
			],
			'title' => [
				'label' => 'Title',
			],
			'action_date' => [
				'label' => 'Action Date',
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'contact_id' => [
				'label' => 'Contact',
			],
			'title' => [
				'label' => 'Title',
			],
			'job_number' => [
				'label' => 'job_number',
			],
			'next_step' => [
				'label' => 'job_number',
			],
		];
	}

	public static function format(string $key, Record $data): string
	{
		$id = $data->job_log_id;
		if (empty($id))
		{
			return '';
		}

		return new Form([
			new Input('action', 'hidden', 'edit'),
			new Input('job_log_id', 'hidden', $id),
			new Input('edit_week', 'submit', 'Edit'),
		]);
	}
}
