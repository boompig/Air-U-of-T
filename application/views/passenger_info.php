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
		
		<!-- favicon -->
		<link rel="icon" type="image/x-icon" href="<?=base_url() ?>/images/airplane-med.png" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery Form Validator -->
		<script src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
		<script src="http://jquery.bassistance.de/validate/additional-methods.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/navbar.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error_box.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/passenger_info.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		<script src="<?=base_url(); ?>/js/form_validate.js"></script>
		
		<script>
			/**
			 * Fill out all fields with junk data that will pass the validator.
			 */
			function junkFill () {
				"use strict";
				
				var randDate = Date.randomFutureDate();
				var randMonth = String(randDate.getMonth() + 1).pad(2, "0");
				var randYear = String(randDate.getFullYear() - 2000).pad(2, "0");
				
				$("#expMonth").find("option[value={0}]".format(randMonth)).prop("selected", true);
				$("#expYear").find("option[value={0}]".format(randYear)).prop("selected", true);
				
				$("#ccNum").val(Utils.genRandomCC());
				
				var name = Utils.getRandomName().split(" ");
				$("#fName").val(name[0]);
				$("#lName").val(name[1]);
			}
		
			$(function() {
				"use strict";
				
				$(".ccExp").find("option[value='']").attr("disabled", "disabled");
				
				$("select, input[type=text]").not("[name=expYear]").each(function() {
					var name = $(this).attr("name");
					var d = $("<div class='invalid' generated='true'></div>").attr("for", name);
					$(this).before(d);
				});
				
				$.validator.messages["required"] = "This field is required";
				
				$.validator.setDefaults({
					"errorClass" : "invalid",
					"errorElement" : "div",
					"validClass" : "valid",
					"success" : "valid"
				});
				
				// custom validator functions
				$.validator.addMethod ("validCreditCardNumber", validCreditCardNumber, "Credit card number should be 16 digits without spaces");
				$.validator.addMethod ("checkCCExpMonth", checkCCExpMonth, "You must select a valid month");
				$.validator.addMethod ("checkCCExpYear", checkCCExpYear, "You must select a valid year");
				$.validator.addMethod ("checkFutureExpiryDate", checkFutureExpiryDate, "Whoops! Your credit card has expired");
				
				// hide the server errors once fields have changed
				$("#ccNum, #fName, #lName", "#expMonth", "#expYear").change(function() {
					$(".error").hide();
				});
				
				$.validator.addClassRules ("ccExp", {
					"checkFutureExpiryDate" : true
				});
				
				$("form").validate({
					"groups" : {
						"expDate" : "expMonth expYear"
					},
					"rules" : {
						"ccNum" : {
							"required" : true,
							"validCreditCardNumber" : true
						},
						"expMonth" : {
							"required" : true,
							"checkCCExpMonth" : true
						},
						"expYear" : {
							"required" : true,
							"checkCCExpYear" : true
						},
						"fName" : {
							"required" : true
						}, 
						"lName" : {
							"required" : true
						}
					},
					"errorPlacement": function (error, elem) {
						if (elem.hasClass("ccExp")) {
							error.insertBefore("#expMonth");
						} else {
							error.insertBefore(elem);
						}
					},
					"success" : function (label) {
						var year = $("#expYear").val();
						var month = $("#expMonth").val();
						$("#ccExp").val(String(month) + String(year));
					}
				});
				
				
				$("button, input[type=submit]").button();
				
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
			
				<div id="fNameContainer" class="userInput">
					<?php
						echo form_label("First Name");
						$arr = HTML_Utils::get_input_array("fName");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['fName'];
						$arr['class'] = "name";
						echo form_input($arr);
					?>
				</div>
				<div id="lNameContainer" class="userInput">
					<?php
						echo form_label("Last Name");
						$arr = HTML_Utils::get_input_array("lName");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['lName'];
						$arr['class'] = "name";
						echo form_input($arr);
					?>
				</div>
				<div id="ccContainer" class="userInput">
					<?php
						echo form_label("Credit Card Number");
						$arr = HTML_Utils::get_input_array("ccNum");
						$arr['required'] = 'required';
						$arr['value'] = $_SESSION['ccNum'];
						$arr['size'] = 16;
						$arr['maxlength'] = 16;
						$arr['placeholder'] = "16 digits, no spaces";
						echo form_input($arr);
					?>
				</div>
				<div id="ccExpDateContainer" class="userInput">
					<?php
						echo form_label("Expiry Date");
						
						$monthOptions = array();
						$monthOptions[''] = "MM";
						
						foreach (range (1, 12) as $m) {
							if ($m < 10) {
								$padM = "0" . ((string) $m);
							} else {
								$padM = (string) $m;
							}
							
							$monthOptions[$padM] = $padM;
						}
						
						$data = HTML_Utils::get_dropdown_options (array ("id" => "expMonth", "class" => "ccExp"));
						echo form_dropdown("expMonth", $monthOptions, $_SESSION['expMonth'], $data);
						
						// $arr = HTML_Utils::get_input_array("expMonth");
						// $arr['required'] = 'required';
						// $arr['value'] = $_SESSION['expMonth'];
						// $arr['size'] = 2;
						// $arr['maxlength'] = 2;
						// $arr['min'] = 1;
						// $arr['max'] = 12;
						// $arr['pattern'] = "\d{2}";
						// $arr['class'] = 'ccExp';
						// $arr['placeholder'] = "mm";
						// echo form_input($arr);
						
						echo "<span>&nbsp;/&nbsp;</span>";
						
						$yearOptions = array();
						// 8 years into the future
						$yearOptions[''] = "YY";
						$currentYear = date("Y") - 2000;
						
						foreach(range ($currentYear, $currentYear + 8) as $y) {
							
							if ($y < 10) {
								$padY = "0" . ((string) $y);
							} else {
								$padY = (string) $y;
							}
							
							$yearOptions[$padY] = $padY;
						}
						
						$data = HTML_Utils::get_dropdown_options (array ("id" => "expYear", "class" => "ccExp"));
						echo form_dropdown("expYear", $yearOptions, $_SESSION['expYear'], $data);
						
						
						// $arr = HTML_Utils::get_input_array("expYear");
						// $arr['required'] = 'required';
						// $arr['value'] = $_SESSION['expYear'];
						// $arr['size'] = 2;
						// $arr['maxlength'] = 2;
						// $arr['pattern'] = "\d{2}";
						// $arr['class'] = 'ccExp';
						// $arr['placeholder'] = "yy";
						// echo form_input($arr);
						
						echo "<input type='hidden' name='ccExp' id='ccExp' value='" . $_SESSION['ccExp'] . "' />";
					?>
				</div>
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