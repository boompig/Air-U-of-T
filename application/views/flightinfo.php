<!DOCTYPE html>

<html>
	<head>
		<title>Flight Selection</title>
		<meta charset="UTF-8" />
		
	</head>
	
	<body>
		<h1>Collect Flight Info</h1>
		
		<form>
			<label for="campus">Campus:</label>
			<select id="campus" name="campus">
				<option value="" disabled="disabled" selected="selected">-- Choose a Campus --</option>
				<option value="UTM">Mississauga</option>
				<option value="UTSG">St. George</option>
			</select>
			
			<label for="date">Date:</label>
			<input type="date" name="date" id="date" />
			
			<input type="submit" value="Search Flights" />
		</form>
		
		<div id="flights">
			<!-- auto-gen content goes here -->
		</div>
	</body>
</html>