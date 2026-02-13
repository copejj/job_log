<?php
namespace Jeff\Code\Controller;

use Jeff\Code\Util\Config;
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
					This page is a PHP page kind of home rolled in a semi-informed MVC style structure.  You
					can find out more in the specs below if interested. I have also put a fair amount of effort into 
					a Java version as well. 
				</p>
				<p>
					<ul>
						<li> <a href="/?page=about">PHP Server Specs</a> </li>
						<li> <a href="<?=$externalUrl?>" target="blank">Java Brain Dribble</a> </li>
					</ul>
				</p>
			</div>
			<div class="about-image"></div>
		</div>
		<?php
	}
}