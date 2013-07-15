<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

/**
 * Return an associative array for everything that's needed for a text input with the given name.
 * @param input_name The name of the input field.
 */
function get_input_array($input_name) {
	return array("name" => $input_name, "id" => $input_name);
}

/**
 * Given an associative array, return an options string to add as 4th parameter to input_dropdown.
 */
function get_dropdown_options($arr) {
	$a2 = array();
	
	foreach ($arr as $key => $value) {
		$a2[] = $key . "='" . $value . "'";
	}
	
	return join(" ", $a2);
}

?>

<!DOCTYPE html>

<html>
	<head>
		<title>Landing Page</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		
		<style>
			#logo {
				/*border: 1px solid #000;*/
				width: 100px;
			}
		
			input, select {
				display: block;
			}
			
			body {
				font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: 1em;
			}
		</style>
		
		<script>
			/**
			 * Created a String.format function, because I was sick of constantly concatenating strings.
			 * Works similarly to Python's string format function.
			 */
			String.prototype.format = function () {
				var s = this;
				
				for (var i = 0; i < arguments.length; i++) {
					s = s.replace("{" + i + "}", arguments[i]);
				}
				
				return s;
			};
		
			/**
			 * Given that a given field has changed value, alter the other campus field to the opposite value.
			 */
			function changeOtherCampus(field, value) {
				"use strict";
				
				var otherField = field == "to" ? "from" : "to", otherValue;
				
				if (value == "UTSG") {
					otherValue = "UTM";
				} else if (value == "UTM") {
					otherValue = "UTSG";
				} else {
					otherValue = "";
				}
				
				$("#" + otherField).find("option[value='{0}']".format(otherValue)).prop("selected", true);
			}
		
			$(function() {
				$(".campusChooser").change(function() {
					console.log("here");
					changeOtherCampus($(this).attr("id"), $(this).val());
				});
				
				$("#date").datepicker({
					numberOfMonths: 2,
					showButtonPanel: true
				});
			});
		</script>
	</head>
	
	<body>
		<h1>Landing Page</h1>
		
		<div id="logoContainer">
			<!-- <img id="logo" src="<?=base_url() ?>/images/blacksheep.jpg" /> -->
		</div>
		
		<div id="searchPanel">
			<?php
				echo form_open('airuoft/searchFlights');
				
				$campus_options = array(
					"" => " -- Select a Campus --",
					"UTSG" => "St. George",
					"UTM" => "Mississauga"
				);
				
				echo form_label('From');
				echo form_dropdown("from", $campus_options, "", get_dropdown_options(array("id"=>"from", "class"=>"campusChooser")));
				
				// also a drop-down... 
				echo form_label('To');
				echo form_dropdown("to", $campus_options, "", get_dropdown_options(array("id"=>"to", "class"=>"campusChooser")));
				
				echo form_label('Date');
				echo form_input(get_input_array("date"));
				
				echo form_submit('search', 'Search Flights');
				echo form_close();
			?>
		</div>
	</body>
</html>