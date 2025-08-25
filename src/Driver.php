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
	public static function getContent(array $get, array $post): Content
	{
		$page = $get['page'] ?? 'index';
		$action = $post['action'] ?? $get['action'] ?? '';
		$content = null;
		switch ($page)
		{
			case 'workweek':
				switch ($action)
				{
					case 'view':
						$content = new Logs();
						break;
					case 'add':
					case 'edit':
						$content = new Week();
						break;
					default:
						$content = new Weeks();
				}
				break;
			case 'log':
				switch ($action)
				{
					case 'details':
						$content = new LogDetails();
						break;
					case 'add':
					case 'edit':
						$content = new Log();
						break;
					default:
						$content = new Logs();
				}
				break;
			case 'company':
				switch ($action)
				{
					case 'view':
						$content = new Logs();
						break;
					case 'add':
					case 'edit':
						$content = new Company();
						break;
					default:
						$content = new Companies();
				}
				break;
			case 'index':
			default:
				$content = new Index();
		}

		$content->get = $get;
		$content->post = $post;
		$content->links = [
			'Home' => '/',
			'Logs' => '/?page=log',
			'Weeks' => '/?page=workweek',
			'Company' => '/?page=company',
		];
		return $content;
	}
}