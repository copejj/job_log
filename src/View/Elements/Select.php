<?php
namespace Jeff\Code\View\Elements;

class Select extends Input
{
	protected array $data;
	protected int $default;
	protected int $selected;

	/**
	 * Creates a html select
	 * @param array $data The target data array should be $data['id'] = 'text' format
	 * @param integer $selected The selected ID if there is one
	 * @param string $label The label to display if nothing is selected
	 */
	public function __construct(string $name, array $data, int $default=0, int $selected=0, string $label='')
	{
		$this->name = $name;
		$this->data = $data;
		$this->default= $default;
		$this->selected = $selected;
		$this->label = $label;
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
				$titleOption = '';
				if (!empty($this->label))
				{
					$labelOption = "<option value=''>{$this->label}</option>";
				}

				$result = "<select class='input select-input' id='select_{$this->name}' name='{$this->name}'>{$labelOption}" . implode($options) . "</select>";
			}
		}
		return $result;
	}
}