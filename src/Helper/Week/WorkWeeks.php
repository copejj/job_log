<?php
namespace Jeff\Code\Helper\Week;

use Jeff\Code\DB;
use Jeff\Code\Helper\Data\Records;

class WorkWeeks extends Records
{
	protected array $weeks;

	public static function init(): WorkWeeks
	{
		$sql = 
			"SELECT *
			from work_weeks";
		$results = DB::getInstance(true)->fetchAll($sql);
		return new WorkWeeks($results);
	}

	protected function getInstance(array $row): ?WorkWeek
	{
		return new WorkWeek($row);
	}
}