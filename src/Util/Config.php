<?php
namespace Jeff\Code\Util;

use RuntimeException;

class Config
{
	static protected $env;
	static protected $place = [];
	static protected $target = [];

	private const FILENAME = '../.env.php';

	static private function init()
	{
		if (empty(self::$env))
		{
			// The config_setting section needs env values for the database 
			// connection so we need to set the env variables immediately
			// before retrieving the config_settings values
			$file = require static::FILENAME;
			self::$env = $file;

			$settings = self::getSettings();
			self::$env = array_merge($settings, self::$env);

			self::setPlacement($settings, 'db');
			self::setPlacement($file, 'file');
		}
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
		$sql = 
			"SELECT distinct on (name) name, value, environment
			from public.config
			where inactive_date is null
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
		static::init();
		if (! array_key_exists($key, self::$env)) {
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
