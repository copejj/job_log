<?php
namespace Jeff\Code\Model\Week;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Records;

class Weeks extends Records
{
	public static function init(array $args=[]): Weeks
	{
		$bind = [];
		$sql = Week::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Weeks($results);
	}

	public static function getInstance(array $row): ?Week
	{
		return Week::getInstance($row);
	}
}
