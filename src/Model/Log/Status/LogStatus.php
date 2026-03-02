<?php
namespace Jeff\Code\Model\Log\Status;

use Jeff\Code\Model\Record;
use Jeff\Code\Util\D;

class LogStatus extends Record
{
	protected static function getKey(): string
	{
		return 'job_log_id';
	}

	public function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$this->bind = [
				$this->data[static::getKey()],
				$this->data['status_id'], 
				$this->data[static::getKey()],
				$this->data['status_id'], 
			];

			$this->sql = 
				"INSERT INTO job_log_statuses (job_log_id, status_id)
				SELECT ?, ?
				WHERE (
					SELECT status_id 
					FROM job_log_statuses 
					WHERE job_log_id = ? 
					ORDER BY status_date DESC 
					LIMIT 1
				) IS DISTINCT FROM ?
				RETURNING *";
		}
		return true;
	}

	public static function getSelect(array $args = [], array &$bind = []): string
	{
		$conds = [];
		$key = static::getKey();
		$conds[] = "{$key} = ?";
		$bind[] = $args[$key];

		if (!empty($args['status_id']))
		{
			$conds[] = "status_id = ?";
			$bind[] = $args['status_id'];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}

		$sql = 
			"SELECT *
			from job_log_statuses {$sql_cond} ";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from job_log_statuses where {$key} = ?";
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data[static::getKey()]):
			case empty($data['status_id']):
				return false;
		}
		return true;
	}	

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new LogStatus($data);
		}
		return null;
	}

}