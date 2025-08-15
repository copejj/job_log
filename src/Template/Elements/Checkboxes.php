<?php
namespace Jeff\Code\Template\Elements;

class Checkboxes extends Input
{
	protected array $data;
	protected array $selected;

	/**
	 * Creates a html select
	 * @param string $name The name of the elements, will return 'name[id]'
	 * @param array $data The target data array should be $data['id'] = 'text' format
	 * @param array $selected The selected IDs if there are ones
	 */
	public function __construct(string $name, array $data, array $selected=[], string $title='')
	{
		$this->name = $name;
		$this->data = $data;
		$this->title = $title;
		$this->selected = [];
		if (!empty($selected))
		{
			foreach ($selected as $id)
			{
				$this->selected[$id] = $id;
			}
		}
	}

	public function __toString()
	{
		$result = '';
		if (!empty($this->data))
		{
			$inputs = [];
			foreach ($this->data as $id => $text)
			{
				$selector = (empty($this->selected[$id])) ? "" : " checked='checked'";
				$inputs[] = "<span class='check_span'><input name='{$this->name}[$id]' class='input check_input' type='checkbox' value='{$id}'{$selector} /> {$text}</span>";
			}

			if (!empty($inputs))
			{
				$result = $this->getLabel() . " <div id='{$this->name}_group' class='input_cont'> " . implode($inputs) . " </div>";
			}
		}
		return $result;
	}
}