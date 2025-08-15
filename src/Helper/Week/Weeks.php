<?php
namespace Jeff\Code\Helper\Week;

use Jeff\Code\DB;
use Jeff\Code\Helper\Data\Records;

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
			"SELECT week_id as id, *
			from weeks {$sql_cond}
			order by start_date desc, end_date desc";
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Weeks($results);
	}

	public static function getInstance(array $row): ?Week
	{
		return Week::getInstance($row);
	}
}