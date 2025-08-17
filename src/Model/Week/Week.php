<?php
namespace Jeff\Code\Model\Week;

use Jeff\Code\Model\Record;

use Jeff\Code\Util\DB;

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
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE weeks 
					set start_date = ?
						, end_date = ? 
					where week_id = ? 
					returning *";
				$this->bind[] = $this->data['week_id'];
			}
			$result = DB::getInstance()->fetchOne($this->sql, $this->bind);
		}
		return !empty($result['week_id']);
	}

	protected static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['start_date']):
			case empty($data['end_date']):
				return false;
		}
		return true;
	}

	public static function getInstance(array $data): Week
	{
		return new Week($data);
	}

	public static function getSelect(array $args=[], array &$bind=[]): string
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
			"WITH target as (
				select week_id
				from weeks {$sql_cond}
			), job_count as (
				select count(job_log_id) job_count, week_id
				from job_logs
					join target using (week_id)
				group by week_id 
			)
			select week_id as id, weeks.*, coalesce(job_count, 0) as job_count
			from target
				join weeks using (week_id)
				left join job_count using (week_id)
			order by start_date desc, end_date desc";
		return $sql;
	}
}