<?php
namespace Jeff\Code\Template\Elements;

use Jeff\Code\Template\Display\Attributes;

class Input
{
	protected string $label;
	protected string $name;
	protected string $type = 'text';
	protected string $value;
	protected Attributes $attr;

	public function __construct(string $name, string $type='text', string $value='', string $default='', string $label='', ?Attributes $attr=null)
	{
		$this->label = $label;
		$this->name = $name;
		$this->type = $type;
		$this->value = (empty($value)) ? $default : $value;
		$this->attr = new Attributes([
			'class' => "input {$this->type}_input",
			'id' => "{$this->type}_{$this->name}",
		]);
		if (!empty($attr))
		{
			$this->attr->merge($attr);
		}
	}

	public function getLabel() 
	{
		if (!empty($this->label))
		{
			return "<label for='{$this->type}_{$this->name}' class='input-label'>{$this->label}</label>";
		}
		return "";
	}

	public function __toString()
	{
		return "<input type='{$this->type}' name='{$this->name}' value='{$this->value}' {$this->attr} />";
	}
}