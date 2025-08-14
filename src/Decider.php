<?php
namespace Jeff\Code;

use Jeff\Code\Template\Content;
use Jeff\Code\Page\Index;
use Jeff\Code\Page\Log\Logs;
use Jeff\Code\Page\Week\WorkWeeks;

class Decider
{
	public static function getContent(array $get, array $post): Content
	{
		$page = $get['page'] ?? 'index';
		$content = null;
		switch ($page)
		{
			case 'workweek':
				$content = new WorkWeeks();
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