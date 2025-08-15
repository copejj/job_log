<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Week\Weeks as Service;
use Jeff\Code\Template\Display\Attributes;
use Jeff\Code\Template\Elements\Table;
use Jeff\Code\Template\Elements\Form;
use Jeff\Code\Template\Elements\Input;
use Jeff\Code\Template\HeaderedContent;

class Weeks extends HeaderedContent
{
	protected Service $week;

	public function processing(): void
	{
		$this->week = Service::init();
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
		echo new Table(new WeekMetadata(), $this->week->getAll(), new Attributes(['class' => 'half-width']));
	}
}