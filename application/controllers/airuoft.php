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
	 * This function is called by landing.php to search for flights.
	 * Load the flights into an array 
	 * It is expected that the $_REQUEST parameter have the date set in this format: 'dd/mm/yyyy'
	 */
	function searchFlights() {
		// load the main model
		$this->load->model("airuoft_model");
		
		$departureDate = DateTime::createFromFormat("m/d/Y", $_REQUEST['date']);
		
		$this->airuoft_model->get_available_flights($_REQUEST['from'], $_REQUEST['to'], date_format($departureDate, "Y-m-d"));
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
