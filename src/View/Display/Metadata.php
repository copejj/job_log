<?php
namespace Jeff\Code\View\Display;

use Exception;

use Jeff\Code\Model\Record;

abstract class Metadata
{
	protected array $metadata = [];

	protected abstract function init(): void;

	public function getRowHeader(Record $row): string
	{
		return '';
	}

	public function get(): array
	{
		$this->init();
		if (empty($this->metadata))
		{
			throw new Exception('Init function must provide metadata');
		}
		return $this->metadata;
	}

	public function keys(): array
	{
		return array_keys($this->get());
	}
}