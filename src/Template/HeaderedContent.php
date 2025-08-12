<?php
namespace Jeff\Code\Template;

class HeaderedContent extends HeadlessContent
{
	protected function header(): void
	{
		?>
		<html>
			<head>
				<link rel="stylesheet" href="css/style.css">
			</head>
			<body>
				<div class='header_cont'> Header </div>
		<?php
	}

	protected function footer(): void 
	{
		?>
				<div class='footer_cont'> Footer </div>
			</body>
		</html>
		<?php
	}
}