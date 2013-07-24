<?php

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");

/**
 * Main controller for Air U of T project (AKA CSC309 A2)
 */
class AirUofT extends CI_Controller {
	
	private $logger;
	/**
	 * @returns {array}
	 */
	private $campuses;
	/**
	 * @returns {array}
	 */
	private $flightTimes;	
	/**
	 * @returns {array}
	 */
	private $seats;
	
	/**
	 * Typical constructor. Starts the session.
	 */
	function __construct() {
		parent::__construct();
		session_start();
		
		// TODO for development, create link with FirePHP Console
		$this->logger = FirePHP::getInstance(true);
		
		$this->campuses = array("UTSG" => "St. George", "UTM" => "Mississauga");
		$this->flightTimes = array(8, 10, 14, 17);
		$this->seats = range(0, 2);
		
		// set this globally
		date_default_timezone_set("UTC");
	}
	
	function getHackerMessage($field) {
		return sprintf("I think you're trying to hack the %s. It won't work. Stop it and go away.", $field);
	}
	
	/**
	 * Return true iff the given campus is a valid campus.
	 * Used for form validation.
	 */
	function validCampus($campus) {
		$result = key_exists($campus, $this->campuses);
		if (! $result) {
			$this->form_validation->set_message($this->getHackerMessage("campus field"));
		}
		
		return $result;
	}
	
	/**
	 * Return true iff the given date is a valid flight date.
	 * This means:
	 * 	1. The date is valid (and in the correct format)
	 * 	2. It is in the future (i.e. not today)
	 * 	3. It is no more than 2 weeks in the future
	 */
	function validFlightDate($flightDateStr) {
		date_default_timezone_set("UTC");
		$flightDate = DateTime::createFromFormat("Y-m-d", $flightDateStr);
		
		if ($flightDate === false) {
			$this->form_validation->set_message("validFlightDate", "The departure date has to be in the format yyyy-mm-dd");
			return false;
		}
		
		date_default_timezone_set("UTC");
		$today = new DateTime();
		$today->setTime(0, 0, 0);
		$flightDate->setTime(0, 0, 0);
		$latest = clone($today);
		$latest->add(new DateInterval("P14D")); // 14 days from now
		
		if ($today > $flightDate) {
			$this->form_validation->set_message("validFlightDate", "You may not book flights in the past");
			return false;
		} else if ($today == $flightDate) {
			$this->form_validation->set_message("validFlightDate", "You may not book a flight for today. You can book one for tomorrow, though.");
			return false;
		} else if ($latest < $flightDate) {
			$this->form_validation->set_message("validFlightDate", "You cannot reserve a flight more than 2 weeks into the future");
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Return true iff the given expiration date is a valid expiration date.
	 * This means:
	 * 	1. The date is valid (and in the correct format) (mmyy)
	 * 	2. It is in the future
	 */
	function validExpiration($expDateStr) {
		date_default_timezone_set("UTC");
		$expDate = DateTime::createFromFormat("dmy", "01" . $expDateStr);
		if ($expDate === false) {
			$this->form_validation->set_message("validExpiration", $this->getHackerMessage("CC expiration date"));
			return false;
		}
		
		$today = new DateTime();
		$expDate->setTime(0, 0, 0);
		$expDate->add(new DateInterval("P1M"));
		
		$result = $today < $expDate;
		// $this->logger->log($today, "today");
		// $this->logger->log($expDate, "expDate");
		
		if (! $result) {
			$this->form_validation->set_message("validExpiration", "Whoops! It looks like your credit card has expired!");
		}
		
		return $result;
	}
	
	/**
	 * Return true iff flightID is a valid flight ID.
	 */
	function validFlightID ($flightID) {
		$result = in_array($flightID, $_SESSION["validFlights"]);
		
		if (! $result) {
			$this->form_validation->set_message("validFlightID", $this->getHackerMessage("flight ID"));
		}
		
		return $result;
	}
	
	/**
	 * TODO this only checks if the flight time is technically valid, but not that it corresponds to the given flight ID
	 */
	function validFlightTime ($time) {
		$result = in_array($time, $this->flightTimes);
		
		if (! $result) {
			$this->form_validation->set_message("validFlightID", $this->getHackerMessage("flight time"));
		}
		
		return $result;
	}
	
	/**
	 * Return true iff the $seatNum is one of the available seats.
	 */
	function validSeat ($seatNum) {
		$result = in_array($seatNum, $_SESSION['validSeats']);
		
		if(! $result) {
			$this->form_validation->set_message("validFlightID", $this->getHackerMessage("seat"));
		}
		
		return $result;
	}
	
	/**
	 * For each key in $vars, save $_SESSION[k] = $_REQUEST[k], if that key is set in the request.
	 * @param {array} $vars
	 */
	function saveRequest ($vars) {
		foreach ($vars as $k) {
			if (isset($_REQUEST[$k])) {
				$_SESSION[$k] = $_REQUEST[$k];
			}
		}
	}
	
	/**
	 * The opposite of saveRequest - puts session variables into Request, to re-run validation.
	 * This allows saving variables between states without sacrificing validative power.
	 * ONLY PERFORM THIS ACTION IF REQUEST PARAM NOT SET
	 */
	function setRequest($vars) {
		// make sure none of the parameters are set in the request
		foreach ($vars as $k) {
			if (isset($_REQUEST[$k])) {
				$this->logger->log($k, "early return");
				return;
			}
		}
		
		foreach ($vars as $k) {
			if (isset($_SESSION[$k])) {
				$_REQUEST[$k] = $_SESSION[$k];
			}
		}
	}
	
	function logSession() {
		$this->logger->log($_SESSION, "session");
		// foreach ($_SESSION as $k => $v) {
			// $this->logger->log($v, $k);
		// }
	}
	
	function logRequest() {
		$this->logger->log($_REQUEST, "request");
		// foreach ($_REQUEST as $k => $v) {
			// $this->logger->log($v, $k);
		// }
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
		
		$this->form_validation->set_rules("from", "Departure Campus", "required|callback_validCampus");
		$this->form_validation->set_rules("to", "Destination Campus", "required|callback_validCampus");
		$this->form_validation->set_rules("date", "Departure Date", "required|callback_validFlightDate");
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->saveRequest(array("from", "to", "date"));
		$this->setRequest(array("from", "to", "date"));
		
		$this->logRequest();
		
		if ($this->form_validation->run()) {
			
			date_default_timezone_set("UTC");
			$departureDate = DateTime::createFromFormat("Y-m-d", $_REQUEST['date']);
			
			$this->load->model("airuoft_model");
			$data["times"] = $this->airuoft_model->get_available_flights($_REQUEST['from'], $_REQUEST['to'], date_format($departureDate, "Y-m-d"));
			
			// THIS IS VERY IMPORTANT:
			// remember valid flightID's in the $_SESSION variable, so only valid times can be booked (i.e. those returned to the user)
			$_SESSION["validFlights"] = array_keys($data["times"]);
			
			$this->logSession();
			
			$this->load->view("flightinfo", $data);
		} else {
			$this->index();
		}
	}
	
	/**
	 * This function is called by flightinfo.php to search for seats
	 * Load the seats into an array
	 * 
	 * Sets the following session variables:
	 * 	'time'		-	departure time  (HH:MM:SS) where MM and SS should be zeros
	 * 	'flightID'	-	flight ID
	 * 
	 * Although note that flight time is not really used anywhere, as Flight ID is the important field
	 * 
	 * Pass the following variables to the view:
	 * 	$occupied	- 	Array of occupied seats
	 * 	$available	-	Array of available seats
	 * 	$seats		-	Array of all seats
	 */
	function searchSeats () {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("flightID", "Flight Time", "required|callback_validFlightID");
		$this->form_validation->set_rules("time", "Flight Time", "required|callback_validFlightTime");
		
		$this->setRequest(array("flightID", "time"));
		
		// adding session check allows for inter-view navigation
		if ($this->form_validation->run()) {
			// only set these once they are verified
			$this->saveRequest(array("time", "flightID"));
			
			$this->load->model("airuoft_model");
			$data["available"] = $this->airuoft_model->get_available_seats($_SESSION["flightID"]);
			 
			$_SESSION["validSeats"] = $data["available"];
			
			$data["seats"] = $this->seats;
			$data["occupied"] = $this->seats;
			
			foreach ($data["seats"] as $seat) {
				if (key_exists($seat, $data["available"])) {
					unset($data["occupied"][$seat]);
				}
			}
			
			$this->logSession();
			$this->load->view("seats", $data);
		} else {
			$this->logger->log("Failed validation on flights");
			$this->logger->log(validation_errors(), "errors");
			$this->logger->log($this->validFlightTime($_REQUEST["time"]), "valid flight time?");
			$this->logger->log($this->validFlightID($_REQUEST["flightID"]), "valid flight ID?");
			$this->logRequest();
			$this->searchFlights();
		}
	}

	/**
	 * Transition to customer info page.
	 * 
	 * Sets the following session variables:
	 * 	'seatNum'	-	index of the seat
	 */
	function customerInfo () {
		$this->setRequest(array("seatNum"));
		
		$this->load->library("form_validation");
		$this->form_validation->set_rules("seatNum", "Seat Number", "required|callback_validSeat");
		
		if ($this->form_validation->run()) {
			$this->saveRequest(array("seatNum"));
			$this->logSession();
			$this->load->view("passenger_info");
		} else {
			$this->logger->log("Failed seat number validation");
			$this->logger->log($this->validSeat($_REQUEST["seatNum"]), "valid seat num?");
			$this->logger->log(validation_errors(), "errors");
			$this->searchSeats();
		}
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
	function confirmation () {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("fName", "First Name", "trim|required");
		$this->form_validation->set_rules("lName", "Last Name", "trim|required");
		$this->form_validation->set_rules("ccNum", "Credit Card Number", "trim|required|regex_match[/\d{16}/]");
		$this->form_validation->set_rules("ccExp", "Credit Card Expiration Date", "required|callback_validExpiration");
		
		$this->saveRequest(array("fName", "lName", "ccNum", "ccExp", "expMonth", "expYear"));
		$this->setRequest(array("fName", "lName", "ccNum", "ccExp", "expMonth", "expYear"));
		
		if ($this->form_validation->run()) {
			$_SESSION['lastView'] = 'confirmation';
			
			date_default_timezone_set("UTC");
			$_SESSION["expDate"] = DateTime::createFromFormat("dmy", "01" . $_SESSION['ccExp']);
			
			$this->logSession();
			$data = array("title" => "Confirmation");
			$this->load->view("confirmation", $data);
		} else {
			$this->logSession();
			$this->customerInfo();
		}
	}
	
	/**
	 * This function is called by confirmation.php to process the ticket purchase, then display a printable summary.
	 * Only process DB requests from pages OTHER THAN SUMMARY
	 * i.e. If summary is refreshed, do not add another item to the DB
	 */
	function buyTicket () {
		$this->logger->log($_SESSION['lastView'], 'last view');
		$data = array("title" => "Summary");
		
		if (isset($_SESSION['lastView']) && $_SESSION['lastView'] != 'summary') {
			
			$this->load->model("airuoft_model");
			$result = $this->airuoft_model->create_ticket($_SESSION['fName'], $_SESSION['lName'], $_SESSION['ccNum'], $_SESSION['expMonth'] . $_SESSION['expYear'], $_SESSION['flightID'], $_SESSION['seatNum']);
			
			$this->logger->log($result, "result");
			
			if ($result) {
				$this->logger->log("It's Good!", "Ticket Result");
				$_SESSION['lastView'] = 'summary';
				
				$this->load->view("confirmation", $data);
			} else {
				// TODO have this show up on another page
				$this->logger->log("Failed =.=", "Ticket Result");
			}
		} else {
			// loading confirmation view, but as summary
			$this->load->view("confirmation", $data);
		}
	}
	
	/**
	 * Unset everything from $_SESSION variable. Start over.
	 */
	function reset () {
		$_SESSION = array();
		$this->index();
	}
}

?>
