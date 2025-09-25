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
				$_SESSION['user_id'],
				$this->data['week_id'],
				$this->data['action_date'], 
				$this->data['company_id'] ?? null, 
				$this->data['title'] ?? null, 
				$this->data['job_number'] ?? null, 
				$this->data['next_step'] ?? null, 
				$this->data['notes'] ?? null, 
				$this->data['confirmation'] ?? null, 
				$this->data['contact'] ?? null, 
				$this->data['contact_number'] ?? null, 
			];

			if (empty($this->data[$key]))
			{
				$this->sql = 
					"INSERT into job_logs (
						user_id
						, week_id
						, action_date
						, company_id
						, title
						, job_number
						, next_step
						, notes
						, confirmation
						, contact
						, contact_number
					)
					values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
					returning *";
			}
			else
			{
				$this->sql = 
					"UPDATE job_logs
					set user_id = ?
						, week_id = ?
						, action_date = ?
						, company_id = ?
						, title = ?
						, job_number = ?
						, next_step = ?
						, notes = ?
						, confirmation = ?
						, contact = ?
						, contact_number = ?
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
			case empty($_SESSION['user_id']):
			case empty($data['week_id']):
			case empty($data['company_id']):
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
		$conds = ['user_id = ?'];
		$bind[] = $_SESSION['user_id'] ?? 0;

		$arguably = [
			static::getKey(),
			'week_id',
			'company_id',
		]; 
		foreach ($arguably as $arg)
		{
			if (!empty($args[$arg]))
			{
				$conds[] = "{$arg} = ?";
				$bind[] = $args[$arg];
			}
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
					, title
					, job_number
					, next_step
					, notes
					, confirmation
					, contact
					, contact_number
					, week_id
					, start_date
					, end_date
					, company_id
					, name
					, website
				from job_logs
					left join weeks using (week_id)
					left join companies using (company_id, user_id) {$sql_cond}
			), actions as (
				with data as (
					select job_log_id, job_log_action_id, action_id, actions.name as action_name
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
					select job_log_id, job_log_method_id, method_id, methods.name as method_name
					from job_logs
						join job_log_methods using (job_log_id)
						join methods using (method_id)
				)
				select job_log_id
					, jsonb_agg(to_jsonb(data.*) - 'job_log_id') as methods_json
				from data
				group by job_log_id
			), statuses as (
				select distinct on (job_log_id) job_log_id, status_id, status, status_date
				from job_logs
					join job_log_statuses using (job_log_id)
					join statuses using (status_id)
				order by job_log_id, job_log_statuses.status_date desc
			), address as (
				select distinct on (company_id) company_id
					, street
					, street_ext
					, city
					, states.name as state
					, zip
				from job_logs
					join company_addresses using (company_id)
					join addresses using (address_id)
					left join states using (state_id)
				where coalesce(street, '') != ''
				order by company_id, addresses.created_date desc, address_id
			)
			select job_logs.*
				, actions_json
				, methods_json
				, street
				, street_ext
				, city
				, state
				, zip
				, status, status_id, status_date
			from job_logs
				left join actions using (job_log_id)
				left join methods using (job_log_id) 
				left join statuses using (job_log_id)
				left join address using (company_id)";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		$bind[] = $_SESSION['user_id'];
		return "DELETE from job_logs where {$key} = ? and user_id = ?";
	}
}