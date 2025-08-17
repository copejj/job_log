<?php
namespace Jeff\Code\Model\Week;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Records;

class Weeks extends Records
{
	public static function init(array $args=[]): Weeks
	{
		$bind = [];
		$conds = [];
		if (!empty($args['week_id']))
		{
			$conds[] = 'week_id = ?';
			$bind[] = $args['week_id'];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = implode(' and ', $conds);
		}
		$sql = 
			"WITH target as (
				select week_id
				from weeks {$sql_cond}
			), job_count as (
				select count(job_log_id) job_count, week_id
				from job_logs
					join target using (week_id)
				group by week_id 
			)
			select week_id as id, weeks.*, coalesce(job_count, 0) as job_count
			from target
				join weeks using (week_id)
				left join job_count using (week_id)
			order by start_date desc, end_date desc";
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Weeks($results);
	}

	public static function getInstance(array $row): ?Week
	{
		return Week::getInstance($row);
	}
}