<?php
namespace Jeff\Code\Template\Elements;

class Radio extends Input
{
	protected array $data;
	protected string $selected;

	public function __construct(string $name, array $data, string $selected='', string $label='')
	{
		$this->data = $data;
		$this->name = $name;
		$this->label = $label;
		$this->selected = $selected;
	}

	public function __toString()
	{
		$result = '';
		if (!empty($this->data))
		{
			$inputs = [];
			foreach ($this->data as $id => $text)
			{
				$selector = ($id == $this->selected) ? " checked='checked'" : "";
				$inputs[] = "<span class='radio-span'><input name='{$this->name}' class='input check-input' type='radio' value='{$id}'{$selector} /> {$text}</span>";
			}

			if (!empty($inputs))
			{
				$result = "<fieldset id='{$this->name}_group'> " . implode($inputs) . " </fieldset>";
			}
		}
		return $result;
	}
}