<?php
namespace Jeff\Code\Template\Elements;

class Select extends Inputs
{
	protected array $data;
	protected int $default;
	protected int $selected;

	/**
	 * Creates a html select
	 * @param array $data The target data array should be $data['id'] = 'text' format
	 * @param integer $selected The selected ID if there is one
	 * @param string $title The title to display if nothing is selected
	 */
	public function __construct(string $name, array $data, int $default=0, int $selected=0, string $title='')
	{
		$this->name = $name;
		$this->data = $data;
		$this->default= $default;
		$this->selected = $selected;
		$this->title = $title;
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
				if (!empty($this->title))
				{
					$titleOption = "<option value=''>{$this->title}</option>";
				}

				$result = $this->getLabel() . "<select class='input select_input' id='select_{$this->name}' name='{$this->name}'>{$titleOption}" . implode($options) . "</select>";
			}
		}
		return $result;
	}
}