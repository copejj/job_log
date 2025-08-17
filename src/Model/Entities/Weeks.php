<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Model\Record;
use Jeff\Code\Model\Week\Week;
use Jeff\Code\View\Format\Formatter;
use Jeff\Code\View\Elements\Date;

class Weeks extends Entities implements Formatter
{
	public function __construct()
	{
		parent::__construct('weeks', 'week_id', 'name', 'start_date desc, end_date desc');
	}

	public function name(array $row): string
	{
		return Weeks::format('week_id', new Week($row));
	}

	public static function format(string $key, Record $data): string
	{
		return Date::format('start_date', $data) . ' - ' . Date::format('end_date', $data);
	}
}