<?php
namespace Jeff\Code\Model\Company;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Records;

class Companies extends Records
{
	public static function init(array $args=[]): Companies
	{
		$bind = [];
		$sql = Company::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Companies($results);
	}

	public static function getInstance(array $row): ?Company
	{
		return Company::getInstance($row);
	}
}
