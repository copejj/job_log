<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Util\DB;

abstract class Entities
{
	protected string $table;
	protected string $order;
	protected string $key;
	protected string $name;

	protected int $default;
	protected array $data;

	protected bool $update_data = true;

	protected function __construct(string $table, string $key, string $name='', string $order='', int $default=0)
	{
		$this->table = $table;
		$this->order = (empty($order)) ? $key : $order;
		$this->key = $key;
		$this->name = $name;
		$this->default = $default;
	}

	public function __get($name): mixed
	{
		switch ($name)
		{
			case 'data':
			case 'default':
				$this->init();
				return $this->$name;
		}
		return null;
	}

	protected function init(): void
	{
		if ($this->update_data)
		{
			$sql = "SELECT * from {$this->table} order by {$this->order}";
			$rows = DB::getInstance(true)->fetchAll($sql);

			if (!empty($rows))
			{
				foreach ($rows as $row)
				{
					if (!empty($this->default))
					{
						$this->default = $row[$this->key];
					}
					$this->data[$row[$this->key]] = $this->name($row);
				}
			}
			$this->update_data = false;
		}
	}

	public function name(array $row): string
	{
		return $row[$this->name];
	}
}