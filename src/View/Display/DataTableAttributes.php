<?php
namespace Jeff\Code\View\Display;

class DataTableAttributes extends Attributes
{
	public function __construct(array $attrs=[])
	{
		$this->add($attrs);
	}

	public function __toString(): string
	{
		if (empty($this->attrs))
		{
			return '';
		}

		$attrs = [];
		foreach ($this->attrs as $key => $values)
		{
			$attrs[] = "\"{$key}\": {$values},";
		}
		return implode(' ', $attrs);
	}

	public function add(array $attrs): void
	{
		if (!empty($attrs))
		{
			foreach ($attrs as $key => $values)
			{
				if (!empty($values))
				{
					$this->attrs[$key] = $values;
				}
			}
		}
	}

}