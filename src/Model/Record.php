<?php
namespace Jeff\Code\Model;

use Exception;

use Jeff\Code\Util\DB;

abstract class Record
{
	protected array $data;
	protected string $key_name;
	protected string $sql = '';
	protected array $bind = [];

	protected bool $update_data = false;

	public abstract function onSave(): bool;
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
		if ($record->save())
		{
			return $record;
		}
		return null;
	}

	public function save(): bool
	{
		$this->onSave();
		\Jeff\Code\Util\D::p('query', [$this->sql, $this->bind]);
		$result = DB::getInstance()->fetchOne($this->sql, $this->bind);
		return !empty($result[$this->key_name]);
	}

	public function __get(string $name): mixed
	{
		if ($name === 'data')
		{
			return $this->data ?? [];
		}
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