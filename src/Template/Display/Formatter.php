<?php
namespace Jeff\Code\Template\Display;

use Jeff\Code\Helper\Data\Record;

interface Formatter
{
	public static function format(string $key, Record $data): string;
}
