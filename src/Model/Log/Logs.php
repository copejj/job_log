<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

use Jeff\Code\Util\DB;

class Logs extends Records
{
	public static function init(array $args=[]): Logs
	{
		$bind = [];
		$sql = Log::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Logs($results);
	}

	public static function getInstance(array $row): ?Record
	{
		return new Log($row);
	}
}