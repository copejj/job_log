<?php
namespace Jeff\Code\Template;

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
	}
}