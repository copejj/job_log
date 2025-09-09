<?php
namespace Jeff\Code\Controller;

use Jeff\Code\View\HeaderedContent;

class UnderDevelopment extends HeaderedContent
{
	public function getTitle(): string
	{
		return "Under Development";
	}

	public function content(): void
	{
		?>
		<p>This page is under construction, please be patient.<i class="fa-solid fa-person-digging"></i></p>
		<?php
	}
}