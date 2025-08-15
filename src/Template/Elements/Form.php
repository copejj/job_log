<?php
namespace Jeff\Code\Template\Elements;

use Exception;

use Jeff\Code\Template\Display\Printable;
use Jeff\Code\Template\Display\Attributes;

class Form implements Printable
{
	protected array $inputs;
	protected string $method;
	protected Attributes $attrs;

	/**
	 * A basic form
	 * @param Input[] $inputs
	 * @param string $method
	 * @param Attributes|null $attrs
	 */
	public function __construct(array $inputs, string $method='post', ?Attributes $attrs=null)
	{
		if (empty($inputs))
		{
			throw new Exception("Argument 'inputs' cannot be empty");
		}

		$this->inputs = [];
		foreach ($inputs as $input)
		{
			if ($input instanceof Input)
			{
				$this->inputs[] = $input;
			}
			else
			{
				throw new Exception('All inputs must be an instance of the class Input');
			}
		}

		$this->method = $method ?? 'post';
		$this->attrs = $attrs ?? new Attributes();
	}

	public function __toString()
	{
		return "<form method='{$this->method}' {$this->attrs}>" . implode($this->inputs). "</form>";
	}
}