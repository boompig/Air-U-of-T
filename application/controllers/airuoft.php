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
	 * 	- 'from'	-	departure campus
	 *  - 'to'		-	arrival campus
	 * 	- 'date'	- 	departure date (in the format yyyy-mm-dd)
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
	 * This function is called by flightinfo.php to search for seats
	 * Load the seats into an array
	 * 
	 * Sets the following session variables:
	 * 	- 'time'		- departure time  (HH:MM:SS) where MM and SS should be zeros
	 * 	- 'flightID'	- flight ID
	 */
	function searchSeats () {
		if (! in_array($_REQUEST["flightID"], $_SESSION["validFlights"])) {
			//TODO trigger an error here	
		}
		
		// by this point, input verified
		$_SESSION['time'] = $_REQUEST['time'];
		$_SESSION['flightID'] = $_REQUEST['flightID'];
		
		
		
		$this->load->model("airuoft_model");
		$_SESSION["seatNum"] = $this->airuoft_model->get_available_seats($_SESSION["flightID"]);
		
		foreach (array("from", "to", "date", "time", "flightID", "seatNum") as $k) {
			$this->logger->log($_SESSION[$k], $k);
		}
	}
}

?>
