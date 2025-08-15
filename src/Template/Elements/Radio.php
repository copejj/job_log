<?php
namespace Jeff\Code\Template\Elements;

class Radio extends Input
{
	protected array $data;
	protected string $selected;

	/**
	 * Creates a html select
	 * @param string $name The name of the elements, will return 'name[id]'
	 * @param array $data The target data array should be $data['id'] = 'text' format
	 * @param string $selected The selected IDs if there are ones
	 */
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
				$inputs[] = "<span class='radio_span'><input name='{$this->name}' class='input check_input' type='radio' value='{$id}'{$selector} /> {$text}</span>";
			}

			if (!empty($inputs))
			{
				$result = "<fieldset id='{$this->name}_group'> " . implode($inputs) . " </fieldset>";
			}
		}
		return $result;
	}
}