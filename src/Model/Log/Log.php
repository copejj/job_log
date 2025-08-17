<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Record;

class Log extends Record
{
	protected static function getKey(): string
	{
		return 'job_log_id';
	}

	protected function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$key = static::getKey();
			$this->bind = [
				$this->data['week_id'],
				$this->data['action_date'], 
				$this->data['company_id'] ?? null, 
				$this->data['contact_id'] ?? null, 
				$this->data['title'] ?? null, 
				$this->data['job_number'] ?? null, 
				$this->data['next_step'] ?? null, 
			];

			if (empty($this->data[$key]))
			{
				$this->sql = 
					"INSERT into job_logs (
						week_id
						, action_date
						, company_id
						, contact_id
						, title
						, job_number
						, next_step
					)
					values (?, ?, ?, ?, ?, ?, ?)
					returning *";
			}
			else
			{
				$this->sql = 
					"UPDATE job_logs
					set week_id = ?
						, action_date = ?
						, company_id = ?
						, contact_id = ?
						, title = ?
						, job_number = ?
						, next_step = ?
					where job_log_id = ?
					returning *";
				$this->bind[] = $this->data[$key];
			}
		}
		return true;
	}

	protected static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['week_id']):
			case empty($data['action_date']):
				return false;
		}
		return true;
	}

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new Log($data);
		}
		return null;
	}

	public static function getSelect(array $args = [], array &$bind = []): string
	{
		$conds = [];
		$key = static::getKey();
		if (!empty($args[$key]))
		{
			$conds[] = "{$key} = ?";
			$bind[] = $args[$key];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}
		$sql = 
			"SELECT job_logs.*
				, start_date
				, end_date
			from job_logs
				join weeks using (week_id) {$sql_cond}";
		return $sql;
	}
}