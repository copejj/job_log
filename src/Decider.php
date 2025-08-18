<?php
namespace Jeff\Code;

use Jeff\Code\View\Content;
use Jeff\Code\Controller\Index;
use Jeff\Code\Controller\Company\Companies;
use Jeff\Code\Controller\Company\CompanyAdd;
use Jeff\Code\Controller\Company\CompanyEdit;
use Jeff\Code\Controller\Log\Logs;
use Jeff\Code\Controller\Log\LogAdd;
use Jeff\Code\Controller\Log\LogEdit;
use Jeff\Code\Controller\Week\Weeks;
use Jeff\Code\Controller\Week\WeekAdd;
use Jeff\Code\Controller\Week\WeekEdit;

class Decider
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
					case 'add':
						$content = new WeekAdd();
						break;
					case 'edit':
						$content = new WeekEdit();
						break;
					default:
						$content = new Weeks();
				}
				break;
			case 'log':
				switch ($action)
				{
					case 'add':
						$content = new LogAdd();
						break;
					case 'edit':
						$content = new LogEdit();
						break;
					default:
						$content = new Logs();
				}
				break;
			case 'company':
				switch ($action)
				{
					case 'add':
						$content = new CompanyAdd();
						break;
					case 'edit':
						$content = new CompanyEdit();
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
			'Weeks' => '/?page=workweek',
			'Logs' => '/?page=log',
			'Company' => '/?page=company',
		];
		return $content;
	}
}