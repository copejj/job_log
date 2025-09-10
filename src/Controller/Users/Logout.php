<?php
namespace Jeff\Code\Controller\Users;

use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\HeaderedContent;

class Logout extends HeaderedContent
{
	public function getTitle(): string
	{
		return "Logout User?";
	}

	public function processing(): void
	{
		if (!empty($this->post['logout_user']))
		{
			// destroy the session
			session_destroy();
			$this->has_redirect = '/';
		}
	}

	public function content(): void
	{
		if (empty($_SESSION['user_id']))
		{

		}
		else
		{
			echo new Form([
				new Input('logout_user', 'submit', 'Logout'),
			]);
		}
	}
}
