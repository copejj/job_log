<?php
namespace Jeff\Code\Page;

use Jeff\Code\Template\HeaderedContent;

class Index extends HeaderedContent
{
	protected function content(): void
	{
		?>
		Hello, World!
		<?php
	}
}