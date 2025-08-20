<?php
namespace Jeff\Code\View\Elements;

use Exception;
use Jeff\Code\View\Display\Attributes;

class Inputs extends Input
{
	protected string $name;
	protected array $inputs;
	protected string $type = 'group';
	protected Attributes $attr;

	public function __construct(Input|array $inputs, string $label='', ?string $name=null, ?string $type=null, ?Attributes $attr=null)
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
		$this->attr = new Attributes([
			'class' => "input-cont {$this->type}-input-cont",
		]);
		if (!empty($attr))
		{
			$this->attr->merge($attr);
		}
	}

	public function getLabel() 
	{
		if (!empty($this->label))
		{
			return "<h3 class='input-label'>{$this->label}</h3>";
		}
		return "";
	}

	public function __toString()
	{
		$inputs = [];
		foreach ($this->inputs as $input)
		{
			$inputs[] = $input->getLabel() . $input;
		}
		return "<div {$this->attr}>" . implode($inputs) . "</div>";
	}
}