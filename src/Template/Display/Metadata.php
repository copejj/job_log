<?php
namespace Jeff\Code\Template\Display;

use Exception;

abstract class Metadata
{
	protected array $metadata = [];

	public abstract function init(): void;

	public function get(): array
	{
		$this->init();
		if (empty($this->metadata))
		{
			throw new Exception('Init function must provide metadata');
		}
		return $this->metadata;
	}
}