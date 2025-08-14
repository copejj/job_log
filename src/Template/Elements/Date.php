<?php
namespace Jeff\Code\Template\Elements;

use Jeff\Code\Template\Elements\Inputs;

class Date extends Inputs
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
}