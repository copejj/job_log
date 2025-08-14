<?php
namespace Jeff\Code\Template\Elements;

use Jeff\Code\Template\Display\Formatter;
use Jeff\Code\Template\Elements\Inputs;

use Jeff\Code\Helper\Data\Record;

class Date extends Inputs implements Formatter
{
	protected string $name;
	protected string $default;
	protected string $date;

	public function __construct(string $name, string $date='', string $default='', string $title='')
	{
		$this->title = $title;

		$this->name = $name;
		$this->date = (empty($date)) ? $default : $date;
	}

	public function __toString()
	{
		return $this->getLabel() . 
			"<div id='{$this->name}_group' class='input_cont date_input_cont'> 
				<input type='date' class='input date_input' id='select_{$this->name}' name='{$this->name}' value='{$this->date}' />
			</div>";
	}

	public static function format(string $key, Record $data): string
	{
		if (empty($data))
		{
			return '';
		}
		return date("F j, Y", strtotime($data->$key));
	}
}