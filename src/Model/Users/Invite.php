<?php
namespace Jeff\Code\Model\Users;

use Jeff\Code\Model\Record;
use Jeff\Code\Util\Config;

class Invite extends Record
{
	protected function onSave(): bool
	{
		if ($this->update_data)
		{
			if (!static::validate($this->data))
			{
				return false;
			}

			$this->bind = [
				$this->data['first_name'] ?? null,
				$this->data['last_name'] ?? null,
				$this->data['email'] ?? null,
			];

			if (empty($this->data['invite_id']))
			{
				$this->sql =
					"INSERT into invites(
						first_name
						, last_name
						, email
					) 
					values (?, ?, ?)
					returning *";
			}
			else
			{
				// do something else
			}
		}
		return false;
	}

	protected static function getKey(): string
	{
		return 'invite_id';
	}

	protected static function validate(array $data): bool
	{
		return true;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string
	{
		return '';
	}

	public static function getSelect(array $args=[], array &$bind=[]): string
	{
		$bind = [Config::get('INVITE_INTERVAL'), $args['key'] ?? $args['k'] ?? null];

		return 'SELECT *
			from invites
			where user_id is null
				and now() - ?::interval < created_date
				and key = ?';
	}

	public static function getInstance(array $data): ?Record
	{
		return new Invite($data);
	}
}