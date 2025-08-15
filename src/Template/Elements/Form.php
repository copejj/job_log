<?php
namespace Jeff\Code\Template\Elements;

use Exception;

class Form implements Element
{
	protected $inputs;
	protected $method;
	protected $attrs;

	/**
	 * A basic form
	 * @param Input[] $inputs
	 * @param string $method
	 * @param string|null $attrs
	 */
	public function __construct(array $inputs, string $method='post', string $attrs='')
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
		$this->attrs = $attrs;
	}

	public function __toString()
	{
		return "<form method='{$this->method}' {$this->attrs}>" . implode($this->inputs). "</form>";
	}
}