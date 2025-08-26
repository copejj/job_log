<?php
namespace Jeff\Code;

use Jeff\Code\View\Content;
use Jeff\Code\Controller\Index;
use Jeff\Code\Controller\Company\Company;
use Jeff\Code\Controller\Company\Companies;
use Jeff\Code\Controller\Log\Log;
use Jeff\Code\Controller\Log\LogDetails;
use Jeff\Code\Controller\Log\Logs;
use Jeff\Code\Controller\Week\Week;
use Jeff\Code\Controller\Week\Weeks;

class Driver
{
	protected ?Content $content;
	protected array $get;
	protected array $post;

	public function __construct(array $get, array $post)
	{
		$this->get = $get;
		$this->post= $post;
	}

	public function getContent(): Content
	{
		$page = $this->get['page'] ?? 'index';
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
			case 'index':
			default:
				$this->content = new Index();
		}

		$this->content->get = $this->get;
		$this->content->post = $this->post;
		$this->content->links = [
			'Home' => '/',
			'Logs' => '/?page=log',
			'Weeks' => '/?page=workweek',
			'Company' => '/?page=company',
		];
		return $this->content;
	}
}