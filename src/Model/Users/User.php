<?php
namespace Jeff\Code\Model\Users;

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

			$this->data['password_hash'] = password_hash($this->data['password'], PASSWORD_ARGON2I);

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
						, user_name
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
						, user_name = ?
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
		D::p(__FUNCTION__, func_get_args());
		return 'user_id';
	}

	protected static function validate(array $data): bool
	{
		D::p(__FUNCTION__, $data);
		switch (true)
		{
			case empty($data['username']):
			case empty($data['password']):
			case !empty($data['confirm_password']) && $data['confirm_password'] !== $data['password']:
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
		D::p(__FUNCTION__, func_get_args());
		$bind[] = $args['username'];
		return 
			"SELECT *
			from users
			where user_name = ?
				and inactive_date is null";
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
}