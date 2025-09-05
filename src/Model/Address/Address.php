<?php
namespace Jeff\Code\Model\Address;

use Jeff\Code\Model\Record;

class Address extends Record
{
	protected static function getKey(): string
	{
		return 'address_id';
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
				$this->data['street'],
				$this->data['street_ext'],
				$this->data['city'],
				(int) $this->data['state_id'],
				$this->data['zip'],
			];

			if (empty($this->data['address_id']))
			{
				$this->sql = 
					"INSERT into addresses (
						street
						, street_ext
						, city
						, state_id
						, zip
					)
					values (?, ?, ?, ?, ?)
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE addresses
					set street = ?
						, street_ext = ?
						, city = ?
						, state_id = ?
						, zip = ? 
					where address_id = ?
					returning *";
				$this->bind[] = $this->data['address_id'];
			}
		}
		return true;
	}

	protected static function validate(array $data): bool
	{
		return true;
	}

	public static function getInstance(array $data): Address
	{
		return new Address($data);
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

		$sql_cond = '';
		if (!empty($conds))
		{
			$sql_cond = 'where ' . implode(' and ', $conds);
		}
		$sql = 
			"SELECT *
			from company_addresses
				join addresses using (address_id)
				left join states using (state_id) {$sql_cond}";
		return $sql;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string 
	{
		$bind = [];
		$key = static::getKey();
		$bind[] = $args[$key];
		return "DELETE from addresses where {$key} = ?";
	}
}
