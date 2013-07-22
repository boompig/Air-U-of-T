<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");

// set $_SESSION variables, so don't error out at the bottom
foreach (array("fName", "lName", "ccNum", "expMonth", "expYear", "ccExp") as $k) {
	if (! array_key_exists($k, $_SESSION))
		$_SESSION[$k] = "";
}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Billing | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/navbar.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error_box.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/billing.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<script>
			/**
			 * Fill out all fields with junk data that will pass the validator.
			 */
			function junkFill () {
				"use strict";
				
				var randDate = Date.randomFutureDate();
				var randMonth = randDate.getMonth() + 1;
				
				$("#expMonth").val(String(randMonth).pad(2, "0"));
				$("#expYear").val(randDate.getFullYear() - 2000);
				
				$("#ccNum").val(Utils.genRandomCC());
				
				var name = Utils.getRandomName().split(" ");
				$("#fName").val(name[0]);
				$("#lName").val(name[1]);
			}
		
			/**
			 * Return True iff the month and year are valid.
			 * Invalid situations:
			 * 	1. Month is invalid #
			 * 	2. Credit card has expired
			 * 
			 * This function shows an error message if there is an error
			 * Will not show the error if it looks like input is only partial
			 */
			function checkDate() {
				var month = $("#expMonth").val();
				var year = $("#expYear").val();
				
				$(".errorBox").hide();
				
				if (isNaN(month) || month.length == 0 || month < 1 || month > 12 || isNaN(year) || year.length == 0) {
					return false;
				} else {
					// recall that JS numbers months from 0-11, so the first day of the month of expiry is (year, month - 1, 1)
					// however, we want the first day of the month *after* expiry, = (year, (month - 1) + 1, 1)
					var expiryDate = new Date("20" + String(year), Number(month), 1);
					var now = new Date();
					
					// console.log(expiryDate);
					// console.log(now);
					// console.log("valid date? " + now < expiryDate);
					
					if (now >= expiryDate) {
						$("#formError").show();
					}
					
					return now < expiryDate;
				}
			}
		
			$(function() {
				$("button, input[type=submit]").button();
				
				$(".ccExp, #ccNum").attr("type", "number");
				
				$("#myForm").submit(function () {
					$("#ccExp").val(String($("#expMonth").val()) + String($("#expYear").val()));
					
					// return checkDate();
				});
				
				$("#expYear").blur(function() {
					checkDate();
				});
				
				$("#expMonth, #expYear").change(function() {
					$("#ccExp").val(String($("#expMonth").val()) + String($("#expYear").val()));
					console.log($("#ccExp").val());
				});
				
				$("#expYear").change(function() {
					checkDate();
				});
				
				$("#expMonth").keyup(function() {
					if ($(this).val().length == 2) {
						$("#expYear").focus();
					}
				});
				
				$("#autofill").click(function() {
					junkFill();
				});
			});
		</script>
	</head>
	
	<body>
		<h1>Passenger Info</h1>
		
		<?php $this->load->view("navbar") ?>
		
		<div id="container">
			<?=form_open("airuoft/confirmation", array("id" => "myForm")); ?>
				
				<fieldset class="userInput" id="nameContainer">
					<?php
						echo form_label("First Name");
						$arr = HTML_Utils::get_input_array("fName");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['fName'];
						$arr['class'] = "name";
						echo form_input($arr);
						
						echo form_label("Last Name");
						$arr = HTML_Utils::get_input_array("lName");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['lName'];
						$arr['class'] = "name";
						echo form_input($arr);
					?>
				</fieldset> <!-- end nameContainer -->
				<fieldset class="userInput" id="ccContainer">
					<legend>Credit Card Info</legend>
					<?php
						echo form_label("Credit Card Number");
						$arr = HTML_Utils::get_input_array("ccNum");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['ccNum'];
						// $arr['pattern'] = "\d{16}";
						$arr['size'] = 16;
						$arr['maxlength'] = 16;
						$arr['oninvalid'] = "setCustomValidity('Credit card number must be 16 digits long with no dashes or spaces')";
						echo form_input($arr);
						
						echo form_label("Expiry Date");
						$arr = HTML_Utils::get_input_array("expMonth");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['expMonth'];
						$arr['size'] = 2;
						$arr['maxlength'] = 2;
						$arr['min'] = 1;
						$arr['max'] = 12;
						$arr['pattern'] = "\d{2}";
						$arr['class'] = 'ccExp';
						$arr['placeholder'] = "mm";
						echo form_input($arr);
						
						echo "<span>/</span>";
						
						$arr = HTML_Utils::get_input_array("expYear");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['expYear'];
						$arr['size'] = 2;
						$arr['maxlength'] = 2;
						$arr['pattern'] = "\d{2}";
						$arr['class'] = 'ccExp';
						$arr['placeholder'] = "yy";
						echo form_input($arr);
						
						echo "<input type='hidden' name='ccExp' id='ccExp' value='" . $_SESSION['ccExp'] . "' />";
					?>
				</fieldset> <!-- end ccContainer -->
				<?php
					echo form_submit("submit", "Next");
				?>
				
				<span id="autofill" class="ui-icon-pencil ui-icon"></span>
				
				<?=form_close(); ?>
				
				<?php $this->load->view("error_box"); ?>
				
				<div class="errorBox ui-state-highlight ui-corner-all" style="display: none;">
					<p>
						<span class="ui-icon ui-icon-info"></span>
						<span>Whoops! It looks like your credit card has expired!</span>
					</p>
				</div>
		</div> <!-- end container -->
	</body>
</html>