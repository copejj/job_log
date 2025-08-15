<?php
namespace Jeff\Code\Controller\Week;

use Jeff\Code\Model\Week\Weeks as Service;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

class Weeks extends HeaderedContent
{
	protected Service $service;

	public function processing(): void
	{
		$this->service = Service::init();
	}

	public function getTitle(): string
	{
		return "Work Weeks";
	}

	public function content(): void
	{
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_week', 'submit', 'New'),
		]);
		echo new Table(new WeekMetadata(), $this->service->getAll(), new Attributes(['class' => 'half-width']));
	}
}