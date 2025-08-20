<?php
namespace Jeff\Code\Model\Company\Address;

use Jeff\Code\Model\Address\Address;

use Jeff\Code\Util\DB;

class CompanyAddress extends Address
{
	protected static function getKey(): string
	{
		return 'company_address_id';
	}

	protected function onSave(): bool
	{
		if ($this->update_data)
		{
			if (empty($this->data['address_id']))
			{
				$address = Address::create($this->data);
				$this->data['address_id'] = $address->address_id;
			}
			else
			{
				$address = Address::load($this->data['address_id']);
				$address->street = $this->data['street'];
				$address->street_ext = $this->data['street_ext'];
				$address->city = $this->data['city'];
				$address->state_id = $this->data['state_id'];
				$address->zip = $this->data['zip'];
				$address->save();
			}


			if (!static::validate($this->data))
			{
				return false;
			}

			$this->bind = [
				$this->data['company_id'],
				$this->data['address_id'],
				$this->data['address_type_id'],
			];

			if (empty($this->data['company_address_id']))
			{
				$this->sql = 
					"INSERT into company_addresses (
						company_id
						, address_id
						, address_type_id
					) 
					values (?, ?, ?) 
					returning *";
			}
			else 
			{
				$this->sql = 
					"UPDATE company_addresses 
					set company_id = ?
						, address_id = ?
						, address_type_id = ?
					where company_address_id = ?
					returning *";
				$this->bind[] = $this->data['company_address_id'];
			}
		}
		return true;
	}

	protected static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['company_id']):
			case empty($data['address_type_id']):
				return false;
		}
		return true;
	}

	public static function getInstance(array $data): CompanyAddress
	{
		return new CompanyAddress($data);
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
		$sql = parent::getDelete($args, $bind = []);
		DB::getInstance()->perform($sql, $bind);

		$key = static::getKey();
		$bind = [$args[$key]];
		return "DELETE from company_addresses where {$key} = ?";
	}
}
