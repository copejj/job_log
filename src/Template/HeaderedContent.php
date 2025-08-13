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
				<div class='links_cont'>
					<a href='/?'>Home</a>
					<a href='/?page=log'>Log</a>
				</div>
		<?php
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