<?php

/**
 * A wrapper object for a user's ticket in memory.
 */
class Ticket extends CI_Model {
	
	private $flightDate;
	private $seatNum;
	private $firstName;
	private $lastName;
	private $ccNumber;
	private $ccExpiryDate;
	
	/**
	 * @param $date Departure date
	 * @param $seatNum The seat number
	 * @param $firstName
	 * @param $lastName
	 * @param $ccNumber The credit card number
	 * @param $ccExpiryDate The credit card expiry date
	 */
	function __construct ($date, $seatNum, $firstName, $lastName, $ccNumber, $ccExpiryDate) {
		// parent::__construct();
		
		$this->flightDate = $date;
		$this->seatNum = $seatNum;
		
		$this->fName = $firstName;
		$this->lName = $lastName;
		
		$this->ccNum = $ccNumber;
		$this->expDate = $ccExpiryDate;
		
	}
}

?>