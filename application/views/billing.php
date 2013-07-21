<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");

// set $_SESSION variables, so don't error out at the bottom
foreach (array("fName", "lName", "ccNum", "expMonth", "expYear") as $k) {
	if (! array_key_exists($k, $_SESSION))
		$_SESSION[$k] = "";
}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Billing | Air U of T</title>
		<meta charset="UTF-8" />
		
	</head>
	
	<body>
		<h1>Billing</h1>
		
		<?=form_open("airuoft/confirm"); ?>
			
			<div>
				<?php
					echo form_label("First Name: ");
					$arr = HTML_Utils::get_input_array("fName");
					$arr['required'] = 'required';
					$arr['value'] = $_SESSION['fName'];
					echo form_input($arr);
					
					echo form_label("Last Name: ");
					$arr = HTML_Utils::get_input_array("lName");
					$arr['required'] = 'required';
					$arr['value'] = $_SESSION['lName'];
					echo form_input($arr);
					
					// TODO have a pattern for this
					echo form_label("Credit Card Number: ");
					$arr = HTML_Utils::get_input_array("ccNum");
					$arr['required'] = 'required';
					$arr['value'] = $_SESSION['ccNum'];
					echo form_input($arr);
					
					// TODO have a pattern for this
					echo form_label("Credit Card Expiry: ");
					$arr = HTML_Utils::get_input_array("expMonth");
					$arr['required'] = 'required';
					$arr['value'] = $_SESSION['expMonth'];
					$arr['size'] = 2;
					echo form_input($arr);
					
					echo "<span>/</span>";
					
					$arr = HTML_Utils::get_input_array("expYear");
					$arr['required'] = 'required';
					$arr['value'] = $_SESSION['expYear'];
					$arr['size'] = 2;
					echo form_input($arr);
					
					echo form_submit("submit", "Next");
				?>
			</div>
			
			
			<?=form_close(); ?>
	</body>
</html>