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
	 * Convert an associative array of attributes to a string.
	 * So given something like array('x' => 'y', 'z' => 'y'), return "x='y' z='y'"
	 */
	function attr_array_to_str ($arr) {
		$a2 = array();
		
		foreach ($arr as $key => $value) {
			if ($key == "class" && is_array($value)) {
				$a2[] = "class='" . implode(" ", $value) . "'";
			} else {
				$a2[] = "$key='$value'";
			}
		}
		
		return join(" ", $a2);
	}
	
	/**
	 * Given an associative array, return an options string to add as 4th parameter to input_dropdown.
	 * The options string simply glues together the key-value pairs of the associative array, and glues the array together with spaces.
	 * Add an HTML5 'required' attribute as well.
	 * @param $arr The associative array
	 */
	function get_dropdown_options($arr) {
		$arr['required'] = 'required';
		return HTML_Utils::attr_array_to_str($arr);
	}
	
	/**
	 * Surround the given item with the given tag.
	 */
	function surround($item, $tag) {
		return "<$tag>$item</$tag>";
	}
	
	/**
	 * Improved version of CI's li method.
	 * @param $item Name of the list item
	 * @param $attrs Associative list of attributes
	 */
	function li($item, $attrs) {
		// parameter is an associative array of attributes for the li
		$listAttrs = HTML_Utils::attr_array_to_str($attrs);
		return "<li $listAttrs>$item</li>";
	}
	
	function open_div ($arr) {
		$attrs = HTML_Utils::attr_array_to_str($arr);
		return "<div $attrs>";
	}
	
	function close_div() {
		return "</div>";
	}
	
	function span ($contents, $arr) {
		$attrs = HTML_Utils::attr_array_to_str($arr);
		return "<span $attrs>$contents</span>";
	}
}

?>