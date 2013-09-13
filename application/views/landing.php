<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");

$campus_options = array(
	"" => "&nbsp;-- Choose a Campus --&nbsp;",
	"UTSG" => "St. George",
	"UTM" => "Mississauga"
);

// set $_SESSION variables, so don't error out at the bottom
foreach (array("from", "to", "date", "time") as $k) {
	if (! array_key_exists($k, $_SESSION))
		$_SESSION[$k] = "";
}

?>

<!DOCTYPE html>

<html>
	<head>
		<title>Flight Search | Air U of T</title>
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
		<link rel="stylesheet" href="<?=base_url(); ?>/css/landing.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		<script src="<?=base_url(); ?>/js/junk_fill.js"></script>
		<script src="<?=base_url(); ?>/js/form_validate.js"></script>
		<script src="<?=base_url(); ?>/js/flight.js"></script>
		
		<script>
			function addValidator () {
				"use strict";
				
				$("form").validate({
					"rules" : {
						"date" : {
							"required" : true,
							"validDateFormat" : true,
							"validDate" : true,
							"checkFutureDate" : true
						},
						"from" : {
							"required" : true,
							"validCampus" : true,
						},
						"to" : {
							"required" : true,
							"validCampus" : true
						}
					}
				});
			}
			
			function junkFill () {
				"use strict";
				
				var campus;
				var fields = ["to", "from"];
				
				for (var i = 0; i < fields.length; i++) {
					if (i === 0) {
						campus = JunkFill.getRandomCampus();
					} else {
						campus = Flight.otherCampus(campus);
					}
					
					$("#" + fields[i]).find("option[value={0}]".format(campus)).prop("selected", true);
				}
				
				// get a day after today's date
				var randDate = JunkFill.getRandomFlightDate();
				var year = String(randDate.getFullYear());
				var month = (String(randDate.getMonth() + 1)).pad(2, "0");
				var day = (String(randDate.getDate())).pad(2, "0");
				
				$("#date").val("{0}-{1}-{2}".format(year, month, day));
			}
		
			$(function() {
				"use strict";
				
				$("select, input[type=text]").each(function() {
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
				$.validator.addMethod ("validDateFormat", validDateFormat, "Date expected in format YYYY-MM-DD");
				$.validator.addMethod ("validDate", validDate, "Invalid date given");
				$.validator.addMethod ("checkFutureDate", checkFutureDate, "You may not book flights in the past");
				$.validator.addMethod ("validCampus", validCampus, "Campus must be one of UTSG, UTM");
				
				Flight.setupCampusChooser(".campusChooser");
				
				// hide the server errors once fields have changed
				$("#date, #from, #to").change (function() {
					$(".error").hide();
				});
				
				$("#date").datepicker({
					minDate: "+1D",
					maxDate: "+14D",
					dateFormat: "yy-mm-dd" // this line actually means yyyy-mm-dd
				});
				
				<?=HTML_Utils::pentestComment() ?>addValidator();
				
				$("input[type=submit], button").button();
				
				$("#autofill").click(function() {
					junkFill();
				});
				
				$(document).tooltip();
			});
		</script>
	</head>
	
	<body>
		<?php $this->load->view("header.php"); ?>
		
		<div id="logoContainer">
			<!-- <img id="logo" src="<?=base_url() ?>/images/blacksheep.jpg" /> -->
		</div>
		
		<div id="searchPanel">
			<?php
				// echo validation_errors();
				echo HTML_Utils::form_open('airuoft/searchFlights');
			?>
			<div id="fromPanel" class="inputPanel">
				<?php
					echo form_label("From");
					echo form_error("from");
					$data = array("id"=>"from", "class"=>"campusChooser");
					echo form_dropdown("from", $campus_options, $_SESSION["from"], HTML_Utils::get_dropdown_options($data));
				?>
			</div> <!-- end from panel -->
			<div id="toPanel" class="inputPanel">
				<?php
					echo form_label("To");
					echo form_error("to");
					$data = array("id"=>"to", "class"=>"campusChooser");
					echo form_dropdown("to", $campus_options, $_SESSION["to"], HTML_Utils::get_dropdown_options($data));
				?>
			</div> <!-- end toPanel -->
			<div id="datePanel" class="inputPanel">
				<?php
					echo form_label("Date");
					echo form_error("date");
					
					$arr = HTML_Utils::get_input_array("date");
					$arr['value'] = $_SESSION['date'];
					$arr['size'] = 10;
					$arr['placeholder'] = "yyyy-mm-dd";
					
					echo form_input($arr);
				?>
				<!-- empty link to get icon -->
				<!-- <a href="#"> -->
					<!-- <span class="ui-state-default ui-corner-all ui-icon ui-icon-calendar"></span> -->
				<!-- </a> -->
			</div> <!-- end datePanel -->
			<div id="submitPanel" class="inputPanel">
				<?php
					echo form_submit('search', 'Search Flights');
				?>
			</div>
			<!-- </div> -->
			<?php
				echo form_close();
			?>
		</div> <!-- end search panel -->
		
		<footer>
			<?php
				
				$classes = array ("ui-icon", "ui-icon-pencil", "bottom-nav");
				$contents = HTML_Utils::span("", array("id" => "autofill", "title" => "autofill", "class" => $classes));
				echo $contents;
			
				$classes = array ("ui-icon", "ui-icon-trash", "bottom-nav");
				$contents = HTML_Utils::span("", array("title" => "reset", "class" => $classes));
				echo anchor("airuoft/reset", $contents, array("title" => "reset"));
				
                // remove admin portal link from page
				//$classes = array ("ui-icon", "ui-icon-locked", "bottom-nav");
				//$contents = HTML_Utils::span("", array("title" => "admin portal", "class" => $classes));
				//echo anchor("admin/admin", $contents, array("title" => "admin portal"));
			?>
		</footer>
	</body>
</html>
