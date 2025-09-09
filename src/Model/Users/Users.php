<?php
namespace Jeff\Code\Model\Users;

use Jeff\Code\Model\Records;
use Jeff\Code\Util\DB;

class Users extends Records
{
	public static function init(array $args=[]): Users
	{
		$bind = [];
		$sql = User::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new Users($results);
	}

	public static function getInstance(array $row): ?User
	{
		return new User($row);
	}
}