<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Admin Portal | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<style>
			button, input[type=submit] {
				margin: 10px;
			}
		</style>
		
		<script>
			$(function() {
				// add pretty JQuery UI stuff
				$("button, input[type=submit]").button();
			});
		</script>
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
		
		<a href="soldtickets.php"><button type="button">View sold tickets</button></a>
	</body>
</html>