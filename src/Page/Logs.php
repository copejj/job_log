<?php
namespace Jeff\Code\Page;

use Jeff\Code\D;

use Jeff\Code\Template\HeaderedContent;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Helper\Log\LogService;

use Jeff\Code\Template\Elements\Checkboxes;
use Jeff\Code\Template\Elements\RadioButton;

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
		$actionInput = new Checkboxes('action', $this->actions->data(), $_POST['action'] ?? [], 'Actions');
		$methodInput = new Checkboxes('method', $this->methods->data(), $_POST['method'] ?? [], "Methods");
		?>
		<form method='post'>
			<?=$actionInput?>
			<?=$methodInput?>
			<input type='submit' value='Submit' />
		</form>
		<?php
	}
}