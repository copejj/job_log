<?php
namespace Jeff\Code\Controller;

use Jeff\Code\Model\Users\Permissions;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Display\Printable;
use Jeff\Code\View\Elements\Link;

class Links implements Printable
{
	protected string $page = '';

	private Permissions $perms;

	public function __construct(string $page='')
	{
		$this->page = $page;
		$this->perms = new Permissions();
	}

	public function __toString(): string
	{
		if (empty($_SESSION['user_id']))
		{
			$links = [
				new Home(),
				new Login(),
			];
		}
		else
		{
			$links = [
				new Logs(),
				new Weeks(),
				new Companies(),
				new Logout(),
				new Users(),
			];

			if ($this->perms->hasAccess('invites'))
			{
				$links[] = new Invite();
			}
		}

		$refs = [];
		$current_page = $this->page ?? '';
		foreach ($links as $link)
		{
			if ($current_page == $link->page)
			{
				$link->attr->add(['class' => 'link-selected']);
			}
			$refs[] = "{$link}";
		}
		return "<ul>" . implode($refs) . "</ul>";
	}
}

class Companies extends Link
{
	public function __construct()
	{
		parent::__construct('company', 'Company');
	}
}

class Home extends Link
{
	public function __construct()
	{
		parent::__construct('', 'Home');
	}
}

class Logs extends Link
{
	public function __construct()
	{
		parent::__construct('log', 'Log');
	}
}

class Weeks extends Link
{
	public function __construct()
	{
		parent::__construct('workweek', 'Weeks');
	}
}

class Users extends Link
{
	public function __construct()
	{
		parent::__construct('user', ucwords($_SESSION['first_name'] ?? 'User'), new Attributes(['class' => 'align-right']));
	}
}

class Login extends Link
{
	public function __construct()
	{
		parent::__construct('login', 'Login', new Attributes(['class' => 'align-right']));
	}
}

class Logout extends Link
{
	public function __construct()
	{
		parent::__construct('logout', 'Logout', new Attributes(['class' => 'align-right']));
	}
}

class Invite extends Link
{
	public function __construct()
	{
		parent::__construct('invite', 'Invite');
	}
}
