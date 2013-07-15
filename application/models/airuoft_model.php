<?php

// TODO for development, create link with FirePHP Console
// dirty hack here to get this to work
require_once("../FirePHPCore/FirePHP.class.php");

/**
 * A general class to access the various DBs and fetch some data from them, for the Air U of T application.
 */
class AirUofT_Model extends CI_Model {
	/**
	 * An associative array mapping campus 'short names' to their IDs in the DB tables.
	 */
	private $campuses = array();
	
	function __construct () {
		parent::__construct();
		
		// TODO for development, create link with FirePHP Console
		$this->logger = FirePHP::getInstance(true);
		
		// to start with, have a mapping of campus names to campus IDs
		foreach (array("UTSG" => "St. George", "UTM" => "Mississauga") as $campus => $campusFullName) {
			$q = "SELECT id FROM campus WHERE name='$campusFullName';";
			$query = $this->db->query($q);
			$id = $query->row()->id;
			$this->campuses[$campus] = intval($id);
		}
	}
	
	/**
	 * Return an array, which is formed by joining the key-value pairs of $array using $glue
	 */
	function a_array_join ($glue, $array) {
		$a2 = array();
		
		foreach($array as $k => $v) {
			$a2[] = $k . "=" . $v;
		}
		
		return $a2;
	}
	
	/**
	 * Construct a query using the given array.
	 * @param $table The table to query
	 * @param $array The associative array mapping columns to values
	 * @param $columns The columns to retrieve from the table. If none specified, return all of them.
	 */
	function construct_query ($table, $array, $columns=null) {
		if ($columns !== null) {
			$colString = "*";
		} else {
			$colString = join(", ", $columns);
		}
		
		$qString = join(" AND ", $this->a_array_join($array));
		
		return "SELECT $colString FROM $table WHERE $qString";
	}
	
	/**
	 * Construct an insert SQL query using the given associative array of column-value pairs.
	 * WARNING: modifies array that it is given
	 * @param $table The table to insert into
	 * @param $arr The associative array
	 */
	function construct_insert ($table, $arr) {
		// quote the strings
		foreach ($arr as $key=>&$value) {
			if (! is_numeric($value)) {
				$value = "'$value'";
			}
		}
		
		$colString = join(",", array_keys($arr));
		$valString = join(",", array_values($arr));
		
		return "INSERT INTO $table ($colString) VALUES ($valString);";
	}
	
	/**
	 * Return an array of Flight objects that fit the search criteria. Only available flights will be returned.
	 * @param $from Departure campus
	 * @param $to Destination campus
	 * @param $date Departure date
	 */
	function get_available_flights ($from, $to, $date) {
		// time to get the campus IDs
		
		$campusFrom = $this->campuses[$from];
		$campusTo = $this->campuses[$to];
		
		$q = "SELECT timetable.leavingfrom AS departureCampus,
		timetable.goingto AS arrivalCampus,
		timetable.time AS departureTime,
		flight.date AS departureDate,
		flight.available AS available 
		FROM timetable INNER JOIN flight ON timetable.id=flight.timetable_id
		WHERE timetable.leavingfrom=$campusFrom AND timetable.goingto=$campusTo AND flight.date='$date' AND flight.available=1;";
		
		$query = $this->db->query($q);
		$i = 0;
		
		foreach ($query->result() as $row) {
			$i++;
			$this->logger->log($row, "query result");	
		}
		
		if ($i == 0) {
			$this->logger->log($q, "No result set");
		}
	}
	
	/**
	 * Fill the flight table for the next 14 days with the relevant information.
	 */
	function fill_flights() {
		$this->db->trans_start();
		$date = new DateTime(date("Y-m-d"));
				
		foreach (range (1, 15) as $i) {
			date_add($date, date_interval_create_from_date_string("1 day"));
			
			foreach (range (1, 8) as $j) {
				$q = $this->construct_insert("flight", array("timetable_id" => $j, "date" => date_format($date, "Y-m-d"), "available" => 1));
				$this->db->query($q);
			}
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->logger->log("Populating flight table failed" ,"");
		}
	}
}
?>