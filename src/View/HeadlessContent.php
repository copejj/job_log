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
		<dialog id="myDialog">
			<h2>Dialog Title</h2>
			<p>This is a native dialog.</p>
			<button id="closeDialog">Close</button>
		</dialog>
		<button id="openDialog">Open Dialog</button>
		<script src="js/main.js"></script>
		<?php
		if (Config::get('ENVIRONMENT') !== 'prod' && !empty($_GET['debug']))
		{
			D::p('GET', $this->get);
			D::p('POST', $this->post);
		}
	}
}