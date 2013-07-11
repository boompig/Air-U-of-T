<?php

class Helloworld_model extends CI_Model {
	function __construct () {
		parent::__construct();
	}

	function getData() {
		$query = $this->db->get('data');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			// show_error('Database is empty!);'
		}
	}
}
?>
