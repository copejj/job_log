<?php
namespace Jeff\Code\Helper\Log;

use Jeff\Code\Helper\Data\Record;

class Log extends Record
{
	public function save(): bool
	{
		return false;
	}

	public static function create(array $data): ?Log
	{
		return null;
	}
}