<?php
namespace Jeff\Code\Page;

use Jeff\Code\D;

use Jeff\Code\Template\HeaderedContent;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Helper\Log\LogService;

use Jeff\Code\Template\Elements\Select;

class Logs extends HeaderedContent
{
	protected LogService $logService;
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		$this->actions = new Actions();
		$this->methods = new Methods();
		$this->logService = new LogService();
	}

	public function getTitle(): string
	{
		return "Logs";
	}

	public function content(): void
	{
		$actionSelect = new Select('action', $this->actions->data(), $this->actions->default(), $_POST['action'] ?? 0, '[Select an action]');
		$methodSelect = new Select('method', $this->methods->data(), $this->methods->default(), $_POST['method'] ?? 0, '[Select a method]');
		?>
		<form method='post'>
			<?=$actionSelect?>
			<?=$methodSelect?>
		</form>
		<?php
	}
}