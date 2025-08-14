<?php
namespace Jeff\Code\Page\Log;

use Jeff\Code\Template\HeaderedContent;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Helper\Log\LogService;

class Logs extends HeaderedContent
{
	protected LogService $logService;
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		$this->logService = new LogService();
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