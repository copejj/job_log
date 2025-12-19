<?php
namespace Jeff\Code\Model\Company;

use Jeff\Code\Model\Record;
use Jeff\Code\Util\DB;

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

			$key = static::getKey();
			$this->bind = [
				$_SESSION['user_id'],
				$this->data['name'],
				$this->data['email'] ?? '',
				$this->data['website'] ?? '',
				$this->data['phone'] ?? '',
				$this->data['fax'] ?? '',
			];

			if (empty($this->data['company_id']))
			{
				$this->sql = 
					"INSERT into companies (
						user_id
						, name
						, email
						, website
						, phone 
						, fax
					) 
					values (?, ?, ?, ?, ?, ?) 
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE companies 
					set user_id = ?
						, name = ?
						, email = ?
						, website = ?
						, phone = ?
						, fax = ?
					where company_id = ? 
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
		$conds = ['user_id = ?'];
		$bind[] = $_SESSION['user_id'] ?? 0;

		$arguably = [ 
			static::getKey()
			, 'name'
		];
		foreach ($arguably as $arg)
		{
			if (!empty($args[$arg]))
			{
				$conds[] = "{$arg} = ?";
				$bind[] = $args[$arg];
			}
		}

		$bind[] = $_SESSION['user_id'];

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}
		$sql = 
			"WITH target as (
				select company_id, user_id
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
					join target using (company_id, user_id)
				where user_id = ?
				group by company_id 
			)
			select company_id
				, name
				, email
				, website
				, phone
				, fax
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

	public static function getByName(string $name): ?Company
	{
		$bind = [];
		$sql = static::getSelect(['name' => $name], $bind);
		$result = DB::fetchOne($sql, $bind);
		if (!empty($result))
		{
			return new Company($result);
		}
		return null;
	}
}
