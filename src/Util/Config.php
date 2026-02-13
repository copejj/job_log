<?php
namespace Jeff\Code\Util;

use RuntimeException;

class Config
{
    static protected $env;
    static protected $place = [];
    static protected $target = [];

    static private function init()
    {
        if (empty(self::$env)) {
            // 1. Create the base environment from Server/PHP-FPM variables
            $initialEnv = [
                'ENVIRONMENT'       => getenv('ENVIRONMENT') ?: 'dev',
                'DB_HOST'           => getenv('DB_HOST') ?: '127.0.0.1',
                'DB_USER'           => getenv('DB_USER'),
                'DB_PASS'           => getenv('DB_PASS'),
                'DB_NAME'           => getenv('DB_NAME'),
                'DB_PORT'           => getenv('DB_PORT') ?: '5432', // Default Postgres port
                'JAVA_EXTERNAL_URL' => getenv('JAVA_EXTERNAL_URL') ?: "http://localhost:8080",
                'NEW_USER_ENABLED'  => filter_var(getenv('NEW_USER_ENABLED'), FILTER_VALIDATE_BOOLEAN),
                'IP_HOST_URL'       => 'ipinfo.io/ip',
            ];

            // Set the static env immediately so DB class can use it to connect
            self::$env = $initialEnv;
            self::setPlacement($initialEnv, 'server_env');

            // 2. Fetch the rest of the settings from the Database
            $settings = self::getSettings();
            
            // 3. Merge database settings over the server defaults
            self::$env = array_merge(self::$env, $settings);
            self::setPlacement($settings, 'db');
        }
    }

    static private function setPlacement(array $target, string $placement)
    {
        foreach ($target as $key => $val) {
            self::$place[$key] = $placement;
        }
    }

    static private function getSettings(): array
    {
        $env = [];
        // Note: Ensure your DB class uses Config::get('DB_HOST'), etc.
        $sql = "SELECT DISTINCT ON (name) name, value, environment
                FROM public.config
                WHERE inactive_date IS NULL
					AND trim(upper(environment)) IN ('ANY', ?)
                ORDER BY name, environment DESC";
        
        $settings = DB::fetchAll($sql, [trim(strtoupper(self::$env['ENVIRONMENT']))]);

        if (!empty($settings)) {
            foreach ($settings as $setting) {
                $name = trim($setting['name']);
                if (!empty($name)) {
                    $env[$name] = $setting['value'];
                    self::$target[$name] = $setting['environment'];
                }
            }
        }
        return $env;
    }

    static public function get(string $key)
    {
        static::init();
        if (!array_key_exists($key, self::$env)) {
            throw new RuntimeException("No such config key: {$key}");
        }
        return self::$env[$key];
    }


	static public function getAll()
	{
		static::init();
		return self::$env;
	}

	static public function getAllData()
	{
		static::init();
		return ['env' => self::$env, 'placement' => self::$place, 'target' => self::$target];
	}

	static public function list(...$keys) : array
	{
		static::init();
		$list = [];
		foreach ($keys as $key) {
			$list[] = self::get($key);
		}
		return $list;
	}
}
