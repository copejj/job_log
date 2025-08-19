<?php
namespace Jeff\Code\Model\Log;

use Jeff\Code\Model\Record;

class Log extends Record
{
	public static function getKey(): string
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
			"WITH job_logs as (
				select job_log_id
					, action_date
					, company_id
					, contact_id
					, title
					, job_number
					, next_step
					, week_id
					, start_date
					, end_date
				from job_logs
					join weeks using (week_id) {$sql_cond}
			), actions as (
				with data as (
					select job_log_id, job_log_action_id, action_id, name
					from job_logs
						join job_log_actions using (job_log_id)
						join actions using (action_id)
				)
				select job_log_id
					, jsonb_agg(to_jsonb(data.*) - 'job_log_id') as actions_json
				from data
				group by job_log_id
			), methods as (
				with data as (
					select job_log_id, job_log_method_id, method_id, name
					from job_logs
						join job_log_methods using (job_log_id)
						join methods using (method_id)
				)
				select job_log_id
					, jsonb_agg(to_jsonb(data.*) - 'job_log_id') as methods_json
				from data
				group by job_log_id
			), company as (
				select company_id
					, name as company_name
				from job_logs
					join companies using (company_id)
			)
			select job_logs.*
				, company_name
				, actions_json
				, methods_json
			from job_logs
				left join company using (company_id)
				left join actions using (job_log_id)
				left join methods using (job_log_id)";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from job_logs where {$key} = ?";
	}
}