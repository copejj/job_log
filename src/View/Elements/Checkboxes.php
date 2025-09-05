<?php
namespace Jeff\Code\View\Elements;

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
	public function __construct(string $name, array $data, array $selected=[], string $label='')
	{
		$this->name = $name;
		$this->data = $data;
		$this->label = $label;
		$this->type = 'checkboxes';
		$this->selected = [];
		if (!empty($selected))
		{
			foreach ($selected as $id => $aid)
			{
				$this->selected[$id] = $aid ?? '';
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
				$value = $this->selected[$id] ?? '';
				$selector = (empty($value)) ? "" : " checked='checked'";
				$inputs[] = 
					"<div class='check-div'>
						<input id='{$this->type}-{$this->name}-{$id}' name='{$this->name}[$id]' class='input check-input' type='checkbox' value='{$value}'{$selector} />
						<label for='{$this->type}-{$this->name}-{$id}' class='check-label'>{$text}</label>
					</div>";
			}

			if (!empty($inputs))
			{
				$result = implode($inputs);
			}
		}
		return $result;
	}
}