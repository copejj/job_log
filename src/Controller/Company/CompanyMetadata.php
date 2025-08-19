<?php
namespace Jeff\Code\Controller\Company;

use Jeff\Code\Model\Record;

use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Format\EditButton;

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
