<?php
namespace Jeff\Code;

use Jeff\Code\Controller\Index;
use Jeff\Code\Controller\Company\Company;
use Jeff\Code\Controller\Company\Companies;
use Jeff\Code\Controller\Users\Invite;
use Jeff\Code\Controller\Links;
use Jeff\Code\Controller\Log\Log;
use Jeff\Code\Controller\Log\LogDetails;
use Jeff\Code\Controller\Log\Logs;
use Jeff\Code\Controller\Users\User;
use Jeff\Code\Controller\Users\Login;
use Jeff\Code\Controller\Users\Logout;
use Jeff\Code\Controller\Week\Week;
use Jeff\Code\Controller\Week\Weeks;
use Jeff\Code\Model\Users\Permissions;
use Jeff\Code\View\Content;

class Driver
{
	protected ?Content $content;
	protected array $get;
	protected array $post;
	protected Permissions $perms;

	public function __construct(array $get, array $post)
	{
		$this->get = $get;
		$this->post = $post;
		$this->perms = new Permissions();
	}

	public function getContent(): Content
	{
		$page = '';
		if (!empty($this->get['page']))
		{
			if (empty($_SESSION['user_id']))
			{
				$page = 'login';
			}
			else
			{
				$page = $this->get['page'];
			}
		}

		$action = $this->post['action'] ?? $this->get['action'] ?? '';
		$this->content = null;
		switch ($page)
		{
			case 'workweek':
				switch ($action)
				{
					case 'view':
						$this->content = new Logs();
						break;
					case 'add':
					case 'edit':
						$this->content = new Week();
						break;
					default:
						$this->content = new Weeks();
				}
				break;
			case 'log':
				switch ($action)
				{
					case 'details':
						$this->content = new LogDetails();
						break;
					case 'add':
					case 'edit':
						$this->content = new Log();
						break;
					default:
						$this->content = new Logs();
				}
				break;
			case 'company':
				switch ($action)
				{
					case 'view':
						$this->content = new Logs();
						break;
					case 'add':
					case 'edit':
						$this->content = new Company();
						break;
					default:
						$this->content = new Companies();
				}
				break;
			case 'login':
				$this->content = new Login();
				break;
			case 'logout':
				$this->content = new Logout();
				break;
			case 'user':
				$this->content = new User();
				break;
			case 'invite':
				$this->content = new Invite();
				break;
			case '':
			default:
				$this->content = new Index();
		}

		$this->content->perms = $this->perms;
		$this->content->get = $this->get;
		$this->content->post = $this->post;
		$this->content->links = new Links($page ?? '');
		return $this->content;
	}
}