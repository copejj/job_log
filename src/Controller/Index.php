<?php
namespace Jeff\Code\Controller;

use Jeff\Code\Util\Config;
use Jeff\Code\Util\D;
use Jeff\Code\View\HeaderedContent;

class Index extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "Hi There";
	}

	protected function content(): void
	{
		$externalUrl = Config::get('JAVA_EXTERNAL_URL');

		?>
		<div class="about-cont">
			<div class="about-text">
				<p>
					This is the <strong>Brain Dribbler</strong> page.  I put this page and a few others together to experiment with
					different tech and essentially exercising my brain while sometimes beating my head against a wall.  
				</p>
				<p>
					This page is a Java page using the Spring-Boot framework on top of some Thymeleaf templates.  You
					can find out more in the specs in the <a href="/?page=about">About</a> section if interested.  I have also put a fair amount
					of effort into a PHP <strong>Brain Dribble</strong> over <a href="<?=$externalUrl?>" target="blank">HERE</a> if interested.
				</p>
			</div>
			<div class="about-image"></div>
		</div>
		<?php
	}
}