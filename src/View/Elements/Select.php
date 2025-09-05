<?php
namespace Jeff\Code\View\Elements;

use Jeff\Code\View\Display\Attributes;

class Select extends Input
{
	protected array $data;
	protected int $default;
	protected int $selected;
	protected string $option_label;

	public function __construct(string $name, array $data, int $selected=0, int $default=0, string $label='', string $option_label='', ?Attributes $attr=null)
	{
		$this->name = $name;
		$this->data = $data;
		$this->selected = $selected;
		$this->default = $default;
		$this->label = $label;
		$this->option_label = $option_label;
		$this->type = 'select';
		$this->attr = new Attributes([
			'class' => 'input select-input',
			'id' => "select-{$this->name}",
		]);
		if (!empty($attr))
		{
			$this->attr->merge($attr);
		}
	}

	public function __toString()
	{
		$options = [];
		if (!empty($this->option_label))
		{
			$options[] = "<option value=''>{$this->option_label}</option>";
		}

		if (!empty($this->data))
		{
			$selected = (empty($this->selected)) ? $this->default : $this->selected;
			foreach ($this->data as $id => $text)
			{
				$selector = ($id == $selected) ? " selected='selected'" : "";
				$options[] = "<option value='{$id}'{$selector}>{$text}</option>";
			}
		}

		$select = '';
		if (!empty($options))
		{
			$select = "<select name='{$this->name}' {$this->attr}>" . implode($options) . "</select>";
		}
		return $select;
	}
}