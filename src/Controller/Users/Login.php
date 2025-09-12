<?php
namespace Jeff\Code\Controller\Users;

use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\Util\D;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;
use Jeff\Code\Model\Users\User;

class Login extends HeaderedContent
{
	protected Labels $labels;
	protected User $user;

	public function getTitle(): string
	{
		return "Login";
	}

	public function processing():void
	{
		$this->labels = new Labels();

		$post = $this->post;
		$message = '';
		if (!empty($post))
		{
			if (empty($post['create_user']))
			{
				// login existing user
				$user = User::get($post);
				if (empty($user) || !password_verify($post['password'], $user->password_hash))
				{
					$message = "Login failed";
				}
				else
				{
					$message = 'Login successful';
					$this->user = $user;
					$this->acted = true;
					$this->has_redirect = '/?page=log';
					$_SESSION = $this->user->toArray();
				}
			}
			else
			{
				if (empty($post['new_user']))
				{
					//edit an existing user
				}
				else
				{
					//create a new user
					$user = User::create($post);
					if (!empty($user->user_id))
					{
						$message = "User created successfully";
						$this->user = $user;
						$this->acted = true;
						$this->has_redirect = '/?page=log';
						$_SESSION = $this->user->toArray();
					}
				}
			}
		}
		$this->message = $message;
	}

	public function content(): void
	{
		$data = $this->user->data ?? $this->post;
		?>
		<script>
			function create_form()
			{
				$('#user_form').append("<?=new Input('action', 'hidden', 'new_user')?>");
				return true;
			}
		</script>
		<?php
		if (empty($data['create_user']))
		{
			echo new Form([
				new Inputs([ 
					new Input('username', 'text', $data['username'] ?? '', '', $this->labels->username),
					new Input('password', 'password', $data['password'] ?? '', '', $this->labels->password),
				]),
				new Input('login_user', 'submit', 'Login'),
				new Input('create_user', 'submit', 'New'),
			], 'post', new Attributes(['id' => 'user_form']));
		}
		else
		{
			echo new Form([
				new Inputs([ 
					new Input('new_user', 'hidden', 'new_user'),
					new Input('first_name', 'text', $data['first_name'] ?? '', '', $this->labels->first_name),
					new Input('last_name', 'text', $data['last_name'] ?? '', '', $this->labels->last_name),
					new Input('email', 'text', $data['email'] ?? '', '', $this->labels->user_email),
					new Input('username', 'text', $data['username'] ?? '', '', $this->labels->username),
					new Input('password', 'password', $data['password'] ?? '', '', $this->labels->password),
					new Input('confirm_password', 'password', $data['confirm_password'] ?? '', '', $this->labels->confirm_password),
				]),
				new Input('create_user', 'submit', 'Save'),
				new Input('cancel_user', 'submit', ($this->acted) ? 'Done' : 'Cancel'),
			], 'post', new Attributes(['id' => 'user_form']));
		}
	}
}
