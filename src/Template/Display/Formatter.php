<?php
namespace Jeff\Code\Template\Display;

interface Formatter
{
	public static function format(string $data): string;
}
