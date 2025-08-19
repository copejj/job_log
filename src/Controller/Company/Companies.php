<?php
namespace Jeff\Code\Controller\Company;

use Jeff\Code\Model\Company\Companies as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditButton;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;


use Jeff\Code\Model\Record;

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

class CompanyMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Company\CompanyEditButton',
			],
			'name' => [
				'label' => 'Name',
			],
			'email' => [
				'label' => 'Email',
			],
			'website' => [
				'label' => 'Website',
			],
			'job_count' => [
				'label' => '#',
				'format' => 'Jeff\Code\Controller\Company\CompanyViewButton',
			],
		];
	}
}

class CompanyEditButton extends EditButton
{
	protected static function getType(): string
	{
		return 'company';
	}
}

class CompanyViewButton extends CompanyEditButton
{
	protected static function getAction(): string
	{
		return 'view';
	}

	protected static function getText(Record $data): string
	{
		$text = (int) $data->job_count;
		return "View: {$text}";
	}
}
