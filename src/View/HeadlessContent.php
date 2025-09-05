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
		?>
		<div class='message-cont'><?=$this->message?></div>
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
		}
	}
}