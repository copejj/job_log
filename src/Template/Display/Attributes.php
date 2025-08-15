<?php
namespace Jeff\Code\Template\Display;

class Attributes implements Printable
{
	protected array $attrs = [];

	public function __construct(array $attrs=[])
	{
		$this->set($attrs);
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
			$attrs[] = "{$key}='". implode(' ', $values) . "'";
		}
		return implode(' ', $attrs);
	}

	public function add(array $attrs): void
	{
		if (!empty($attrs))
		{
			foreach ($attrs as $key => $values)
			{
				if (empty($this->attrs[$key]))
				{
					$this->attrs[$key] = [];
				}

				if (!empty($values))
				{
					if (!is_array($values))
					{
						$values = $this->split($values);
					}
					$this->attrs[$key] = array_merge($this->attrs[$key], $values);
				}
			}
		}
	}

	public function set(array $attrs): void
	{
		if (!empty($attrs))
		{
			foreach ($attrs as $key => $values)
			{
				$this->attrs[$key] = $this->split($values);
			}
		}
	}

	public function merge(Attributes $attr): void
	{
		$this->add($attr->attrs);
	}

	private function split(string $values): array
	{
		if (empty($values))
		{
			return [];
		}
		return preg_split('/[\s]+/', trim($values));
	}
}