<?php
namespace Jeff\Code\Helper\Week;

use Jeff\Code\DB;
use Jeff\Code\Helper\Data\Records;

class Weeks extends Records
{
	public static function init(): Weeks
	{
		$sql = 
			"SELECT *
			from weeks";
		$results = DB::getInstance(true)->fetchAll($sql);
		return new Weeks($results);
	}

	public static function getInstance(array $row): ?Week
	{
		return Week::getInstance($row);
	}
}