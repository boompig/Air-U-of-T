<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");

$campus_options = array(
	"" => " -- Choose a Campus --",
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
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/landing.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		<script src="<?=base_url(); ?>/js/flight.js"></script>
		
		<script>
			$(function() {
				f = new Flight();
				f.setupCampusChooser(".campusChooser");
				
				// I can't seem to figure out how to set this up in CI, so will use JS
				$("#date").attr("type", "date");
				
				$("#date").datepicker({
					minDate: "+1D",
					maxDate: "+14D",
					dateFormat: "yy-mm-dd" // this line actually means yyyy-mm-dd
				});
				
				// $("input[type=text], select").after($("<div class='errors'></div>"));
				
				$("form").submit(function() {
					return f.validate_inputs();
				});
				
				$("input[type=submit], button").button();
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
				// echo validation_errors();
				echo form_open('airuoft/searchFlights');
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
					
					// stuff for client-side validation
					$arr['required'] = "required";
					$arr['pattern'] = "\d{4}\-\d{2}\-\d{2}";
					echo form_input($arr);
				?>
				<!-- empty link to get icon -->
				<a href="#">
					<span class="ui-state-default ui-corner-all ui-icon ui-icon-calendar"></span>
				</a>
			</div>
			<?php
				echo form_submit('search', 'Search Flights');
			?>
			<!-- </div> -->
			<?php
				echo form_close();
			?>
		</div>
	</body>
</html>