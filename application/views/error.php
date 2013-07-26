<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Error | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- favicon -->
		<link rel="icon" type="image/x-icon" href="<?=base_url() ?>/images/airplane-med.png" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error_box.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<script>
			$(function() {
				$("input[type=submit]").button();
			});
		</script>
	</head>
	<body>
		<h1 id="title">Error</h1>
		
		<div id="content">
			<div class="errorBox ui-state-highlight ui-corner-all">
				<span class="ui-icon ui-icon-alert"></span>
				<span><?=$errMsg; ?></span>
			</div>
			
			<?php
				echo HTML_Utils::form_open("airuoft/reset");
				echo form_submit("startOver", "Start Over");
				echo form_close();
			?>
		</div>
	</body>
</html>