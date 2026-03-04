<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jeff\Code\Util\Config;
use Jeff\Code\Util\DB;
use Jeff\Code\Util\Users\Password;

if (Config::get('ENVIRONMENT') !== 'prod')
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$args = getopt('', ['userid:', 'password:']);
$bind = [];
if (empty($args['userid']))
{
	echo "No user id provided".PHP_EOL;
	$sql = "SELECT user_id, username, first_name, last_name FROM users";
	$users = DB::fetchAll($sql, $bind);
	print_r($users);
}
else
{
	echo "found user id: {$args['userid']}". PHP_EOL;
	$sql = "SELECT user_id, username, first_name, last_name FROM users WHERE user_id = ?";
	$bind[] = $args['userid'];
	$users = DB::fetchAll($sql, $bind);
	if (empty($users))
	{
		echo "No user found with id: {$args['userid']}".PHP_EOL;
	}
	else
	{
		print_r($users);
		if (!empty($args['password']))
		{
			echo "Updating password for user id: {$args['userid']}".PHP_EOL;
			$sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
			$bind = [Password::hash($args['password']), $args['userid']];
			$result = DB::perform($sql, $bind);
			echo "Password updated successfully".PHP_EOL;
		}
	}
}

