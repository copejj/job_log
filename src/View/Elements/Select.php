<?php
namespace Jeff\Code\View\Elements;

class Select extends Input
{
	protected array $data;
	protected int $default;
	protected int $selected;
	protected string $option_label;

	public function __construct(string $name, array $data, int $selected=0, int $default=0, string $label='', string $option_label='')
	{
		$this->name = $name;
		$this->data = $data;
		$this->selected = $selected;
		$this->default = $default;
		$this->label = $label;
		$this->option_label = $option_label;
		$this->type = 'select';
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
			$select = "<select class='input select-input' id='select-{$this->name}' name='{$this->name}'>" . implode($options) . "</select>";
		}
		return $select;
	}
}