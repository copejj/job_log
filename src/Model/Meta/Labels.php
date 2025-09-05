<?php
namespace Jeff\Code\Model\Meta;

use Jeff\Code\Util\Bind;
use Jeff\Code\Util\DB;

class Labels
{
	protected array $keys;
	protected array $data;

	protected bool $update_data = true;

	public function __construct(array $keys = [])
	{
		$this->keys = $keys;
	}

	public function init(): void
	{
		if ($this->update_data)
		{
			$bind = [];
			$conds = [];
			if (!empty($this->keys))
			{
				$conds[] = "key in (" . Bind::get($this->keys, $bind) . ")";
			}

			$sql_cond = '';
			if (!empty($conds))
			{
				$sql_cond = "where " . implode(' and ', $conds);
			}

			$sql = 
				"SELECT key, label
				from labels {$sql_cond}";
			$data = DB::getInstance(true)->fetchAll($sql, $bind);
			foreach ($data as $d)
			{
				if (!empty($d['key']))
				{
					$this->data[$d['key']] = trim($d['label'] ?? '');
				}
			}
			$this->update_data = false;
		}
	}

	public static function getInstance(): Labels
	{
		return new Labels();
	}

	public function __get($name): string
	{
		$this->init();
		if (isset($this->data[$name]))
		{
			return trim($this->data[$name]);
		}
		return "<i>{$name}</i>";
	}
}