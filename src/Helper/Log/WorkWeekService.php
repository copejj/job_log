<?php
namespace Jeff\Code\Helper\Log;

use Jeff\Code\DB;

class WorkWeekService
{
	protected WorkWeeks $data;

	protected bool $update_data = true;

	public function init()
	{
		if ($this->update_data)
		{
			$sql = 
				"SELECT *
				from work_weeks";
			$results = DB::getInstance(true)->fetchAll($sql);
			if (!empty($results))
			{
				$this->data = new WorkWeeks();
				
				$this->update_data = false;
			}
		}
	}

	public function getAll()
	{
	}
}
