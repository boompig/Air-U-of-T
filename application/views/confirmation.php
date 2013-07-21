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
		<title>Confirmation | Air U of T</title>
		<meta charset="UTF-8" />
		
		<style>
			.userField {
				padding-right: 10px;
			}
		</style>
	</head>
	
	<body>
		<h1>Confirmation</h1>
		
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
					"Seat Number" => "seatNum"
				);
				
				showVals($arr);
			?>
		</div>
	</body>
</html>