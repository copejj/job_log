<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\View\Display\Metadata;

use Jeff\Code\View\Elements\Format\EditButton;

class LogMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Log\LogEditButton',
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
				'label' => 'Job Number',
			],
			'next_step' => [
				'label' => 'Next Step',
			],
		];
	}
}

class LogEditButton extends EditButton
{
	protected static function getType(): string
	{
		return 'job_log';
	}
}
