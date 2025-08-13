<?php
namespace Jeff\Code;

use Jeff\Code\Template\Content;
use Jeff\Code\Page\Index;
use Jeff\Code\Page\Logs;

class Decider
{
	public static function getContent(array $get, array $post): Content
	{
		$page = $get['page'] ?? 'index';
		$content = null;
		switch ($page)
		{
			case 'log':
				$content = new Logs;
				break;
			case 'index':
			default:
				$content = new Index();
		}

		$content->setArgs($get, $post);
		return $content;
	}
}