<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs;
use Jeff\Code\View\Display\Attributes;
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
		\Jeff\Code\Util\D::p('request', ['get' => $this->get, 'post' => $this->post]);
		$readonly_attr = new Attributes(['readonly' => '']);
		foreach ($this->logs->getAll() as $log)
		{
			\Jeff\Code\Util\D::p('log', $log);
			echo new Inputs([
				new Input('company_name', 'text', $log->company_name, '', 'Company Name', $readonly_attr),
			]);
		}

	}
}