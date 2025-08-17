<?php
namespace Jeff\Code\Model\Log\Method;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

use Jeff\Code\Util\DB;

class LogMethods extends Records
{
	public static function init(array $args=[]): Records
	{
		$conds[] = 'job_log_id = ?';
		$bind = [$args['job_log_id'] ?? 0];

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = implode(' and ', $conds);
		}
		$sql = 
			"SELECT *
			from job_log_methods {$sql_cond}";
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new LogMethods($results);

	}

	public static function getInstance(array $row): ?Record
	{
		return new LogMethod($row);
	}
}