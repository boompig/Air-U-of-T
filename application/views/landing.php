<!DOCTYPE html>

<html>
	<head>
		<title>Landing Page</title>
		<meta charset="UTF-8" />
		
		<style>
			#logo {
				border: 1px solid #000;
			}
		
			input {
				display: block;
			}
		</style>
	</head>
	
	<body>
		<h1>Landing Page</h1>
		
		<div id="logo">
			Logo
		</div>
		
		<div id="searchPanel">
			<?php
				echo form_open('airuoft/searchFlights');
				
				echo form_label('From');
				echo form_input('from');
				
				echo form_label('To');
				echo form_input('to');
				
				echo form_label('Date');
				echo form_input('date');
				
				echo form_submit('search', 'Search Flights');
				echo form_close();
			?>
		</div>
	</body>
</html>