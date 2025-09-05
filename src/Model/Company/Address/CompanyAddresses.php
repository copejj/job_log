<?php
namespace Jeff\Code\Model\Company\Address;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Records;

class CompanyAddresses extends Records
{
	public static function init(array $args=[]): CompanyAddresses
	{
		$bind = [];
		$sql = CompanyAddress::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new CompanyAddresses($results);
	}

	public static function getInstance(array $row): ?CompanyAddress
	{
		return CompanyAddress::getInstance($row);
	}
}