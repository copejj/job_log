<?php
namespace Jeff\Code\Template;

abstract class HeaderedContent extends HeadlessContent
{
	protected function header(): void
	{
		?>
		<html>
			<head>
				<link rel="stylesheet" href="css/style.css">
			</head>
			<body>
				<div class='header_cont'> Jeff's Work Log Page </div>
		<?php
	}

	protected function links(): void
	{
		$links = $this->links;
		if (!empty($links))
		{
			$refs = [];
			foreach ($links as $label => $ref)
			{
				$refs[] = "<a href='{$ref}'>{$label}</a>";
			}
			?>
				<div class='links_cont'>
					<?=implode($refs)?>
				</div>
			<?php
		}
	}

	protected function footer(): void 
	{
		?>
				<div class='footer_cont'> &copy; 2025 Jeff Cope, All Rights Reserved. </div>
			</body>
		</html>
		<?php
	}
}