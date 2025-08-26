<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs;
use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
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
		$readonly_attr = new Attributes(['readonly' => '']);
		$inputs = [];
		$md = new LogDetailsMetadata();
		$metadata = $md->get();
		$labels = new Labels(array_keys($metadata));
	
		foreach ($this->logs->getAll() as $log)
		{
			$input = [];
			\Jeff\Code\Util\D::p('log', $log);
			foreach ($metadata as $key => $meta)
			{
				$input[] = new Input($key, 'text', $log->$key ?? '', '', $labels->$key, $readonly_attr);
			}
			$inputs[] = new Inputs($input);
		}
		echo new Inputs($inputs);
	}
}

class LogDetailsMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'action_date' => [ 'format' => 'Jeff\Code\View\Elements\Date', ],
			'title' => [ ],
			'company_name' => [ ],
			'street' => [ ],
			'street_ext' => [ ],
			'city' => [ ],
			'state' => [ ],
			'zip' => [ ],
			'website' => [ ],
		];
	}
}