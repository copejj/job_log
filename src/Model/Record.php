<?php
namespace Jeff\Code\Model;

use Exception;

use Jeff\Code\Util\DB;

abstract class Record
{
	protected array $data;
	protected string $sql = '';
	protected array $bind = [];

	protected bool $update_data = false;

	protected abstract function onSave(): bool;
	protected abstract static function getKey(): string;
	protected abstract static function validate(array $data): bool;

	public abstract static function getDelete(array $args=[], array &$bind=[]): string;
	public abstract static function getSelect(array $args=[], array &$bind=[]): string;
	public abstract static function getInstance(array $data): ?Record;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public static function delete(array $data): void
	{
		$bind = [];
		$sql = static::getDelete($data, $bind);
		DB::getInstance()->perform($sql, $bind);
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
			$record->update_data = false;
			return $record;
		}
		return null;
	}

	public function save(): bool
	{
		$this->onSave();
		$result = DB::getInstance()->fetchOne($this->sql, $this->bind);
		return !empty($result[static::getKey()]);
	}

	public static function load(int $id): ?Record
	{
		$bind = [];
		$sql = static::getSelect([static::getKey() => $id], $bind);
		$data = DB::getInstance(true)->fetchOne($sql, $bind);
		return static::getInstance($data);
	}

	public function __get(string $name): mixed
	{
		if ($name === 'data')
		{
			return $this->data ?? [];
		}
		return $this->data[$name] ?? '';
	}

	public function __set(string $name, mixed $value): void
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