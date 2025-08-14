<?php
namespace Jeff\Code\Page\Week;

use Jeff\Code\Helper\Actions;
use Jeff\Code\Helper\Methods;
use Jeff\Code\Template\Elements\Checkboxes;

class WeekEdit extends Weeks
{
	protected Actions $actions;
	protected Methods $methods;

	public function processing(): void
	{
		parent::processing();
		$this->actions = new Actions();
		$this->methods = new Methods();
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