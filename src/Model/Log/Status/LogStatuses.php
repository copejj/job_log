<?php
namespace Jeff\Code\Model\Log\Status;

use Jeff\Code\Model\Records;
use Jeff\Code\Util\DB;

class LogStatuses extends Records
{
	public static function init(array $args=[]): Records
	{
		$bind = [];
		$sql = LogStatus::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new LogStatuses($results);
	}

	public static function getInstance(array $row): ?LogStatus
	{
		return new LogStatus($row);
	}
}