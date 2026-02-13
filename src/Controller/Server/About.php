<?php
namespace Jeff\Code\Controller\Server;

use Jeff\Code\View\HeaderedContent;

class About extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "About";
	}

	protected function content(): void
	{
		?>
		<p>This is the about that never displays... it just goes on and on for days...</p>
		<?php
	}
}