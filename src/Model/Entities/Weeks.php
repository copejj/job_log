<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Week\Week;
use Jeff\Code\Util\D;
use Jeff\Code\View\Elements\Date;
use Jeff\Code\View\Format\Formatter;

use Jeff\Code\Util\DB;

class Weeks extends Entities implements Formatter
{
	public function __construct()
	{
		$sql = 
			"SELECT week_id
			from weeks
			where now()::date between start_date and end_date
			order by start_date desc, end_date asc
			limit 1";
		$default = DB::getInstance(true)->fetchOne($sql);
		if (empty($default))
		{
			$week = new Week(['action_date' => date('Y-m-d')]);
			$week->save(true);
			$default = DB::getInstance(true)->fetchOne($sql);
		}
		$table = 
			"(
				WITH job_count as (
					select week_id, count(1) as job_count
					from weeks
						join job_logs using (week_id)
					group by week_id
				)
				select *
				from weeks
					join job_count using (week_id)
			) weeks";
		parent::__construct($table, 'week_id', 'name', 'start_date desc, end_date desc', $default['week_id'] ?? '');
	}

	public function name(array $row): string
	{
		return Weeks::format('week_id', new Week($row));
	}

	public static function format(string $key, Record $data): string
	{
		return Date::format('start_date', $data) . ' - ' . Date::format('end_date', $data);
	}
}
