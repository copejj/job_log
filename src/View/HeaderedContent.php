<?php
namespace Jeff\Code\View;

use Jeff\Code\Util\D;

abstract class HeaderedContent extends HeadlessContent
{
	protected function header(): void
	{
		$redirect = '';
		$has_redirect = $this->has_redirect;
		if (!empty($has_redirect))
		{
			$redirect = 
				"<meta charset='UTF-8'>
				<meta http-equiv='refresh' content='0; url={$this->has_redirect}'>
				<script type='text/javascript'>
					window.location.href = '{$this->has_redirect}'
				</script>";
		}
		?>
		<!DOCTYPE html>
		<html lang="en">
			<head> <?=$redirect?>
				<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
				<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
				<link rel="stylesheet" href="css/style.css">
				<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
				<link href="css/datatables/datatables.min.css" rel="stylesheet" integrity="sha384-R2Fgx8DD9wAkVgw6wT8uF062Yb82xSJ9SoEeo6yG3TnkFB1xM/YFXwjBYo0kQ0ct" crossorigin="anonymous">
				<script src="js/datatables/datatables.min.js" integrity="sha384-UeCeWNYLaY2mvxN9AsU1R1+aV0hde/ksgd68wqgf87HJF8ge3gGnhUPPh0hHFooK" crossorigin="anonymous"></script>
				<title> Work Log </title>
			</head>
			<body>
				<div class='full-page-content'>
					<div class='header-cont'> Work Log </div>
		<?php
	}

	protected function links(): void
	{
		?>
			<div class='links-cont'>
				<?=$this->links?>
			</div>
		<?php
	}

	protected function footer(): void 
	{
		?>
					<div class='footer-cont'> &copy; 2025 Jeff Cope, All Rights Reserved. </div>
					<dialog id="pageModal" class='page-modal'>
						<button id="closeModal"> X </button>
						<div id="pageContent" class='page-content'></div>
					</dialog>
					<script src="js/main.js"></script>
				</div>
			</body>
		</html>
		<?php
	}
}