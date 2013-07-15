<?php

/**
 * Static class to extend CI's HTML generation.
 */
class HTML_Utils extends CI_Model {
	/**
	 * Return an associative array for everything that's needed for a text input with the given name.
	 * @param input_name The name of the input field.
	 */
	function get_input_array($input_name) {
		return array("name" => $input_name, "id" => $input_name);
	}
	
	/**
	 * Given an associative array, return an options string to add as 4th parameter to input_dropdown.
	 */
	function get_dropdown_options($arr) {
		$a2 = array();
		
		foreach ($arr as $key => $value) {
			$a2[] = $key . "='" . $value . "'";
		}
		
		return join(" ", $a2);
	}
}

?>