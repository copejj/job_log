<?php
namespace Jeff\Code\Helper\Week;

use Exception;

use Jeff\Code\DB;
use Jeff\Code\Helper\Data\Record;

class Week extends Record
{
	public function save(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$bind = [
				$this->data['start_date'],
				$this->data['end_date'],
			];

			if (empty($this->data['week_id']))
			{
				$sql = "INSERT into weeks (start_date, end_date) values (?, ?) returning *";
			}
			else 
			{
				$sql = "UPDATE weeks set start_date = ?, end_date = ? where week_id = ? returning *";
				$bind[] = $this->data['week_id'];
			}
			$result = DB::getInstance()->fetchOne($sql, $bind);
		}
		return !empty($result['week_id']);
	}

	public static function load(int $id): Week
	{
		$sql = 
			"SELECT *
			from weeks
			where week_id = ?";
		$data = DB::getInstance(true)->fetchOne($sql, [$id]);
		return static::getInstance($data);
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['start_date']):
			case empty($data['end_date']):
				return false;
		}
		return true;
	}

	public static function getInstance(array $data): Week
	{
		return new Week($data);
	}
}