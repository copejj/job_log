<?php
namespace Jeff\Code\Model\Users;

use Exception;
use Jeff\Code\Model\Record;
use Jeff\Code\Util\D;

class User extends Record
{
	protected function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			if (!empty($this->data['password']))
			{
				$this->data['password_hash'] = password_hash($this->data['password'], PASSWORD_ARGON2I);
			}

			$this->bind = [
				$this->data['first_name'] ?? '',
				$this->data['last_name'] ?? '',
				$this->data['email'] ?? '',
				$this->data['username'],
				$this->data['password_hash'], 
			];

			if (empty($this->data['user_id']))
			{
				$this->sql = 
					"INSERT INTO users (
						first_name
						, last_name
						, email
						, username
						, password_hash
					)
					VALUES (?, ?, ?, ?, ?)
					returning *";
			}
			else
			{
				$this->sql = 
					"UPDATE users 
					set first_name = ?
						, last_name = ?
						, email = ?
						, username = ?
						, password_hash = ?
					where user_id = ?
					returning *";
				$this->bind[] = $this->data['user_id'];
			}
		}
		return true;
	}

	protected static function getKey(): string
	{
		return 'user_id';
	}

	protected static function validate(array $data): bool
	{
		switch (true)
		{
			case empty($data['username']):
				return false;
		}
		return true;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string
	{
		$bind[] = $args['user_id'] ?? 0;
		return '';
	}

	public static function getSelect(array $args=[], array &$bind=[]): string
	{
		$conds = [];
		$conds[] = 'inactive_date is null';

		$arguably = [static::getKey(), 'username'];
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

		return 
			"SELECT *, '' as password, '' as confirm_password
			from users {$sql_cond}";
	}

	public static function getInstance(array $data): User
	{
		return new User($data);
	}

	public function toArray(): array
	{
		$user_data = [];
		foreach (['user_id', 'first_name', 'last_name', 'email'] as $key)
		{
			$user_data[$key] = $this->data[$key];
		}
		return $user_data;
	} 

	public function __set(string $name, mixed $value): void
	{
		$set_value = $value;
		if ($name === 'password_hash')
		{
			$password = trim($value[0] ?? '');
			$confirm = trim($value[1] ?? '');
			if (empty($password) && empty($confirm)) 
			{
				// if either of them are empty, just overwrite with the existing password hash
				$set_value = $this->data['password_hash'];
			}
			else 
			{
				if ($password === $confirm)
				{
					$set_value = password_hash($password, PASSWORD_ARGON2I);
				}
				else
				{
					throw new Exception("Passwords don't match");
				}
			}
		}
		parent::__set($name, $set_value);
	}
}