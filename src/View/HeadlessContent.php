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

	protected function messages(): void
	{
		?>
		<div class='message-cont'><?=$this->message?></div>
		<?php
	}

	protected abstract function getTitle(): string;
	protected function title(): void
	{
		?>
		<h2><?=$this->getTitle()?></h2>
		<?php
	}


	protected function bottom(): void
	{
		?>
		</div>
		<?php
		if (Config::get('ENVIRONMENT') !== 'prod' && !empty($_GET['debug']))
		{
			D::p('GET', $_GET);
			D::p('POST', $_POST);
		}
	}
}