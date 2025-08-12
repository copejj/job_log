<?php
namespace Jeff\Code\Page;

use Jeff\Code\D;

use Jeff\Code\Template\HeaderedContent;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Helper\Log\LogService;

class LogPage extends HeaderedContent
{
	protected LogService $logService;
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		$this->actions = new Actions();
		D::p('actions', [$this->actions->default(), $this->actions->data()]);
		$this->methods = new Methods();
		D::p('methods', [$this->actions->default(), $this->actions->data()]);
		$this->logService = new LogService();
	}

	public function content(): void
	{
		?>
		<h2>Logs</h2>
		<form method='post'>
			
		</form>
		<?php
	}
}