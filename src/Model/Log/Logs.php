<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

class Logs extends Records
{
	public static function init(array $args=[]): Logs
	{
		$bind = [];
		$conds = [];
		if (!empty($args['job_log_id']))
		{
			$conds[] = 'job_log_id = ?';
			$bind[] = $args['job_log_id'];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = implode(' and ', $conds);
		}
		$sql = 
			"SELECT job_logs.*
				, start_date
				, end_date
			from job_logs
				join weeks using (week_id) {$sql_cond}";
		$results = DB::getInstance(true)->fetchAll($sql);
		return new Logs($results);
	}

	public static function getInstance(array $row): ?Record
	{
		return new Log($row);
	}
}