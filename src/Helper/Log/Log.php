<?php
namespace Jeff\Code\Helper\Log;

use Jeff\Code\Helper\Data\Record;

class Log extends Record
{
	public function save(): bool
	{
		return false;
	}

	public static function validate(array $data): bool
	{
		return false;
	}

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new Record($data);
		}
		return null;
	}
}