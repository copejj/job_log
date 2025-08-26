<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs;
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
	public function init(): void
	{
		$this->metadata = [
			'action_date' => [ 'format' => 'Jeff\Code\View\Elements\Date' ],
			'title' => [ ],
			'company_name' => [ ],
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