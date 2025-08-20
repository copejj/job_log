<?php
namespace Jeff\Code\Model\Company;

use Jeff\Code\Model\Record;

class Company extends Record
{
	protected static function getKey(): string
	{
		return 'company_id';
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
				$this->data['name'],
				$this->data['email'] ?? '',
				$this->data['website'] ?? '',
			];

			if (empty($this->data['company_id']))
			{
				$this->sql = 
					"INSERT into companies (
						name
						, email
						, website
					) 
					values (?, ?, ?) 
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE companies 
					set name = ?
						, email = ?
						, website = ?
					where company_id = ? 
					returning *";
				$this->bind[] = $this->data['company_id'];
			}
		}
		return true;
	}

	protected static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['name']):
				return false;
		}
		return true;
	}

	public static function getInstance(array $data): Company
	{
		return new Company($data);
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
				select company_id
				from companies {$sql_cond}
			), job_count as (
				select count(job_log_id) job_count, company_id
				from job_logs
					join target using (company_id)
				group by company_id 
			)
			select company_id as id, companies.*, coalesce(job_count, 0) as job_count
			from target
				join companies using (company_id)
				left join job_count using (company_id)
			order by name";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from companies where {$key} = ?";
	}
}
