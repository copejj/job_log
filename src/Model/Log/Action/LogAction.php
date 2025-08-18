<?php
namespace Jeff\Code\Model\Log\Action;

use Jeff\Code\Model\Record;

class LogAction extends Record
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
				$this->data['action_id'], 
			];

			$this->sql = 
				"INSERT into job_log_action (
					job_log_id
					, action_id
				)
				values (?, ?)
				on conflict do nothing
				returning *";
			\Jeff\Code\Util\D::p('query', [$this->sql, $this->bind]);
		}
		return true;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from job_log_action where {$key} = ?";
	}

	public static function getSelect(array $args = [], array &$bind = []): string
	{
		$conds = [];
		$key = static::getKey();
		$conds[] = "{$key} = ?";
		$bind[] = $args[$key];

		if (!empty($args['action_id']))
		{
			$conds[] = "action_id = ?";
			$bind[] = $args['action_id'];
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}

		$sql = 
			"SELECT *
			from job_log_action {$sql_cond} ";
		return $sql;
	}

	public static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data[static::getKey()]):
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