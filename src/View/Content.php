<?php
namespace Jeff\Code\View;

use Exception;
use Jeff\Code\Model\Meta\Labels;

abstract class Content
{
	protected array $data = [
		'get' => null,
		'post' => null,
		'message' => '',
		'links' => null,
		'selected' => null,
		'update_data' => false,
		'has_redirect' => null,
		'labels' => null,
		'acted' => false,
	];

	protected function init(): void { }
	protected function processing(): void { }
	protected function header(): void { }
	protected function links(): void { }
	protected function top(): void { }
	protected function messages(): void {}
	protected function title(): void {}
	protected function content(): void {}
	protected function bottom(): void {}
	protected function footer(): void {}

	public function display(): void
	{
		$this->labels = new Labels();
		$this->init();
		$this->processing();
		$this->header();
		$this->links();
		$this->top();
		$this->messages();
		$this->title();
		$this->content();
		$this->bottom();
		$this->footer();
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
}