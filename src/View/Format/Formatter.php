<?php
namespace Jeff\Code\View\Format;

use Jeff\Code\Model\Record;

interface Formatter
{
	public static function format(string $key, Record $data): string;
}
