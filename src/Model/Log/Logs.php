<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

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