<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs;
use Jeff\Code\Model\Record;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\DetailsTable;
use Jeff\Code\View\HeadlessContent;

class LogDetails extends HeadlessContent
{
	protected Logs $logs;

	protected function processing(): void
	{
		$this->logs = Logs::init($this->get);
	}

	protected function getTitle(): string
	{
		return 'Log Details';
	}

	protected function content(): void
	{
		echo new DetailsTable(new LogDetailsMetadata(), $this->logs->getAll(), new Attributes(['readonly' => '']));
	}
}

class LogDetailsMetadata extends Metadata
{
	public function getRowHeader(Record $row): string
	{
		return "<div class='details-header-name'>{$row->name}:</div><div class='details-header-title'>{$row->title}</div>";
	}

	public function init(): void
	{
		$this->metadata = [
			'week_id' => [ 'format' => 'Jeff\Code\Model\Entities\Weeks' ],
			'action_date' => [ 'format' => 'Jeff\Code\View\Elements\Date' ],
			'title' => [ ],
			'job_number' => [ ],
			'name' => [ ],
			'street' => [ ],
			'street_ext' => [ ],
			'city' => [ ],
			'state' => [ ],
			'zip' => [ ],
			'website' => [ ],
			'notes' => [ 'type' => 'textarea' ],
		];
	}
}