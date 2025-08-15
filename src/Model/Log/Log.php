<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\DB;
use Jeff\Code\Model\Record;

class Log extends Record
{
	public function save(): bool
	{
		return false;
	}

	public static function load(int $id): ?Record
	{
		$sql = 
			"SELECT *
			from job_log
			where job_log_id = ?";
		$data = DB::getInstance(true)->fetchOne($sql, [$id]);
		return static::getInstance($data);
	}

	public static function validate(array $data): bool
	{
		return true;
	}

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new Log($data);
		}
		return null;
	}

}