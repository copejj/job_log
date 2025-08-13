<?php
namespace Jeff\Code\Helper\Log;

use Exception;

use Jeff\Code\DB;

class WorkWeek
{
	protected array $data;

	protected bool $update_data = false;

	public function __construct($work_week_id, $start_date, $end_date)
	{
		$this->data['work_week_id'] = $work_week_id;
		$this->data['start_date'] = $start_date;
		$this->data['end_date'] = $end_date;
	}

	public function __get($name)
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

	public function __set($name, $value): void
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

	public function save(): bool
	{
		if ($this->update_data)
		{
			$sql = 
				"UPDATE work_week
				set start_date = ?
					, end_date = ?
				where work_week_id = ?
				returning *";
			$bind = [
				$this->data['start_date'],
				$this->data['end_date'],
				$this->data['work_week_id'],
			];
			$result = DB::getInstance()->fetchOne($sql, $bind);
			if (!empty($result['work_week_id']))
			{
				return true;
			}
		}
		return false;
	}

	public static function create($start_date, $end_date): ?WorkWeek
	{
		$sql = "INSERT into work_week(start_date, end_date) values (?, ?) returning *";
		$result = DB::getInstance()->fetchOne($sql, [$start_date, $end_date]);
		if (!empty($result['work_week_id']))
		{
			return new WorkWeek($result['work_week_id'], $result['start_date'], $result['end_date']);
		}
		return null;
	}
}