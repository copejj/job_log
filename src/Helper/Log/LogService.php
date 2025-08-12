<?php
namespace Jeff\Code\Helper\Log;

use Jeff\Code\D;
use Jeff\Code\DB;

class LogService
{
	private $logs;

	private bool $update_data = true;

	public function __construct()
	{
		
	}

	public function init()
	{
		if ($this->update_data)
		{
			$sql = 
				"SELECT *
				from job_log";
			$logs = DB::getInstance(true)->fetchAll($sql);
			D::p('logs', $logs);
			
			if (!empty($logs))
			{
				$this->$logs = $logs;
				$this->update_data = false;
			}
		}
	}
}