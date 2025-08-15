<?php
namespace Jeff\Code\Controller;

use Jeff\Code\View\HeaderedContent;

class Index extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "Hi There";
	}

	protected function content(): void
	{
		?>
		<p>I wanted to play around with some objects and inheritance and see what I could build.</p> 
		<p>You are now swept up in that mess...</p>
		<p>Congrats.</p>
		<?php
	}
}