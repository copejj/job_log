<?php
namespace Jeff\Code\Template\Display;

class Table
{
	protected Metadata $metadata;
	protected array $data;

	public function __construct(Metadata $metadata, array $data)
	{
		$this->metadata = $metadata;
		$this->data = $data;
	}

	public function __toString()
	{
		
	}
}
