<?php
namespace Jeff\Code\Controller\Users;

use Exception;
use Jeff\Code\Model\Meta\Labels;
use Jeff\Code\Model\Users\Invite;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;
use Jeff\Code\Model\Users\User;
use Jeff\Code\Util\Config;
use Jeff\Code\Util\D;

class Login extends HeaderedContent
{
	protected Labels $labels;
	protected User $user;
	protected bool $new_enabled = false;

	public function getTitle(): string
	{
		return "Login";
	}

	public function processing():void
	{
		$this->labels = new Labels();
		if (!empty(trim(Config::get('NEW_USER_ENABLED'))))
		{
			$this->new_enabled = true;
		}

		if (!empty($this->get['k']))
		{
			$invite = Invite::get(['key' => $this->get['k']]);
			if (!empty($invite))
			{
				D::p('invite', $invite);
				$user_data = [
					'create_user' => 1,
					'first_name' => $invite->first_name,
					'last_name' => $invite->last_name,
					'email' => $invite->email,
				];
				$this->user = User::getInstance($user_data);
				$this->new_enabled = true;
			}
		}

		$post = $this->post;
		$message = '';
		if (!empty($post))
		{
			if (empty($post['create_user']))
			{
				// login existing user
				$user = User::get($post);
				$password = $post['password'] ?? '';
				if (empty($password))
				{
					$message = "Please login to continue";
				}
				else if (empty($user) || !password_verify($password, $user->password_hash))
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
			else if (!empty($this->new_enabled))
			{
				//create a new user
				if (!empty($post['new_user']))
				{
					try
					{
						$user = User::create($post);
						$user_id = $user->user_id ?? 0;
						if (!empty($user_id))
						{
							$message = "User created successfully";
							$this->user = $user;
							$this->acted = true;
							$this->has_redirect = '/?page=log';
							$_SESSION = $this->user->toArray();

							if (!empty($invite))
							{
								$invite->user_id = $user_id;
								$invite->save();
							}
						}
					}
					catch (Exception $e)
					{
						$message = $e->getMessage();
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
			$inputs = [
				new Inputs([ 
					new Input('username', 'text', $data['username'] ?? '', '', $this->labels->username),
					new Input('password', 'password', $data['password'] ?? '', '', $this->labels->password),
				]),
				new Input('login_user', 'submit', 'Login')
			];

			if ($this->new_enabled)
			{
				$inputs[] = new Input('create_user', 'submit', 'New');

			}
			echo new Form($inputs, 'post', new Attributes(['id' => 'user_form']));
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
