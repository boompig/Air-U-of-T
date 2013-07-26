<?php
	$this->load->model("html_utils");
?>
<div id="navbar-before"></div>
<div id="navbar">
	<?php
		$title = $this->router->fetch_method();
	
		// we need $title to be set
		$titles = array(
			"searchFlights" => "Choose Flight",
			"searchSeats" => "Choose Seat", 
			"customerInfo" => "Passenger Info", 
			"confirmation" => "Confirmation", 
			"buyTicket" => "Summary"
		);
		
		$thisIndex = array_search($title, array_keys($titles));
		$i = 0;
		
		foreach($titles as $k => $v) {
			$arr = array ("class" => array("navItem"));
			
			if ($i < $thisIndex) {
				$arr["class"][] = "past";
			} else if ($i == $thisIndex) {
				$arr["class"][] = "present";
			} else {
				$arr["class"][] = "future";
			} 
			
			echo HTML_Utils::open_div($arr);
			
			$contents = HTML_Utils::span($i + 1,  array("class" => "navIndex"));
			$contents .= HTML_Utils::span($v, array("class" => "navTitle"));
			
			if ($i <= $thisIndex && $title !== "buyTicket") {
				// the /1 passes ID=1 (which is the same as arg=true)
				echo anchor("airuoft/" . $k . "/1", $contents, array());
			} else {
				echo $contents;
			}
			
			echo HTML_Utils::close_div();
			$i += 1;
		}
	?>
</div>
<div id="navbar-after"></div>
