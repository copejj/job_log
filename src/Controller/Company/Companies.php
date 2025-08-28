<?php
namespace Jeff\Code\Controller\Company;

use Jeff\Code\Model\Company\Companies as Service;

use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditAction;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;


use Jeff\Code\Model\Record;
use Jeff\Code\View\Format\Formatter;

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
				'format' => 'Jeff\Code\Controller\Company\CompanyAction',
				'class' => 'fit-width',
			],
			'job_count' => [
				'format' => 'Jeff\Code\Controller\Company\CompanyViewAction',
				'class' => 'fit-width',
			],
			'name' => [ ],
			'website' => [ 
				'format' => 'Jeff\Code\Controller\Company\CompanyNewTab' 
			],
			'email' => [ ],
			'phone' => [ ],
		];
	}
}

class CompanyNewTab implements Formatter
{
	public static function format(string $key, Record $data): string
	{
		$ref = $data->$key ?? '';
		if (empty($ref))
		{
			return '';
		}
		$display = $ref;
		$index = strpos($display, '?');
		if ($index !== false)
		{
			$display = substr($display, 0, $index);
		}
		return "<a href='{$ref}' target=_blank>{$display}</a>";
	}
}

class CompanyAction extends EditAction
{
	protected static function getForm(string $action, string $type, Record $data): string
	{
		$type_id = "company_id";
		$id = $data->$type_id;
		if (empty($id))
		{
			return '';
		}

		$query = '/?' . http_build_query([
			'page' => 'log',
			'action' => 'details',
			$type_id => $id, 
		]);
		return new Form([
			new Input('action', 'hidden', $action),
			new Input($type_id, 'hidden', $id),
			new Input("edit_{$type}", 'submit', 'Edit'),
			new Input("detail_{$type}", 'button', 'Details', '', '', new Attributes(['onclick' => "openModal(\"" . htmlentities($query) . "\")"])),
		], 'post', static::getAttributes());
	}

	protected static function getType(): string
	{
		return 'company';
	}
}

class CompanyViewAction extends EditAction
{
	protected static function getType(): string
	{
		return 'company';
	}

	protected static function getAttributes(): Attributes
	{
		return new Attributes([
			'action' => '/?page=log',
		]);
	}

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
