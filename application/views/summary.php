<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");
$logger = FirePHP::getInstance(true);

function showVals($arr) {
	foreach ($arr as $k => $v) {
		echo "<div>";
		echo "<span class='userField $k'>$k</span>";
		if ($v == "expDate") {
			$val = $_SESSION['expMonth'] . "/" . $_SESSION['expYear'];
		} else {
			$val = $_SESSION[$v];
		}
		
		echo "<span class='userVal $k'>$val</span>";
		echo "</div>";
	}
}

?>

<!DOCTYPE html>

<html>
	<head>
		<title>Summary | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<style>
			.userField {
				padding-right: 10px;
			}
		</style>
		
		<script>
			$(function() {
				$("#printButton").click(function() {
					window.print();
				});
			})
		</script>
	</head>
	
	<body>
		<h1>Order Summary</h1>
		
		<p>Congratulations! Your ticket is confirmed.</p>
		
		<div id="customerInfo">
			<h3>Customer Info</h3>
			<?php
				$arr = array(
					"First Name" => "fName", 
					"Last Name" => "lName", 
					"Credit Card Number" => "ccNum",
					"Expiry Date" => "expDate"
				);
				
				showVals($arr);
			?>
		</div>
		
		<div id="flightInfo">
			<h3>Flight Info</h3>
			<?php
				$arr = array(
					"Departure Campus" => "from", 
					"Destination Campus" => "to", 
					"Departure Date" => "date",
					"Departure Time" => "time",
					"Seat Number" => "seatNum",
					"Flight Number" => "flightID"
				);
				
				showVals($arr);
			?>
		</div>
		
		<button type="button" id="printButton">Print Ticket</button>
	</body>
</html>