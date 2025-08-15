<?php
namespace Jeff\Code\Template\Elements;

class Input
{
	protected string $label;
	protected string $name;
	protected string $type = 'text';
	protected string $value;
	protected string $attr;

	public function __construct(string $name, string $type='text', string $value='', string $default='', string $label='', string $attr='')
	{
		$this->label = $label;
		$this->name = $name;
		$this->type = $type;
		$this->value = (empty($value)) ? $default : $value;
		$this->attr = $attr . " class='input {$this->type}_input'";
	}

	public function getLabel() 
	{
		if (!empty($this->label))
		{
			return "<label for='{$this->name}_group' class='input_label'>{$this->label}</label>";
		}
		return "";
	}

	public function __toString()
	{
		return "<input type='{$this->type}' id='{$this->type}_{$this->name}' name='{$this->name}' value='{$this->value}' {$this->attr} />";
	}
}