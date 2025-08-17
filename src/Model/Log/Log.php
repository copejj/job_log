<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Util\DB;
use Jeff\Code\Model\Record;

class Log extends Record
{
	protected string $key_name = 'job_log_id';

	public function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$this->bind = [
				$this->data['week_id'],
				$this->data['action_date'], 
				$this->data['company_id'] ?? null, 
				$this->data['contact_id'] ?? null, 
				$this->data['title'] ?? null, 
				$this->data['job_number'] ?? null, 
				$this->data['next_step'] ?? null, 
			];

			if (empty($this->data[$this->key_name]))
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
				$this->bind[] = $this->data[$this->key_name];
			}
		}
		return true;
	}

	public static function load(int $id): ?Record
	{
		$sql = 
			"SELECT *
			from job_logs
			where job_log_id = ?";
		$data = DB::getInstance(true)->fetchOne($sql, [$id]);
		return static::getInstance($data);
	}

	public static function validate(array $data): bool
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

}