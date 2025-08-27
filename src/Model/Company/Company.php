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
				$this->data['phone'] ?? '',
			];

			if (empty($this->data['company_id']))
			{
				$this->sql = 
					"INSERT into companies (
						name
						, email
						, website
						, phone 
					) 
					values (?, ?, ?, ?) 
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE companies 
					set name = ?
						, email = ?
						, website = ?
						, phone = ?
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
		$arguably = [ static::getKey() ];
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
			"WITH target as (
				select company_id
				from companies {$sql_cond}
			), addresses as (
				with data as (
					select *
					from company_addresses
						join addresses using (address_id)
				)
				select company_id
					, jsonb_agg(to_jsonb(data.*) - 'company_id') as addresses_json
				from data
				group by company_id
			), job_count as (
				select count(job_log_id) job_count, company_id
				from job_logs
					join target using (company_id)
				group by company_id 
			)
			select company_id
				, name
				, email
				, website
				, phone
				, addresses_json
				, coalesce(job_count, 0) as job_count
			from target
				join companies using (company_id)
				left join addresses using (company_id)
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
