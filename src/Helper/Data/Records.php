<?php
namespace Jeff\Code\Helper\Data;

abstract class Records
{
	protected array $data = [];

	public abstract static function init(): Records;
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

	public function __toString()
	{
		return print_r($this->data, true);
	}
}