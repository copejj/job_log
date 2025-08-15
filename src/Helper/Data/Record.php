<?php
namespace Jeff\Code\Helper\Data;

use Exception;

abstract class Record
{
	protected array $data;

	protected bool $update_data = false;

	public abstract function save(): bool;
	public abstract static function load(int $id): ?Record;
	public abstract static function validate(array $data): bool;
	public abstract static function getInstance(array $data): ?Record;

	public function __construct(array $data)
	{
		$this->data = $data;
	}


	public static function create(array $data): ?Record
	{
		if (!static::validate($data))
		{
			return null;
		}

		$record = static::getInstance($data);
		$record->update_data = true;
		$record->save();
		return $record;
	}

	public function __get(string $name): string
	{
		return $this->data[$name] ?? '';
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