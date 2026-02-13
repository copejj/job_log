<?php
namespace Jeff\Code\Util\Info;

use Jeff\Code\Util\Config;

class GitInfo
{
	private $branch;
	private $hash;

	public function __construct()
	{
		switch (Config::get('ENVIRONMENT'))
		{
			case 'prod':
				$this->branch = trim(shell_exec('git describe --tags --exact-match 2>/dev/null') ?? '');
				$this->hash = trim(shell_exec('git log -1 --pretty=format:"%h"'));
				break;
			case 'dev':
			default:
				$this->branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>&1'));
				$this->hash = trim(shell_exec('git log -1 --pretty=format:"%h"'));
		}
	}

	public function __get($name)
	{
		return $this->$name;
	}
}