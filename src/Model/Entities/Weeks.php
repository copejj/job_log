<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\View\Format\Formatter;

use Jeff\Code\Model\Entities;
use Jeff\Code\Model\Week\Week; 

use Jeff\Code\View\Elements\Date;

class Weeks extends Entities
{
	public function __construct()
	{
		parent::__construct('weeks', 'week_id');
	}

	public function name(array $row): string
	{
		$week = new Week($row);
		return Date::format('start_date', $week) . ' - ' . Date::format('end_date', $week);
	}
}