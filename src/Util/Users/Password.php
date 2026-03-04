<?php
namespace Jeff\Code\Util\Users;

class Password
{
	public static function hash(string $password): string
	{
		return password_hash($password, PASSWORD_ARGON2I);
	}
}
