<?php
namespace Jeff\Code\Template\Elements;

use Jeff\Code\Template\Display\Formatter;
use Jeff\Code\Template\Elements\Input;

use Jeff\Code\Helper\Data\Record;

class Date extends Input implements Formatter
{
	public function __construct(string $name, string $date='', string $default='', string $title='')
	{
		parent::__construct($name, 'date', $date, $default, $title);
	}

	public static function format(string $key, Record $data): string
	{
		if (empty($data))
		{
			return '';
		}
		return date("m/d/Y", strtotime($data->$key));
	}
}