<?php
namespace Jeff\Code\Controller\Log;

use Jeff\Code\Model\Log\Logs as Service;
use Jeff\Code\View\Display\Metadata;
use Jeff\Code\View\Elements\Table;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Format\EditButton;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

class Logs extends HeaderedContent
{
	protected Service $service;

	public function processing(): void
	{
		if (empty($this->post['action']))
		{
			$this->service = Service::init();
		}
		else
		{
			$this->service = Service::init($this->post);
		}
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
		echo new Table(new LogMetadata(), $this->service->getAll());
	}
}


class LogMetadata extends Metadata
{
	public function init(): void
	{
		$this->metadata = [
			'edit_col' => [
				'label' => '',
				'format' => 'Jeff\Code\Controller\Log\LogEditButton',
			],
			'week_id' => [
				'label' => 'Week',
				'format' => 'Jeff\Code\Model\Entities\Weeks',
			],
			'company_name' => [
				'label' => 'Company',
			],
			'title' => [
				'label' => 'Title',
			],
			'action_date' => [
				'label' => 'Action Date',
				'format' => 'Jeff\Code\View\Elements\Date',
			],
			'title' => [
				'label' => 'Title',
			],
			'job_number' => [
				'label' => 'Job Number',
			],
			'next_step' => [
				'label' => 'Next Step',
			],
		];
	}
}

class LogEditButton extends EditButton
{
	protected static function getType(): string
	{
		return 'job_log';
	}
}