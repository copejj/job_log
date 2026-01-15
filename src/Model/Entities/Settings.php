<?php
namespace Jeff\Code\Model\Entities;

use Jeff\Code\Util\D;
use Jeff\Code\Util\DB;

class Settings extends Entities
{
	protected int $divisor = 3;

	public function __construct()
	{
		parent::__construct('settings', 'user_id');
	}

	protected function init(): void
	{
		if ($this->update_data)
		{
			$sql = 
				"WITH target_data as (
					select user_id, ?::int as divisor
					from users
					where user_id = ?
						and inactive_date is null
				), job_logs as (
					select *
					from target_data
						join job_logs using (user_id)
				), log_counts as (
					select user_id, (count(1) / divisor) as limit_count
					from job_logs
					group by user_id, divisor
				), action_counts as (
					with data as (
						select action_id, count(1) as action_count
						from job_logs 
							join job_log_actions using (job_log_id)
							join actions using (action_id)
						group by action_id
					)
					select user_id, json_agg(action_id) as action_ids
					from data
						join log_counts on (limit_count <= action_count)
					group by user_id
				), method_counts as (
					with data as (
						select method_id, count(1) as method_count
						from job_logs 
							join job_log_methods using (job_log_id)
							join methods using (method_id)
						group by method_id
					)
					select user_id, json_agg(method_id) as method_ids
					from data
						join log_counts on (limit_count <= method_count)
					group by user_id
				), status_counts as (
					with data as (
						select status_id, count(1) as status_count
						from job_logs 
							join job_log_statuses using (job_log_id)
							join statuses using (status_id)
						group by status_id
					)
					select user_id, json_agg(status_id) as status_ids
					from data
						join log_counts on (limit_count <= status_count)
					group by user_id, status_count
					order by status_count desc limit 1
				), settings as (
					select action_ids, method_ids, status_ids
					from target_data
						join log_counts using (user_id)
						join action_counts using (user_id)
						join method_counts using (user_id)
						join status_counts using (user_id)
				)
				select *
				from settings";
			$rows = DB::getInstance(true)->fetchOne($sql, [$this->divisor, $_SESSION['user_id']]);

			$data = [];
			foreach (['action_ids', 'method_ids', 'status_ids'] as $field)
			{
				$data[$field] = [];
				if (!empty($rows[$field]))
				{
					$data[$field] = json_decode($rows[$field], true);
				}
			}
			$this->data = $data;
			$this->update_data = false;
		}
	}
}
