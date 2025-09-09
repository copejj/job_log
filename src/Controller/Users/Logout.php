<?php
namespace Jeff\Code\Controller\Users;

use Jeff\Code\View\HeaderedContent;

class Logout extends HeaderedContent
{
	public function getTitle(): string
	{
		return "Logout";
	}
}
