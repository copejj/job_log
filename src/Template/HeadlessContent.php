<?php
namespace Jeff\Code\Template;

use Jeff\Code\D;
use Jeff\Code\Config;

abstract class HeadlessContent extends Content
{
	protected function top(): void
	{
		?>
		<div class='content_cont'>
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
		if (Config::get('ENVIRONMENT') === 'dev' && !empty($_GET['debug']))
		{
			D::p('GET', $_GET);
			D::p('POST', $_POST);
		}
	}
}