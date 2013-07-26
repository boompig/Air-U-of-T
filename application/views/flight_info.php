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

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");
$logger = FirePHP::getInstance(true);
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Flight Selection | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- favicon -->
		<link rel="icon" type="image/x-icon" href="<?=base_url() ?>/images/airplane-med.png" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/navbar.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/flight_info.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		<script src="<?=base_url(); ?>/js/flight.js"></script>
		
		<script>
			$(function() {
				Flight.setupCampusChooser(".campusChooser");
				
				$("#date").datepicker({
					minDate: "+1D",
					maxDate: "+14D",
					dateFormat: "yy-mm-dd" // this line actually means yyyy-mm-dd
				});
				
				$("input[type=submit], button").button();
				
				$(".selectButton").click(function() {
					// get departure time for the button's row
					var time = $(this).parent().parent().find("td:first").html();
					var flightID = $(this).parent().parent().find("td .flightID").html();
					
					$("#time").val(time);
					$("#flightID").val(flightID);
					$("#seatForm").submit();
				});
			});
		</script>
	</head>
	
	<body>
		<h1>Choose a Flight</h1>
		
		<?php $this->load->view("navbar.php"); ?>
		
		<div id="searchPanel">
			<div id="toolbar">
				<?php
					echo HTML_Utils::form_open("airuoft/searchFlights");
				?>
				
				<div id="fromPanel" class="inputPanel">
					<?php
						echo form_dropdown("from", $campus_options, $_SESSION['from'], HTML_Utils::get_dropdown_options(array("id"=>"from", "class"=>"campusChooser")));
					?>
				</div>
				
				<img src="<?=base_url() ?>/images/arrow_alt_right.png" class="inputPanel" />
				<!-- <span class="ui-icon ui-icon-circle-arrow-e inputPanel"></span> -->
				
				<div id="toPanel" class="inputPanel">
					<?php
						echo form_dropdown("to", $campus_options, $_SESSION['to'], HTML_Utils::get_dropdown_options(array("id"=>"to", "class"=>"campusChooser")));
					?>
				</div>
				
				<div id="datePanel" class="inputPanel">
					<?php
						$arr = HTML_Utils::get_input_array("date");
						$arr["value"] = $_SESSION["date"];
						$arr["size"] = 10;
						
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
					echo form_submit("search", "Search Flights");
					echo form_close();
				?>
				
			</div> <!-- end datePanel -->
			
			<div id="errorPanel" class="ui-state-highlight ui-corner-all" style="display: <?php if (validation_errors()) echo 'block'; else echo 'none'; ?>;">
				<span class="ui-icon ui-icon-alert"><!-- icon --></span>
				<span><?=validation_errors(); ?></span>
			</div>
			
			<?php
				if (count($times) > 0) {
					$this->table->set_heading(array ("Depart", "Arrive", "Seats Available", "", ""));
					$i = 0;
					foreach($times as $flightID => $arr) {
						// $i++;
						$this->table->add_row(array ($arr["time"], preg_replace("/00/", "30", $arr["time"], 1), $arr["numSeats"], "<button type='button' class='selectButton'>Select</button>", "<span class='flightID' style='display: none;'>" . $flightID . "</span>"));
					}
					
					echo $this->table->generate();
				}
			?>
			
			<div class="ui-state-highlight ui-corner-all" style="display: <?php if (count($times) > 0) echo 'none'; else echo 'block'; ?>;">
				<span class="ui-icon ui-icon-alert"><!-- icon --></span>
				Whoops! There were no flights matching your search. Consider changing your departure date.
			</div>
			
			<?=HTML_Utils::form_open("airuoft/searchSeats", array("id" => 'seatForm')); ?>
				<input type="hidden" name="time" id="time" value="" required="required" pattern="\d{2}\:00\:00" />
				<input type="hidden" name="flightID" id="flightID" value="" required="required" pattern="\d+" />
			<?=form_close(); ?>
		</div> <!-- end search panel -->
	</body>
</html>