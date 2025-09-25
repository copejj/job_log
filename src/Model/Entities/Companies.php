<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Util\DB;

class Companies extends Entities
{
	public function __construct()
	{
		parent::__construct('companies', 'company_id', 'name', 'name');
	}

	protected function init(): void
	{
		if ($this->update_data)
		{
			$sql = 
				"SELECT * 
				from {$this->table} 
				where user_id = ?
				order by {$this->order}";
			$rows = DB::getInstance(true)->fetchAll($sql, [$_SESSION['user_id']]);

			if (!empty($rows))
			{
				foreach ($rows as $row)
				{
					if (!empty($row['default']))
					{
						$this->default = $row[$this->key];
					}
					$this->data[$row[$this->key]] = $this->name($row);
				}
			}
			$this->update_data = false;
		}
	}
}
