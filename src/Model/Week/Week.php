<?php
namespace Jeff\Code\Model\Week;

use Jeff\Code\Model\Record;

class Week extends Record
{
	protected static function getKey(): string
	{
		return 'week_id';
	}

	protected function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			if (empty($this->data['action_date']))
			{
				$this->bind = [
					$this->data['start_date'],
					$this->data['end_date'],
				];

				if (empty($this->data['week_id']))
				{
					$this->sql = 
						"INSERT into weeks (
							start_date
							, end_date
						) 
						values (?, ?) 
						on conflict do nothing
						returning *";
				}
				else 
				{
					$this->sql = 
						"UPDATE weeks 
						set start_date = ?
							, end_date = ? 
						where week_id = ? 
						on conflict do nothing
						returning *";
					$this->bind[] = $this->data['week_id'];
				}
			}
			else
			{
				$this->bind = [
					$this->data['action_date'],
				];

				$this->sql = 
					"WITH target as (
						select ?::date as action_date
					), week_days as (
						SELECT action_date - EXTRACT(DOW FROM action_date)::integer as start_date
							, action_date
							, action_date + 6 - EXTRACT(DOW FROM action_date)::integer as end_date
						from target
					)
					insert into weeks(start_date, end_date)
					select start_date, end_date
					from week_days
					on conflict do nothing
					returning *";
			}
		}
		return true;
	}

	protected static function validate(array $data): bool
	{
		if (!(empty($data['start_date']) && empty($data['end_date'])))
		{
			return true;
		}

		if (!(empty($data['action_date'])))
		{
			return true;
		}
		return false;
	}

	public static function getInstance(array $data): Week
	{
		return new Week($data);
	}

	public static function getSelect(array $args=[], array &$bind=[]): string
	{
		$conds = [];
		$arguably = [static::getKey()];
		foreach ($arguably as $arg)
		{
			if (!empty($args[$arg]))
			{
				$conds[] = "{$arg} = ?";
				$bind[] = $args[$arg];
			}
		}

		if (!empty($args['action_date']))
		{
			$conds[] = "?::date between start_date and end_date"; 
			$bind[] = $args['action_date'];
		}

		if (empty($conds))
		{
			$conds[] = "job_count > 0";
		}

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}
		$sql = 
			"WITH target as (
				select week_id
				from weeks
			), job_count as (
				select count(job_log_id) job_count, week_id
				from job_logs
					join target using (week_id)
				group by week_id 
			)
			select week_id as id, weeks.*, coalesce(job_count, 0) as job_count
			from target
				join weeks using (week_id)
				left join job_count using (week_id) {$sql_cond}
			order by start_date desc, end_date desc";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from week where {$key} = ?";
	}
}