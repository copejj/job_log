<?php
namespace Jeff\Code\Helper\Log;

use Jeff\Code\DB;
use Jeff\Code\Helper\Data\Record;
use Jeff\Code\Helper\Data\Records;

class Logs extends Records
{
	public static function init(): Logs
	{
		$sql = 
			"SELECT *
			from job_logs";
		$results = DB::getInstance(true)->fetchAll($sql);
		return new Logs($results);
	}

	public static function getInstance(array $row): ?Record
	{
		return new Log($row);
	}
}