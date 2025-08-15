<?php
namespace Jeff\Code\Template\Elements;

class Input
{
	protected string $title;
	protected string $name;
	protected string $type = 'text';
	protected string $value;

	public function __construct(string $name, string $type='text', string $value='', string $default='', string $title='')
	{
		$this->title = $title;
		$this->name = $name;
		$this->type = $type;
		$this->value = (empty($value)) ? $default : $value;
	}

	public function getLabel() 
	{
		if (!empty($this->title))
		{
			return "<label for='{$this->name}_group' class='input_label'>{$this->title}</label>";
		}
		return "";
	}

	public function __toString()
	{
		return $this->getLabel() . 
			"<div id='{$this->name}_group' class='input_cont {$this->type}_input_cont'> 
				<input type='{$this->type}' class='input {$this->type}_input' id='{$this->type}_{$this->name}' name='{$this->name}' value='{$this->value}' />
			</div>";
	}
}