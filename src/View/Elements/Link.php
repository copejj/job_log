<?php
namespace Jeff\Code\View\Elements;

use Exception;
use Jeff\Code\Util\D;
use Jeff\Code\View\Display\Attributes;

class Link
{
	protected array $data = [
		'page' => '',
		'label' => '[ overwrite me ]',
		'attr' => null,
		'selected' => false,
	];

	public function __construct(string $page, string $label, ?Attributes $attr = null)
	{
		$this->page = $page;
		$this->label = $label;
		$this->attr = new Attributes(['class' => 'link']);
		if (!empty($attr))
		{
			$this->attr->merge($attr);
		}
	}

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

	public function __isset($key)
	{
		return ($this->__get($key) !== null);
	}

	public function __toString(): string
	{
		$ref = (empty($this->page)) ? '' : "?page={$this->page}";
		return "<li {$this->attr}><a href='/{$ref}'>{$this->label}</a></li>";
	}
}