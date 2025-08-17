<?php
namespace Jeff\Code\Model\Log\Method;

use Jeff\Code\Model\Record;

class LogMethod extends Record
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
				$this->data['method_id'], 
			];

			$this->sql = 
				"INSERT into job_log_method (
					job_log_id
					, method_id
				)
				values (?, ?)
				returning * on conflict do nothing";
		}
		return true;
	}

	public static function getSelect(array $args = [], array &$bind = []): string
	{
		$conds = [];
		$key = static::getKey();
		$conds[] = "{$key} = ?";
		$bind[] = $args[$key];

		if (!empty($args['method_id']))
		{
			$conds[] = "method_id = ?";
			$bind[] = $args['method_id'];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}

		$sql = 
			"SELECT *
			from job_log_method {$sql_cond} ";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from job_log_method where {$key} = ?";
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data[static::getKey()]):
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