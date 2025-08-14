<?php
namespace Jeff\Code\Page\Log;

use Jeff\Code\Template\HeaderedContent;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Helper\Log\Logs as Service;

class Logs extends HeaderedContent
{
	protected Service $logs;
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		$this->logs = Service::init();
	}

	public function getTitle(): string
	{
		return "Logs";
	}

	public function content(): void
	{
		?>
		<?php
	}
}