<?php
namespace Jeff\Code;

use Jeff\Code\View\Content;
use Jeff\Code\Controller\Index;
use Jeff\Code\Controller\Log\Logs;
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
				$content = new Logs();
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
		];
		return $content;
	}
}