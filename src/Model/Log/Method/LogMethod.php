<?php
namespace Jeff\Code\Model\Log\Method;

use Jeff\Code\Model\Record;

use Jeff\Code\Util\DB;

class LogMethod extends Record
{
	protected string $key_name = 'job_log_method_id';

	public function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$this->bind = [
				$this->data['job_log_id'],
				$this->data['method_id'], 
			];

			if (empty($this->data[$this->key_name]))
			{
				$this->sql = 
					"INSERT into job_log_method (
						job_log_id
						, method_id
					)
					values (?, ?)
					returning *";
			}
			else
			{
				$this->sql = 
					"UPDATE job_log_method
					set job_log_id = ?
						, method_id = ?
					where job_log_method_id = ?
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
			from job_log_method
			where job_log_method_id = ?";
		$data = DB::getInstance(true)->fetchOne($sql, [$id]);
		return static::getInstance($data);
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['job_log_id']):
			case empty($data['method_id']):
				return false;
		}
		return true;
	}	

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new LogMethod($data);
		}
		return null;
	}


}