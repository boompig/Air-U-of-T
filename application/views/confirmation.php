<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");
$edit = $title == "Confirmation";

/**
 * For every value v in $arr, show $_SESSION[v]
 * $arr is an associative array of human-readable names mapped to session keys
 * 
 * Display a table (no headers), with 2-3 columns
 * First column (if $edit) pencil linking to where to change (else) empty
 * Second column is human-readable names
 * Third column are values
 * 
 * @param {array} $arr
 * @param {bool} $edit
 */
function showVals($arr, $edit=true) {
	echo "<table>";
	
	foreach ($arr as $k => $v) {
		if ($v == "expDate") {
			$val = $_SESSION['expMonth'] . "/" . $_SESSION['expYear'];
		} else {
			$val = $_SESSION[$v];
		}
		
		echo "<tr>";
			echo "<td>";
			if ($edit && $v == "seatNum") {
				echo "<a href='airuoft/searchSeats'><span class='ui-icon ui-icon-pencil' title='Change your seat'></span></a>";
			}
			echo "</td>";
		
			echo "<td><span class='userField $k'>$k</span></td>";
			echo "<td><span class='userVal $k'>$val</span></td>";
		echo "</tr>";
	}
	
	echo "</table>";
}

?>

<!DOCTYPE html>

<html>
	<head>
		<title><?=$title?> | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/confirmation.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<script>
			$(function() {
				// $("#accordion").accordion();
				$("button, input[type=submit]").button();
				
				$(document).tooltip();
			});
		</script>
	</head>
	
	<body>
		<h1><?=$title?></h1>
		
		<div id="content">
			<div id="accordion" class="ui-accordion ui-widget ui-helper-reset">
				<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
					<!-- <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span> -->
					Passenger Info
				</h3>
					
				<div id="customerInfo" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
					
					<?php
						$_SESSION["passName"] = $_SESSION["fName"] . " " . $_SESSION["lName"];
					
						$arr = array(
							"Name" => "passName",
							"Credit Card Number" => "ccNum",
							"Expiry Date" => "expDate"
						);
						
						showVals($arr);
						unset($_SESSION["passName"]);
					?>
				</div>
				
				<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
					<!-- <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span> -->
					Flight Info
				</h3>
				<div id="flightInfo" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
					
					<?php
						$arr = array(
							"Departure Campus" => "from", 
							"Destination Campus" => "to", 
							"Departure Date" => "date",
							"Departure Time" => "time",
							"Seat" => "seatNum"
						);
						
						showVals($arr, $edit);
					?>
				</div>
			</div> <!-- end accordion -->
			
			
			<?php
				if ($title == "Confirmation") {
					echo form_open("airuoft/buyTicket");
					echo form_submit("submit", "Buy Ticket");
					echo form_close();
				} else {
					// summary
					// $(\'.noPrint\').hide().css(\'display\', \'none\'); 
					echo '<button type="button" class="noPrint" id="printButton" onclick="window.print()">Print Ticket</button>';
				}
			?>
		</div> <!-- end content -->
	</body>
</html>