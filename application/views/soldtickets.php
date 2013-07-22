<!DOCTYPE html>

<html>
	<head>
		<title>Sold Tickets | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/admin.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<style>
			input[type=submit] {
				margin: 20px auto;
				
			}
		</style>
		
		<script>
			$(function() {
				$("input[type=submit]").button();
				
				var now = new Date();
				var weekday = Date.dayNames[now.getDay()];
				var month = Date.monthNames[now.getMonth()];
				var h = now.getHours();
				var p;
				
				if (h > 12) {
					p = "PM";
					h -= 12;
				} else if (h == 12) {
					p = "PM"
				} else {
					p = "AM";
				}
				
				var time = h + ":" + now.getMinutes() + " " + p;
								
				$("#timestamp").html("Generated at {0} on {1}, {2} {3}".format(time, weekday, month, now.getDate()));
			});
		</script>
	</head>
	<body>
		<h1>Ticket Sales Summary</h1>
		
		<div id="ticketDetails">
			<?php
			
				$columnMap = array(
					"fName" => "First Name",
					"lName" => "Last Name",
					"ccNum" => "Credit Card Number",
					"ccExpDate" => "Credit Card Expiration Date",
					"seatNum" => "Seat Number",
					"flightDate" => "Flight Date",
				);
				
				$headings = array();
				foreach(array_keys($tickets[0]) as $key) {
					$headings[] = $columnMap[$key];
				}
				
				$this->table->set_heading($headings);
			
				foreach ($tickets as $ticket) {
					$cells = array();
					
					foreach($ticket as $key => $val) {
						$cells[] = array("data" => $val, "class" => $key);
					}
					
					$this->table->add_row($cells);
				}
				
				echo $this->table->generate();
			?>
		</div>
		
		<footer>
			<span id="timestamp"></span>
			<?php
				echo form_open("admin/admin");
				echo form_submit("", "Back");
				echo form_close();
			?>
		</footer>
	</body>
</html>