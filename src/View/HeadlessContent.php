<?php
namespace Jeff\Code\View;

use Jeff\Code\Util\Config;
use Jeff\Code\Util\D;

abstract class HeadlessContent extends Content
{
	protected function top(): void
	{
		?>
		<div class='content-cont'>
		<?php
	}

	protected abstract function getTitle(): string;
	protected function title(): void
	{
		?>
		<div class='sub-header-cont'><?=$this->getTitle()?></div>
		<?php
	}

	protected function messages(): void
	{
		$message = $this->message ?? '';
		if (!empty($message))
		{
			?>
			<div class='message-cont'><?=$message?></div>
			<?php
		}
	}

	protected function content(): void
	{
		?>
		<p>This page is under construction, please be patient.<i class="fa-solid fa-person-digging"></i></p>
		<?php
	}

	protected function bottom(): void
	{
		?>
		</div>
		<?php
		if (Config::get('ENVIRONMENT') !== 'prod' && !empty($_GET['debug']))
		{
			D::p('GET', $this->get);
			D::p('POST', $this->post);
			D::p('SERVER', $_SERVER);
			D::p('SESSION', $_SESSION);
		}
	}
}