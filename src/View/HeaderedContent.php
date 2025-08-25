<?php
namespace Jeff\Code\View;

abstract class HeaderedContent extends HeadlessContent
{
	protected function header(): void
	{
		?>
		<!DOCTYPE html>
		<html lang="en">
			<head>
				<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
				<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
				<link rel="stylesheet" href="css/style.css">
				<link href="css/datatables/datatables.min.css" rel="stylesheet" integrity="sha384-R2Fgx8DD9wAkVgw6wT8uF062Yb82xSJ9SoEeo6yG3TnkFB1xM/YFXwjBYo0kQ0ct" crossorigin="anonymous">
				<script src="js/datatables/datatables.min.js" integrity="sha384-UeCeWNYLaY2mvxN9AsU1R1+aV0hde/ksgd68wqgf87HJF8ge3gGnhUPPh0hHFooK" crossorigin="anonymous"></script>
				<title>Jeff's Work Log Page</title>
			</head>
			<body>
				<div class='header-cont'> Jeff's Work Log Page </div>
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
				<div class='links-cont'>
					<?=implode($refs)?>
				</div>
			<?php
		}
	}

	protected function footer(): void 
	{
		?>
				<div class='footer-cont'> &copy; 2025 Jeff Cope, All Rights Reserved. </div>
				<dialog id="myModal">
					<h2>Modal Title</h2>
					<div id="dialogContainer"></div>
					<button id="closeModal">Close</button>
				</dialog>
				<script src="js/main.js"></script>
			</body>
		</html>
		<?php
	}
}