<?php
namespace Jeff\Code\Template;

class HeadlessContent extends Content
{
	protected function top(): void
	{
		?>
		<div class='top_cont'> 
			Top 
		</div>
		<div class='content_cont'>
		<?php
	}

	protected function bottom(): void
	{
		?>
		</div>
		<div class='bottom_cont'> 
			Bottom 
		</div>
		<?php
	}
}