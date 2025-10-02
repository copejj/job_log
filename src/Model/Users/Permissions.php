<?php
namespace Jeff\Code\Model\Users;

class Permissions
{
	private array $permissions = [];

	private bool $update_data = true;

	protected function init(): void
	{
		if ($this->update_data)
		{
			$this->update_data = false;
		}
	}

	public function __get(string $key): ?bool
	{
		if (isset($this->permissions[$key]))
		{
			return ($this->permissions[$key] === true);
		}
		return null;
	}

	public function __isset($key)
	{
		return ($this->__get($key) !== null);
	}

	public function hasAccess(string $key): bool 
	{
		if (!empty($_SESSION['is_admin']))
		{
			return true;
		}

		if (!empty($this->$key))
		{
			return true;
		}

		return false;
	}
}