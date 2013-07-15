<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

/**
 * Return an associative array for everything that's needed for a text input with the given name.
 * @param input_name The name of the input field.
 */
function get_input_array($input_name) {
	return array("name" => $input_name, "id" => $input_name);
}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Landing Page</title>
		<meta charset="UTF-8" />
		
		<style>
			#logo {
				/*border: 1px solid #000;*/
				width: 100px;
			}
		
			input {
				display: block;
			}
		</style>
	</head>
	
	<body>
		<h1>Landing Page</h1>
		
		<div id="logoContainer">
			<img id="logo" src="<?=base_url() ?>/images/blacksheep.jpg" />
		</div>
		
		<div id="searchPanel">
			<?php
				echo form_open('airuoft/searchFlights');
				
				echo form_label('From');
				echo form_input(get_input_array("from"));
				
				echo form_label('To');
				echo form_input(get_input_array("to"));
				
				echo form_label('Date');
				echo form_input(get_input_array("date"));
				
				echo form_submit('search', 'Search Flights');
				echo form_close();
			?>
		</div>
	</body>
</html>