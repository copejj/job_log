<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs as Service;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

class Logs extends HeaderedContent
{
	protected Service $service;

	public function processing(): void
	{
		$this->service = Service::init();
	}

	public function getTitle(): string
	{
		return "Logs";
	}

	public function content(): void
	{
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_log', 'submit', 'New'),
		]);
		echo new Table(new LogMetadata(), $this->service->getAll(), new Attributes(['class' => 'half-width']));
	}
}