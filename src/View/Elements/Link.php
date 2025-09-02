<?php
namespace Jeff\Code\View\Elements;

use Exception;

abstract class Link
{
	protected array $data = [
		'page' => '',
		'label' => '[ overwrite me ]',
	];

	public function __get(string $name): mixed
	{
		if (array_key_exists($name, $this->data))
		{
			return $this->data[$name];
		}
		else
		{
			throw new Exception("Array key '{$name}' does not exist.");
		}
	}

	public function __set(string $name, mixed $value): void
	{
		if (array_key_exists($name, $this->data))
		{
			$this->data[$name] = $value;
			$this->data['update_data'] = true;
		}
		else
		{
			throw new Exception("Array key '{$name}' does not exist.");
		}
	}
}