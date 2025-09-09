<?php
namespace Jeff\Code\Controller\Users;

use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;

class Login extends HeaderedContent
{
	protected Labels $labels;

	public function getTitle(): string
	{
		return "Login";
	}

	public function processing():void
	{
		$this->labels = new Labels();
	}

	public function content(): void
	{
		echo new Form([
			new Inputs([ 
				new Input('user_name', 'text', $data['user_name'] ?? '', '', $this->labels->user_name),
				new Input('password', 'password', $data['password'] ?? '', '', $this->labels->password),
			]),
			new Input('login_user', 'submit', 'Login'),
		]);
	}
}
