<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Week\Weeks as Service;
use Jeff\Code\Template\HeaderedContent;

class Weeks extends HeaderedContent
{
	protected Service $week;

	public function processing(): void
	{
		$this->week = Service::init();
	}

	public function getTitle(): string
	{
		return "Work Weeks";
	}

	public function content(): void
	{
		?>
		<?php
	}
}