<?php
namespace Jeff\Code;

use RuntimeException;

class Config
{
	static protected $env;
	static protected $place = [];
	static protected $target = [];

	static public function init(array $env)
	{
		// The config_setting section needs env values for the database 
		// connection so we need to set the env variables immediately
		// before retrieving the config_settings values
		self::$env = $env;
		$settings = self::getSettings();
		self::$env = array_merge($settings, self::$env);

		self::setPlacement($settings, 'db');
		self::setPlacement($env, 'file');
	}

	static private function setPlacement(array $target, string $placement)
	{
		if (!empty($target))
		{
			foreach ($target as $key => $val)
			{
				self::$place[$key] = $placement;
			}
		}
	}

	static private function getSettings() : array
	{
		$env = [];
		$sql = "
			select distinct on (name) name, value, environment
			from config
			where inactive = 0
				and trim(upper(environment)) in ('ANY', ?)
			order by name, environment desc";
		$settings = DB::fetchAll($sql, [trim(strtoupper(self::$env['ENVIRONMENT']))]);
		if (!empty($settings))
		{
			foreach ($settings as $setting)
			{
				if (!empty(trim($setting['name'])))
				{
					$env[$setting['name']] = $setting['value'];
					self::$target[$setting['name']] = $setting['environment'];
				}
			}
		}
		return $env ?? [];
    }

	static public function get(string $key)
	{
		self::assertKey($key);
		return self::$env[$key];
	}

	static public function getAll()
	{
		return self::$env;
	}

	static public function getAllData()
	{
		return ['env' => self::$env, 'placement' => self::$place, 'target' => self::$target];
	}

	static public function list(...$keys) : array
	{
		$list = [];
		foreach ($keys as $key) {
			$list[] = self::get($key);
		}
		return $list;
	}

	static protected function assertKey(string $key) : void
	{
		if (! array_key_exists($key, self::$env)) {
			throw new RuntimeException("No such config key: {$key}");
		}
	}

	static public function googleClientAuth() : array
	{
		return [
			'web' => [
				'client_id' => self::get('GOOGLE_CLIENT_ID'),
				'project_id' => self::get('GOOGLE_PROJECT_ID'),
				'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
				'token_uri' => 'https://oauth2.googleapis.com/token',
				'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
				'client_secret' => self::get('GOOGLE_CLIENT_SECRET'),
				'redirect_uris' => [self::get('SERVER_URL') . '/index.php'],
				'javascript_origins' => [self::get('SERVER_URL')],
			],
		];
	}

	static public function allowedIps() : array
	{
		$allowed = [
			"127.0.0.1",
			"24.119.144.2",
			"54.70.58.54",
		];
		if (!empty($_SERVER['SERVER_ADDR']))
		{
			$allowed[] = $_SERVER['SERVER_ADDR'];
		}
		return $allowed;
	}

	static public function emailPassword(string $addr) : string
	{
		return self::get('EMAIL_PASSWORDS')[$addr];
	}
}
