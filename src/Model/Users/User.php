<?php
namespace Jeff\Code\Model\Users;

use Jeff\Code\Model\Record;

class User extends Record
{
	protected function onSave(): bool
	{
		return false;
	}

	protected static function getKey(): string
	{
		return 'user_id';
	}

	protected static function validate(array $data): bool
	{
		return false;
	}

	public static function getDelete(array $args=[], array &$bind=[]): string
	{
		return '';
	}

	public static function getSelect(array $args=[], array &$bind=[]): string
	{
		return '';
	}

	public static function getInstance(array $data): ?User
	{
		return null;
	}
}