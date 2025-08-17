<?php
namespace Jeff\Code\Model\Log\Action;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Records;

use Jeff\Code\Util\DB;

class LogActions extends Records
{
	public static function init(array $args=[]): Records
	{
		$bind = [];
		$sql = LogAction::getSelect($args, $bind);
		$results = DB::getInstance(true)->fetchAll($sql, $bind);
		return new LogActions($results);

	}

	public static function getInstance(array $row): ?Record
	{
		return new LogAction($row);
	}
}