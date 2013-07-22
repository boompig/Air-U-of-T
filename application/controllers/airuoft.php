<?php

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");

/**
 * Main controller for Air U of T project (AKA CSC309 A2)
 */
class AirUofT extends CI_Controller {
	
	/**
	 * Typical constructor. Starts the session.
	 */
	function __construct() {
		parent::__construct();
		session_start();
		
		// TODO for development, create link with FirePHP Console
		$this->logger = FirePHP::getInstance(true);
	}
	
	/**
	 * The main landing page for the customer portal.
	 */
	function index() {
		$this->load->view("landing");
	}
	
	/**
	 * This function is called by landing.php (or alternatively flightinfo.php) to search for flights.
	 * Load the flights into an array 
	 *  
	 * The $_REQUEST parameter 'date' should be in the format 'yyyy-mm-dd'
	 * 
	 * Sets the following session variables:
	 * 	'from'			-	departure campus
	 *  'to'			-	arrival campus
	 * 	'date'			- 	departure date (in the format yyyy-mm-dd)
	 * 	'validFlights'	-	valid flights
	 */
	function searchFlights () {
		$this->load->library("form_validation");
		
		$this->form_validation->set_rules("from", "Departure Campus", "required");
		$this->form_validation->set_rules("to", "Destination Campus", "required");
		$this->form_validation->set_rules("date", "Departure Date", "required");
		// TODO validate date server-side
		// TODO also make sure date is not in the past, and date is not beyond max scope
		
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		// remember everything user entered in $_SESSION variable
		foreach (array("from", "to", "date", "time") as $k) {
			if (array_key_exists($k, $_REQUEST)) {
				$_SESSION[$k] = $_REQUEST[$k];
			}
		}
		
		if ($this->form_validation->run()) {
			// load the main model
			$this->load->model("airuoft_model");
			
			date_default_timezone_set("UTC");
			
			// format date correctly for DB
			$departureDate = DateTime::createFromFormat("Y-m-d", $_REQUEST['date']);
			
			// query DB for flight times
			$data["times"] = $this->airuoft_model->get_available_flights($_REQUEST['from'], $_REQUEST['to'], date_format($departureDate, "Y-m-d"));
			
			// THIS IS VERY IMPORTANT:
			// remember valid flightID's in the $_SESSION variable, so only valid times can be booked (i.e. those returned to the user)
			$_SESSION["validFlights"] = array_values($data["times"]);
			
			// redirect to flight info, where user can pick a flight
			$this->load->view("flightinfo", $data);
		} else {
			$this->load->view("landing");
		}
	}
	
	/**
	 * Transition to customer info page.
	 * 
	 * Sets the following session variables:
	 * 	'seatNum'	-	index of the seat
	 */
	function customerInfo () {
		// TODO check that seat is set
		// TODO check that the selected seat is valid
		
		// to allow backwards navigation
		if (key_exists("seat", $_REQUEST)) {
			$_SESSION["seatNum"] = $_REQUEST["seat"];
		}
		
		// TODO debugging to make sure all data is good up to this point
		// TODO this is not needed
		foreach (array("from", "to", "date", "time", "flightID", "seatNum") as $k) {
			$this->logger->log($_SESSION[$k], $k);
		}
		
		$this->load->view("billing");
	}
	
	/**
	 * This function is called by flightinfo.php to search for seats
	 * Load the seats into an array
	 * 
	 * Sets the following session variables:
	 * 	'time'		-	departure time  (HH:MM:SS) where MM and SS should be zeros
	 * 	'flightID'	-	flight ID
	 * 
	 * Pass the following variables to the view:
	 * 	$occupied	- 	Array of occupied seats
	 * 	$available	-	Array of available seats
	 * 	$seats		-	Array of all seats
	 */
	function searchSeats () {
		if (! in_array($_REQUEST["flightID"], $_SESSION["validFlights"])) {
			//TODO trigger an error here	
		}
		
		// by this point, input verified
		$_SESSION['time'] = $_REQUEST['time'];
		$_SESSION['flightID'] = $_REQUEST['flightID'];
		
		$this->load->model("airuoft_model");
		$data["available"] = $this->airuoft_model->get_available_seats($_SESSION["flightID"]);
		 
		$_SESSION["validSeats"] = $data["available"];
		
		$data["seats"] = range(0, 2);
		$data["occupied"] = range(0, 2);
		
		foreach ($data["seats"] as $seat) {
			if (key_exists($seat, $data["available"])) {
				unset($data["occupied"][$seat]);
			}
		}
		
		$this->load->view("seats", $data);
	}
	
	/**
	 * This function is called by billing.php to display a summary of all info entered thus far
	 * Verify that all customer info is set properly, then redirect to confirmation.php
	 * 
	 * Sets the following session variables:
	 * 	'fName'		-	client first name
	 * 	'lName'		-	client last name
	 * 	'ccNum'		-	credit card number
	 * 	'expDate'	-	expiry date of the card (as a DateTime object)
	 * 	'expYear'	-	expiry year of credit card
	 * 	'expMonth'	-	expiry month of credit card
	 */
	function confirm () {
		// TODO validate inputs
		
		date_default_timezone_set("UTC");
		
		
		// once inputs are validated
		foreach(array("fName", "lName", "ccNum", "expMonth", "expYear") as $k) {
			// to support backwards navigation
			if (key_exists($k, $_REQUEST))
				$_SESSION[$k] = $_REQUEST[$k];
		}
		
		// used to check for repeated submissions in buyTicket
		$_SESSION['lastView'] = 'confirmation';
		$this->logger->log($_SESSION['lastView'], 'last view');
		
		$_SESSION["expDate"] = DateTime::createFromFormat("Y-m-t", $_SESSION['expYear'] . "-" . $_SESSION['expMonth'] . "-00");
		
		$data = array("title" => "Confirmation");
		$this->load->view("confirmation", $data);
	}
	
	/**
	 * This function is called by confirmation.php to process the ticket purchase, then display a printable summary.
	 * Only process DB requests from pages OTHER THAN SUMMARY
	 * i.e. If summary is refreshed, do not add another item to the DB
	 */
	function buyTicket () {
		$this->logger->log($_SESSION['lastView'], 'last view');
		
		$data = array("title" => "Summary");
		
		if ($_SESSION['lastView'] != 'summary') {
			
			$this->load->model("airuoft_model");
			$result = $this->airuoft_model->create_ticket($_SESSION['fName'], $_SESSION['lName'], $_SESSION['ccNum'], $_SESSION['expMonth'] . $_SESSION['expYear'], $_SESSION['flightID'], $_SESSION['seatNum']);
			
			$this->logger->log($result, "result");
			
			if ($result) {
				$this->logger->log("It's Good!", "Ticket Result");
				$_SESSION['lastView'] = 'summary';
				
				$this->load->view("confirmation", $data);
			} else {
				$this->logger->log("Failed =.=", "Ticket Result");
			}
		} else {
			$this->load->view("confirmation", $data);
		}
	}
}

?>
