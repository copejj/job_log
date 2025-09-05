<?php
namespace Jeff\Code\View\Elements;

use Jeff\Code\View\Display\Attributes;

class TextArea extends Input
{
	public function __construct(string $name, string $value='', string $default='', string $label='', ?Attributes $attr=null)
	{
		$this->label = $label;
		$this->name = $name;
		$this->type = 'text-area';
		$this->value = (empty($value)) ? $default : $value;
		$this->attr = new Attributes([
			'class' => "input {$this->type}-input",
			'id' => "{$this->type}-{$this->name}",
			'rows' => '6', 
//			'cols' => '50',
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
			return "<label for='{$this->type}-{$this->name}' class='input-label'>{$this->label}</label>";
		}
		return "";
	}

	public function __toString()
	{
		return "<textarea name='{$this->name}' {$this->attr}>{$this->value}</textarea>";
	}
}