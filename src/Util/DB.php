<?php
namespace Jeff\Code\Util;

use Jeff\Code\Util\Config;

use Atlas\Pdo\Connection;
use Atlas\Pdo\ConnectionLocator;

class DB
{
	static protected $instance;

	static public function getInstance(bool $readonly = false, string $nameKey = 'DB_NAME') : Connection
	{
		[$db_server, $db_user, $db_pass, $db_name] = Config::list(
			'DB_HOST',
			'DB_USER',
			'DB_PASS',
			$nameKey
		);

		$driver_options = [
			\PDO::ATTR_PERSISTENT => true
		];

		$dsn = "pgsql:host={$db_server};port=5432;dbname={$db_name}";

		$connectionLocator = ConnectionLocator::new($dsn, $db_user, $db_pass, $driver_options);

		$connectionLocator->setWriteFactory('master', Connection::factory(
			"pgsql:host={$db_server};port=5432;dbname={$db_name}",
			$db_user, $db_pass, $driver_options
		));

		try {
			$ro_db_server = Config::get('DB_HOST_RO');

			if (!empty($ro_db_server))
			{
				$connectionLocator->setReadFactory('master', Connection::factory(
					"pgsql:host={$ro_db_server};port=5432;dbname={$db_name}",
					$db_user, $db_pass, $driver_options
				));

				if ($readonly)
				{
					return $connectionLocator->getRead();
				}
			}
		} catch (\Throwable $th) {
			//throw $th;
		}

		return $connectionLocator->getWrite();
	}

	static public function __callStatic(string $func, array $args)
	{
		return static::getInstance()->$func(...$args);
	}

	public function __call(string $func, array $args)
	{
		return static::getInstance()->$func(...$args);
	}
}
