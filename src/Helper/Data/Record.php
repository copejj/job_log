<?php
namespace Jeff\Code\Helper\Data;

use Exception;

abstract class Record
{
	protected array $data;

	protected bool $update_data = false;

	public abstract function save(): bool;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public static function getInstance(array $data): ?Record
	{
		if (empty($data))
		{
			return null;
		}

		return new Record($data);
	}

	public static function create(array $data): ?Record
	{
		if (empty($data))
		{
			return null;
		}

		$record = new Record($data);
		$record->update_data = true;
		$record->save();
		return $record;
	}

	public function __get(string $name): string
	{
		if (array_key_exists($name, $this->data))
		{
			return $this->data[$name];
		}
		else
		{
			throw new Exception("Array key '{$name}' does not exist.");
		}
	}

	public function __set(string $name, string $value): void
	{
		if (array_key_exists($name, $this->data))
		{
			$this->data[$name] = $value;
			$this->update_data = true;
		}
		else
		{
			throw new Exception("Array key '{$name}' does not exist.");
		}
	}

	public function __toString()
	{
		return print_r($this->data, true);
	}
}