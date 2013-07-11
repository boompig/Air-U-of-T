<!DOCTYPE html>

<html>
	<head>
		<title>Billing</title>
		<meta charset="UTF-8" />
		
	</head>
	
	<body>
		<h1>Billing</h1>
		
		<form>
			<div>
				<label for="firstName">First Name:</label>
				<input type="text" id="firstName" name="firstName" />
				
				<label for="lastName">Last Name:</label>
				<input type="text" id="lastName" name="lastName" />
			</div>
			
			<div>
				<label for="ccNumber">Credit Card Number:</label>
				<input type="text" id="ccNumber" name="ccNumber" />
				
				<label for="ccExpiry">Expiry Date:</label>
				<input type="text" id="ccExpiry" name="ccExpiry" />
			</div>
			
			<input type="submit" value="Bill Me!" />
		</form>
	</body>
</html>