<?php
namespace Jeff\Code\Template\Elements;

use Exception;

class Inputs extends Input
{
	protected string $name;
	protected array $inputs;
	protected string $type = 'group';

	public function __construct(Input|array $inputs, string $label='', ?string $name=null, ?string $type=null)
	{
		if (empty($inputs))
		{
			throw new Exception("Argument 'inputs' cannot be empty");
		}

		if (!is_array($inputs))
		{
			$inputs = [$inputs];
		}

		$this->inputs = [];
		foreach ($inputs as $input)
		{
			if ($input instanceof Input)
			{
				$this->inputs[] = $input;
				$this->name = $name ?? $input->name;
				$this->type = $type ?? $input->type;
			}
			else
			{
				throw new Exception('All inputs must be an instance of the class Input');
			}
		}
		$this->label = $label;
	}

	public function __toString()
	{
		$inputs = [];
		foreach ($this->inputs as $input)
		{
			$inputs[] = $input->getLabel() . $input;
		}
		return "<div id='{$this->name}_group' class='input-cont {$this->type}-input-cont'>" . implode($inputs) . "</div>";
	}
}