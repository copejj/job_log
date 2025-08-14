<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Template\Display\Metadata;

class WeekMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'week_id' => [
				'label' => 'ID',
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
}