<?php
namespace Jeff\Code\Controller\Users;

use Jeff\Code\Model\Users\Invite as Service;
use Jeff\Code\Util\Config;
use Jeff\Code\Util\D;
use Jeff\Code\View\Display\Attributes;
use Jeff\Code\View\Elements\Form;
use Jeff\Code\View\Elements\Input;
use Jeff\Code\View\Elements\Inputs;
use Jeff\Code\View\HeaderedContent;

class Invite extends HeaderedContent
{
	protected Service $invite;

	public function processing(): void
	{
		if (!empty($this->get['k']))
		{
			$this->invite = Service::get($this->get);
			if (empty($this->invite))
			{
				$this->message = "This invite has either timed out, been used, expired or is invalid.";
			}
			else 
			{
				D::p('found the user');
			}
		}
		else if (!empty($this->post))
		{
			if (!$this->perms->hasAccess('invites'))
			{
				$this->has_redirect = '/';
			}

			$this->invite = Service::getInstance($this->post);
			$this->invite->save(true);
			$this->message = "Invite created successfully";
		}
		D::p('invite', $this->invite ?? null);
	}

	public function getTitle(): string
	{
		return "Invite User";
	}

	public function content(): void
	{
		$data = $this->invite->data ?? $this->post;

		if (empty($data['key']))
		{
			echo new Form([
				new Inputs([ 
					new Input('first_name', 'text', $data['first_name'] ?? '', '', $this->labels->first_name),
					new Input('last_name', 'text', $data['last_name'] ?? '', '', $this->labels->last_name),
					new Input('email', 'text', $data['email'] ?? '', '', $this->labels->user_email),
				]),
				new Input('save_invite', 'submit', 'Save'),
			], 'post', new Attributes(['id' => 'invite_form']));
		}
		else
		{
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
			$host = $_SERVER['HTTP_HOST'];
			$url = $protocol . $host;
			?>
			<p>Hi <?=$data['first_name']?>,</p>
			<p><?=ucwords($_SESSION['first_name'] . ' ' . $_SESSION['last_name'])?> wants to invite you to try <a href='<?=$url?>'>Brain Dribbler (Work Activity Log)</a></p>
			<p><?=ucwords($_SESSION['first_name'])?> has provided you with a temporary link that will be available for <?=Config::get('INVITE_INTERVAL')?>.  
				Just click on the following link to create a user account: <a href='<?="{$url}/?page={$this->get['page']}&k={$data['key']}"?>'>I'm ready to be THAT cool!</a></p>
			<p>Thanks,<br>The Dribbling Team</p>
			
			<?php
		}
	}
}