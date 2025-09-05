<?php
namespace Jeff\Code\Model\Log\Method;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

use Jeff\Code\Util\DB;

class LogMethods extends Records
{
	public static function init(array $args=[]): Records
	{
		$bind = [];
		$sql = LogMethod::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new LogMethods($results);

	}

	public static function getInstance(array $row): ?Record
	{
		return new LogMethod($row);
	}
}