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
	 * The main landing page for the admin portal.
	 */
	function admin() {
		$this->load->view("admin");
	}
	
	/**
	 * This function is called by landing.php (or alternatively flightinfo.php) to search for flights.
	 * Load the flights into an array 
	 * It is expected that the $_REQUEST has the following set: parameter have the date set in this format: 'mm/dd/yyyy'
	 * 
	 * Sets the following session variables:
	 * 	- 'from'	-	departure campus
	 *  - 'to'		-	arrival campus
	 * 	- 'date'	- 	departure date
	 */
	function searchFlights () {
		$this->load->library("form_validation");
		
		$this->form_validation->set_rules("from", "Departure Campus", "required");
		$this->form_validation->set_rules("to", "Destination Campus", "required");
		$this->form_validation->set_rules("date", "Departure Date", "required");
		
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
			$departureDate = DateTime::createFromFormat("m/d/Y", $_REQUEST['date']);
			
			// query DB for flight times
			$data["times"] = $this->airuoft_model->get_available_flights($_REQUEST['from'], $_REQUEST['to'], date_format($departureDate, "Y-m-d"));
			
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
	 * 	- 'time'	- departure time  (HH:MM:SS) where MM and SS should be zeros
	 */
	function searchSeats () {
		// TODO check that everything is set
		
		$this->logger->log($_REQUEST['time'], "departure time");
		$_SESSION['time'] = $_REQUEST['time'];
	}
	
	/**
	 * Create flights for the next 14 days.
	 */
	function createFlights () {
		// load the main model
		$this->load->model("airuoft_model");
		// fill the table
		$this->airuoft_model->fill_flights();
		// redirect back to admin page
		// TODO show some sort of confirmation
		$this->load->view("admin");
	}
}

?>
