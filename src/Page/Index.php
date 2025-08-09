<?php
namespace Jeff\Code\Page;

use Jeff\Code\Content;

class Index implements Content
{
	public static function display(): void
	{
		echo "Hello, World.";
	}
}