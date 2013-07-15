<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Flight Selection</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		<script src="<?=base_url(); ?>/js/flight.js"></script>
		
		<script>
			$(function() {
				setupCampusChooser(".campusChooser");
				
				$("#date").datepicker({
					minDate: "+1D",
					maxDate: "+14D"
				});
			});
		</script>
	</head>
	
	<body>
		<h1>Choose a Flight</h1>
		<?php
			$campus_options = array(
				"" => " -- Choose a Campus --",
				"UTSG" => "St. George",
				"UTM" => "Mississauga"
			);
		
			// form 1 - redo information from before
			echo form_open("airuoft/searchFlights");
			echo form_label("From");
			echo form_dropdown("from", $campus_options, $_SESSION['from'], HTML_Utils::get_dropdown_options(array("id"=>"from", "class"=>"campusChooser")));
			
			echo form_label('To');
			echo form_dropdown("to", $campus_options, $_SESSION['to'], HTML_Utils::get_dropdown_options(array("id"=>"to", "class"=>"campusChooser")));
			
			echo form_label('Date');
			$arr = HTML_Utils::get_input_array("date");
			$arr["value"] = $_SESSION['date'];
			echo form_input($arr);
			echo form_submit("search", "Search Flights");
			echo form_close();
			
			// form 2 - get flight information
			
			$time_options = array();
			foreach($times as $time) {
				$time_options[$time] = $time;
			}
			
			echo form_open("airuoft/searchSeats");
			echo form_label("Flight Time");
			echo form_dropdown("time", $time_options, HTML_Utils::get_dropdown_options(array("id"=>"time")));
			echo form_submit("submit", "Proceed to seat selection");
			echo form_close();
		?>
	</body>
</html>