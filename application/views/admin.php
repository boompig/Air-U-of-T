<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Template Title</title>
		<meta charset="UTF-8" />
		
	</head>
	
	<body>
		<h1>Admin Portal</h1>
		
		<div>
			<button type="button">
				Delete all flight and ticket data
			</button>
		</div>
		
		<div>
			<?php
				echo form_open("airuoft/createFlights");
				echo form_submit("create", "Populate flight table for next 14 days");
				echo form_close();
			?>
		</div>
		
		<a href="soldtickets.php">See all tickets sold</a>
	</body>
</html>