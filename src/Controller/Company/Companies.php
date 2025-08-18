<?php
namespace Jeff\Code\Controller\Company;

use Jeff\Code\Model\Company\Companies as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

class Companies extends HeaderedContent
{
	protected Service $service;

	public function processing(): void
	{
		$this->service = Service::init();
	}

	public function getTitle(): string
	{
		return "Companies";
	}

	public function content(): void
	{
		echo new Form([
			new Input('action', 'hidden', 'add'),
			new Input('add_company', 'submit', 'New'),
		]);
		echo new Table(new CompanyMetadata(), $this->service->getAll(), new Attributes([]));
	}
}