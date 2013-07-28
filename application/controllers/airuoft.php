<?php

/**
 * Main controller for Air U of T project (AKA CSC309 A2)
 */
class AirUofT extends CI_Controller {
	
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
	 * Whether pentesting is turned on or not.
	 */
	private $penTest;
	
	/**
	 * Typical constructor. Starts the session.
	 */
	function __construct() {
		parent::__construct();
		session_start();
		
		$this->campuses = array("UTSG" => "St. George", "UTM" => "Mississauga");
		$this->flightTimes = array(8, 10, 14, 17);
		$this->seats = range(0, 2);
		
		// set this globally
		date_default_timezone_set("UTC");
		
		// whether to keep or not keep pentesting
		$this->penTest = false;
		// $_SESSION['pentest'] = $this->penTest;
	}
	
	function getHackerMessage($field) {
		return sprintf("I think you're trying to hack the %s. It won't work. Stop it and go away.", $field);
	}
	
	/**
	 * Return true iff the given campus is a valid campus.
	 * Used for form validation.
	 */
	function validCampus ($campus) {
		$result = isset($this->campuses[$campus]);
		if (! $result) {
			$this->form_validation->set_message("validCampus", $this->getHackerMessage("campus field"));
		}
		
		return $result;
	}
	
	/**
	 * Return true iff $toCampus != $fromCampus
	 */
	function differentCampuses ($toCampus) {
		if (func_num_args() > 1 && func_get_arg(1)) {
			$fromCampus = func_get_arg(1);
		} else {
			$fromCampus = $_REQUEST['from'];
		}
		
		if ($fromCampus === $toCampus) {
			$this->form_validation->set_message("differentCampuses", "Departure and arrival campuses must be different");
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Return true iff the given date is a valid flight date.
	 * This means:
	 * 	1. The date is valid (and in the correct format)
	 * 	2. It is in the future (i.e. not today)
	 * 	3. It is no more than 2 weeks in the future
	 */
	function validFlightDate($flightDateStr) {
		$flightDate = DateTime::createFromFormat("Y-m-d", $flightDateStr);
		
		if ($flightDate === false) {
			$this->form_validation->set_message("validFlightDate", "The departure date has to be in the format yyyy-mm-dd");
			return false;
		}
		
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
		if (strlen($expDateStr) !== 4) {
			// do not display the hacker message if a field is not set
			$this->form_validation->set_message("validExpiration", "Credit card expiration date has improper format " . strlen($expDateStr));
			return false;
		}
		
		$expDate = DateTime::createFromFormat("dmy", "01" . $expDateStr);
		if ($expDate === false) {
			$this->form_validation->set_message("validExpiration", $this->getHackerMessage("CC expiration date"));
			return false;
		}
		
		$today = new DateTime();
		$expDate->setTime(0, 0, 0);
		$expDate->add(new DateInterval("P1M"));
		
		$result = $today < $expDate;
		
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
			$this->form_validation->set_message("validSeat", $this->getHackerMessage("seat"));
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
	
	function logSession() {
		//$this->logger->log($_SESSION, "session");
	}
	
	function logRequest() {
		//$this->logger->log($_REQUEST, "request");
	}
	
	/**
	 * Add given view to visited
	 * If TRUE, means view is successfully completed
	 * If FALSE, means view has just been visited
	 */
	function addVisited($view, $status=false) {
		if (! isset($_SESSION['visited'])) {
			$_SESSION['visited'] = array();
		}
		
		$_SESSION['visited'][$view] = $status;
	}
	
	/**
	 * The main landing page for the customer portal.
	 */
	function index() {
		$this->load->view("landing");
	}
	
	/**
	 * This function is called by landing.php (or alternatively flight_info.php) to search for flights.
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
		$this->form_validation->set_rules("to", "Destination Campus", "required|callback_validCampus|callback_differentCampuses");
		$this->form_validation->set_rules("date", "Departure Date", "required|callback_validFlightDate");
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->saveRequest(array("from", "to", "date"));
		
		if (func_num_args() > 0) {
			// unfortunately, there is no data integrity in date, from, to (because they are saved prior to validation)
			// which means we have to re-validate them
			$fromValid = isset($_SESSION["from"]) && $this->validCampus($_SESSION['from']);
			$toValid = isset($_SESSION["to"]) && $this->validCampus($_SESSION['to']) && $this->differentCampuses($_SESSION['to'], $_SESSION['from']);
			$dateValid = isset($_SESSION["date"]) && $this->validFlightDate($_SESSION['date']);
			
			$noValidate = func_get_arg(0) && $fromValid && $toValid && $dateValid;
		} else {
			$noValidate = false;
		}
		
		if ($noValidate || $this->form_validation->run()) {
			$departureDate = DateTime::createFromFormat("Y-m-d", $_SESSION['date']);
			
			$this->load->model("airuoft_model");
			$data["times"] = $this->airuoft_model->get_available_flights($_SESSION['from'], $_SESSION['to'], date_format($departureDate, "Y-m-d"));
			
			// THIS IS VERY IMPORTANT:
			// remember valid flightIDs in the $_SESSION variable, so only valid times can be booked (i.e. those returned to the user)
			$_SESSION["validFlights"] = array_keys($data["times"]);
			
			$this->load->view("flight_info", $data);
		} else {
			$this->index();
		}
	}
	
	/**
	 * This function is called by flight_info.php to search for seats
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
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		if (func_num_args() > 0) {
			// this means we got here by navigation
			// so now we validate $_SESSION instead of $_REQUEST
			// however, we know that $_SESSION for these vars only set when they are valid
			// so can just check if they are set
			$flightValid = isset($_SESSION['flightID']) && isset($_SESSION['time']);
			//$this->logSession();
			
			$noValidate = func_get_arg(0) && $flightValid;
		} else {
			$noValidate = false;
		}
		
		if ($noValidate || $this->form_validation->run()) {
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
			$this->load->view("seats", $data);
		} else {
			$this->searchFlights(true);
		}
	}

	/**
	 * Transition to customer info page.
	 * 
	 * Sets the following session variables:
	 * 	'seatNum'	-	index of the seat
	 */
	function customerInfo () {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("seatNum", "Seat Number", "required|callback_validSeat");
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		if (func_num_args() > 0) {
			// means we got here by navigation
			// again, setNum has integrity, in that it is only set in the session if it is valid
			$noValidate = func_get_arg(0) && isset($_SESSION['seatNum']);
		} else {
			$noValidate = false;
		}
		
		if ($noValidate || $this->form_validation->run()) {
			$this->saveRequest(array("seatNum"));
			$this->load->view("passenger_info");
		} else {
			$this->searchSeats(true);
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
		$this->form_validation->set_rules("expMonth", "Credit Card Expiration Month", "required");
		$this->form_validation->set_rules("expYear", "Credit Card Expiration Year", "required");
		$this->form_validation->set_rules("ccExp", "Credit Card Expiration Date", "required|callback_validExpiration");
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->saveRequest(array("fName", "lName", "ccNum", "ccExp", "expMonth", "expYear"));
		
		// first, make sure the rest is filled in
		if (! (isset($_SESSION['to']) && isset($_SESSION['from']) && isset($_SESSION['date']))) {
			$this->index();
		} else if (! (isset($_SESSION['time']) && isset($_SESSION['flightID']))){
			$this->searchFlights(true);
		} else if (! isset($_SESSION['seatNum'])) {
			$this->searchSeats(true);
		}
		
		if (func_num_args() > 0) {
			$validName = isset($_SESSION['fName']) && isset($_SESSION['lName']);
			$validCC = isset($_SESSION['ccNum']) && preg_match("/\d{16}/", $_SESSION['ccNum']);
			$validExp = isset($_SESSION['expMonth']) && isset($_SESSION['expYear']) && isset($_SESSION['ccExp']) && $_SESSION['expMonth'] . $_SESSION['expYear'] === $_SESSION['ccExp'] && $this->validExpiration($_SESSION['ccExp']);
			$noValidate = $validName && $validCC && $validExp && func_get_arg(0);
		} else {
			$noValidate = false;
		}
		
		if ($noValidate || $this->form_validation->run()) {
			$_SESSION['lastView'] = 'confirmation';
			
			$_SESSION["expDate"] = DateTime::createFromFormat("dmy", "01" . $_SESSION['ccExp']);
			
			$data = array("title" => "Confirmation");
			$this->load->view("confirmation", $data);
		} else {
			// since this view takes no args, can just go back to it without validating anything
			$this->customerInfo(true);
		}
	}
	
	/**
	 * This function is called by confirmation.php to process the ticket purchase, then display a printable summary.
	 * Only process DB requests from pages OTHER THAN SUMMARY
	 * i.e. If summary is refreshed, do not add another item to the DB
	 */
	function buyTicket () {
		$data = array("title" => "Summary");
		
		if (isset($_SESSION['lastView']) && $_SESSION['lastView'] != 'summary') {
			
			$this->load->model("airuoft_model");
			$result = $this->airuoft_model->create_ticket($_SESSION['fName'], $_SESSION['lName'], $_SESSION['ccNum'], $_SESSION['expMonth'] . $_SESSION['expYear'], $_SESSION['flightID'], $_SESSION['seatNum']);
			
			
			
			if ($result === 0) {
				$_SESSION['lastView'] = 'summary';
				
				$this->load->view("confirmation", $data);
			} else if ($result === 1) {
				$data = array("errMsg" => "Sorry, someone already reserved the same seat on the same flight. Try selecting a different seat.");
				
				$this->load->view("error", $data);
			} else if ($result > 1) {
				// TODO give more descriptive feedback here
				$data = array("errMsg" => "Unknown DB error");
				
				$this->load->view("error", $data);
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
		// $_SESSION = array("pentest" => $this->penTest);
		$_SESSION = array();
		$this->index();
	}
}

?>
