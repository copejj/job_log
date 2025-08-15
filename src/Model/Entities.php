<?php
namespace Jeff\Code\Model;

use Exception;

use Jeff\Code\DB;

abstract class Entities
{
	private string $type;

	protected int $default;
	protected array $data;

	protected bool $update_data = true;

	protected function __construct(string $type)
	{
		if (trim($type) === '')
		{
			throw new Exception('Type cannot be null or blank');
		}

		$this->type = $type;
	}

	public function data(): array
	{
		$this->init();
		return $this->data;
	}

	public function default(): int
	{
		$this->init();
		return $this->default;
	}

	protected function init(): void
	{
		if ($this->update_data)
		{
			$sql = "SELECT * from {$this->type}s order by {$this->type}_id";
			$results = DB::getInstance(true)->fetchAll($sql);

			if (!empty($results))
			{
				foreach ($results as $result)
				{
					if (!empty($result['default']))
					{
						$this->default = $result["{$this->type}_id"];
					}
					$this->data[$result["{$this->type}_id"]] = $result['name'];
				}
			}
			$this->update_data = false;
		}
	}
}