<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Week\WorkWeeks as Service;
use Jeff\Code\Template\HeaderedContent;

class WorkWeeks extends HeaderedContent
{
	protected Service $workweek;

	public function processing(): void
	{
		$this->workweek = Service::init();
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