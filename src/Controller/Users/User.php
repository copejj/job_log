<?php
namespace Jeff\Code\Controller\Users;

use Exception;

use Jeff\Code\Model\Users\User as Service;
use Jeff\Code\Util\D;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;

class User extends HeaderedContent
{
	protected Service $user;

	public function processing(): void
	{
		$this->user = Service::load($_SESSION['user_id']);
		$post = $this->post;
		$message = '';
		if (!empty($post))
		{
			try
			{
				if (!empty($post['save_user']))
				{
					$this->user->first_name = $this->post['first_name'];
					$this->user->last_name = $this->post['last_name'];
					$this->user->email = $this->post['email'];
					$this->user->username = $this->post['username'];
					$this->user->password_hash = [$this->post['password'], $this->post['confirm_password']];
					if ($this->user->save())
					{
						$message = "This user updated successfully";
						$this->acted = true;
					}
				}

				if ($this->acted)
				{
					$this->user = Service::load($_SESSION['user_id']);
					$_SESSION = $this->user->toArray();
				}
			}
			catch (Exception $e)
			{
				\Jeff\Code\Util\D::p('exception', $e);
				$message = $e->getMessage();
			}

			$this->message = $message;
		}
	}

	public function getTitle(): string
	{
		return "User";
	}

	public function content(): void
	{
		$data = $this->user->data ?? $this->post;

		echo new Form([
			new Inputs([ 
				new Input('first_name', 'text', $data['first_name'] ?? '', '', $this->labels->first_name),
				new Input('last_name', 'text', $data['last_name'] ?? '', '', $this->labels->last_name),
				new Input('email', 'text', $data['email'] ?? '', '', $this->labels->user_email),
				new Input('username', 'text', $data['username'] ?? '', '', $this->labels->username),
				new Input('password', 'password', $data['password'] ?? '', '', $this->labels->password),
				new Input('confirm_password', 'password', $data['confirm_password'] ?? '', '', $this->labels->confirm_password),
			]),
			new Input('save_user', 'submit', 'Save'),
		], 'post', new Attributes(['id' => 'user_form']));
	}
}
