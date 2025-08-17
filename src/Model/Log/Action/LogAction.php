<?php
namespace Jeff\Code\Model\Log\Action;

use Jeff\Code\Model\Record;

use Jeff\Code\Util\DB;

class LogAction extends Record
{
	protected string $key_name = 'job_log_action_id';

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
				$this->data['action_id'], 
			];

			if (empty($this->data[$this->key_name]))
			{
				$this->sql = 
					"INSERT into job_log_action (
						job_log_id
						, action_id
					)
					values (?, ?)
					returning *";
			}
			else
			{
				$this->sql = 
					"UPDATE job_log_action
					set job_log_id = ?
						, action_id = ?
					where job_log_action_id = ?
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
			from job_log_action
			where job_log_action_id = ?";
		$data = DB::getInstance(true)->fetchOne($sql, [$id]);
		return static::getInstance($data);
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['job_log_id']):
			case empty($data['action_id']):
				return false;
		}
		return true;
	}	

	public static function getInstance(array $data): ?Record
	{
		if (static::validate($data))
		{
			return new LogAction($data);
		}
		return null;
	}


}