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

	protected bool $acted = false;

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
				D::p('POOST', $post);
				$user = User::get($post);
				D::p('login user', $user);
				if (empty($user))
				{
					$message = "Login failed".__LINE__;
				}
				else
				{
					D::p('password', [$post['password'], $user->password_hash, password_verify($post['password'], $user->password_hash)]);
					if (password_verify($post['password'], $user->password_hash))
					{
						$message = 'Login successful';
						$this->user = $user;
						$_SESSION['user_id'] = $this->user->user_id;
					}
					else
					{
						$message = 'Login Failed'.__LINE__;
					}
				}
				D::p('message: ' . $message);
			}
			else
			{
				D::p('create user', $post);
				if (empty($post['new_user']))
				{
					//edit an existing user
				}
				else
				{
					//create a new user
					$this->user = User::create($post);
					if (!empty($this->user->user_id))
					{
						$message = "User created successfully";
						$this->acted = true;
						$_SESSION['user_id'] = $this->user->user_id;
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
