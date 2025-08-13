<?php
namespace Jeff\Code\Template\Elements;

abstract class Inputs
{
	protected string $name;
	protected string $title;

	public abstract function __tostring();

	public function getLabel() 
	{
		if (!empty($this->title))
		{
			return "<label for='{$this->name}_group' class='input_label'>{$this->title}</label>";
		}
		return "";
	}
}