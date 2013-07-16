<?php

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");

/**
 * Main controller for Air U of T project (AKA CSC309 A2)
 */
class Admin extends CI_Controller {
	/**
	 * Typical constructor.
	 */
	function __construct () {
		parent::__construct();
		
		// TODO for development, create link with FirePHP Console
		$this->logger = FirePHP::getInstance(true);
	}
	
	/**
	 * The main landing page for the admin portal.
	 */
	function index () {
		$this->load->view("admin");
	}
	
	/**
	 * Create flights for the next 14 days.
	 * Pass an associative array to the view called 'result', with the following parameters:
	 * 		'status'	-	boolean indicating whether action succeeded or not
	 * 		'msg'		-	String, a message
	 */
	function createFlights () {
		$this->load->model("airuoft_model");
		
		$status = $this->airuoft_model->fill_flights();
		
		$data["result"] = array("status" => $status);
		
		if ($status) {
			$data["result"]["msg"] = "Flights added successfully";
		} else {
			$data["result"]["msg"] = "Failed to fill flight table";
		}
		
		$this->load->view("admin", $data);
	}
	
	/**
	 * Return all sold tickets as an array.
	 * Each ticket has the following keys:
	 * 	'flightDate'	-	flight date
	 * 	'seatNum'		-	seat number
	 * 	'fName'			-	customer first name
	 * 	'lName'			-	customer last name
	 * 	'ccNum'			-	credit card number
	 * 	'expDate'		- 	credit card expiry date
	 */
	function getTickets () {
		$this->load->model("airuoft_model");
		$data["tickets"] = $this->airuoft_model->get_tickets();
		
		$this->logger->log($data["tickets"], "Tickets");
		
		$this->load->view("soldtickets", $data);
	}
	
	/**
	 * Delete all flight and ticket information.
	 * Pass an associative array to the view called 'result', with the following parameters:
	 * 		'status'	-	boolean indicating whether action succeeded or not
	 * 		'msg'		-	String, a message
	 */
	function deleteAll () {
		$this->load->model("airuoft_model");
		
		$status = $this->airuoft_model->delete_flights_and_tickets();
		
		$data["result"] = array("status" => $status);
		
		if ($status) {
			$data["result"]["msg"] = "Data deleted successfully";
		} else {
			$data["result"]["msg"] = "Failed to delete data";
		}
		
		$this->load->view("admin", $data);
	}
}

?>