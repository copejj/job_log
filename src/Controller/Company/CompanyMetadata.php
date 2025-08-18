<?php
namespace Jeff\Code\Controller\Company;

use Jeff\Code\Model\Record;

use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Format\Formatter;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;

class CompanyMetadata extends Metadata implements Formatter
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Company\CompanyMetaData',
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
			],
		];
	}

	public static function format(string $key, Record $data): string
	{
		$id = $data->company_id;
		if (empty($id))
		{
			return '';
		}

		return new Form([
			new Input('action', 'hidden', 'edit'),
			new Input('company_id', 'hidden', $id),
			new Input('edit_company', 'submit', 'Edit'),
		]);
	}
}
