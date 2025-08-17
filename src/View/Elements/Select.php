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
		$this->default= $default;
		$this->selected = $selected;
		$this->label = $label;
		$this->option_label = $option_label;
		$this->type = 'select';
	}

	public function __toString()
	{
		$result = '';
		if (!empty($this->data))
		{
			$options = [];
			$selected = (empty($this->selected)) ? $this->default : $this->selected;
			foreach ($this->data as $id => $text)
			{
				$selector = ($id == $selected) ? " selected='selected'" : "";
				$options[] = "<option value='{$id}'{$selector}>{$text}</option>";
			}

			if (!empty($options))
			{
				$labelOption = '';
				if (!empty($this->option_label))
				{
					$labelOption = "<option value=''>{$this->option_label}</option>";
				}

				$result = "<select class='input select-input' id='select_{$this->name}' name='{$this->name}'>{$labelOption}" . implode($options) . "</select>";
			}
		}
		return $result;
	}
}