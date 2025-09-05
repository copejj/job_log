<?php
namespace Jeff\Code\Model;

abstract class Records
{
	protected array $data = [];
	protected array $args = [];

	public abstract static function init(array $args=[]): Records;
	public abstract static function getInstance(array $row): ?Record;

	public function __construct(array $data)
	{
		if (!empty($data))
		{
			$record = null;
			foreach ($data as $row)
			{
				$record = static::getInstance($row);
				if (!empty($record))
				{
					$this->data[] = $record;
				}
			}
		}
	}

	public function setArgs(array $args): void
	{
		$this->args = $args;
	}

	public function __get(string $name): string
	{
		return $this->args[$name] ?? '';
	}

	public function __toString()
	{
		return print_r($this->data, true);
	}

	public function getAll(): array
	{
		return $this->data;
	}

	public function getKeys(): array
	{
		return array_keys($this->data);
	}
}