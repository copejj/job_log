<?php
namespace Jeff\Code\View;

abstract class HeaderedContent extends HeadlessContent
{
	protected function header(): void
	{
		$extra_text = (!empty($_SESSION['user_id']) && empty($_SESSION['can_edit'])) ? '(read only)' : '';
		$redirect = '';
		if (!empty($this->has_redirect))
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
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
				<script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/3.4.4/luxon.min.js"></script>
				<link href="css/datatables/datatables.min.css" rel="stylesheet" integrity="sha384-R2Fgx8DD9wAkVgw6wT8uF062Yb82xSJ9SoEeo6yG3TnkFB1xM/YFXwjBYo0kQ0ct" crossorigin="anonymous">
				<script src="js/datatables/datatables.min.js" integrity="sha384-UeCeWNYLaY2mvxN9AsU1R1+aV0hde/ksgd68wqgf87HJF8ge3gGnhUPPh0hHFooK" crossorigin="anonymous"></script>
				<title> Applied Jobs Log </title>
			</head>
			<body>
				<div class='full-page-content'>
					<div class='header-cont'> Applied Jobs Log <?=$extra_text?> </div>
		<?php
	}

	protected function links(): void
	{
		?>
			<div class='menu-container links-cont'>
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