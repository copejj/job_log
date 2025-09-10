<?php
namespace Jeff\Code\Controller;

use Jeff\Code\Util\D;
use Jeff\Code\View\Display\Printable;
use Jeff\Code\View\Elements\Link;

class Links implements Printable
{
	protected string $page = '';

	public function __construct(string $page='')
	{
		$this->page = $page;
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
				new Home(),
				new Logs(),
				new Weeks(),
				new Companies(),
				new Users(),
				new Logout(),
			];
		}

		$refs = [];
		$current_page = $this->page ?? '';
		foreach ($links as $link)
		{
			$page = $link->page ?? '';
			$label = $link->label ?? '';
			$selected = ($current_page == $page) ? ' link-selected' : '';

			$ref = (empty($page)) ? '' : "?page={$page}";
			$refs[] = "<div class='link{$selected}'><a href='/{$ref}'>{$label}</a></div>";
		}
		return implode($refs);
	}
}

class Companies extends Link
{
	protected array $data = [
		'page' => 'company',
		'label' => 'Company',
	];
}

class Home extends Link
{
	protected array $data = [
		'page' => '',
		'label' => 'Home',
	];
}

class Logs extends Link
{
	protected array $data = [
		'page' => 'log',
		'label' => 'Log',
	];
}

class Weeks extends Link
{
	protected array $data = [
		'page' => 'workweek',
		'label' => 'Weeks',
	];
}

class Users extends Link
{
	protected array $data = [
		'page' => 'user',
		'label' => 'User',
	];
}

class Login extends Link
{
	protected array $data = [
		'page' => 'login',
		'label' => 'Login',
	];
}

class Logout extends Link
{
	protected array $data = [
		'page' => 'logout',
		'label' => 'Logout',
	];
}
