<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Admin Portal | Air U of T</title>
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
		<link rel="stylesheet" href="<?=base_url(); ?>/css/admin.css" />
		
		<script>
			$(function() {
				// add pretty JQuery UI stuff
				$("button, input[type=submit]").button();
				
				$(document).tooltip();
				
				// create initial dialog
				$("#dialog-confirm-delete").dialog({
					autoOpen: false,
					resizable: false,
					width: 400,
					modal: true
				});
				
				$(".deleteButton").click(function(e) {
					// need this so form doesn't submit right away
					e.preventDefault();
					
					// unfortunately have to put this part here, or else doesn't work
					$("#dialog-confirm-delete").dialog({
						buttons: {
							"Delete Everything": function() {
								$("#deleteForm").submit();
							},
							"Cancel": function() {
								$(this).dialog("close");
							}
						}
					});
					
					$("#dialog-confirm-delete").dialog("open");
				});
			});
		</script>
	</head>
	
	<body>
		<h1 id="title">Air U of T Admin Portal</h1>
		
		<div id="content">
			<?php
				echo form_open("admin/createFlights");
				echo form_submit("create", "Populate flight table for next 14 days");
				echo form_close();
			?>
			
			<?php
				echo form_open("admin/getTickets");
				echo form_submit("tickets", "View sold tickets");
				echo form_close();
			?>
			
			<?php
				echo form_open("admin/deleteAll", array("id" => "deleteForm"));
				echo form_submit("delete", "Delete all flight and ticket data", "class='deleteButton'");
				echo form_close();
			?>
			
			<div id="dialog-confirm-delete" title="Delete All Data?">
				<p><span class="ui-icon ui-icon-alert"><!-- icon --></span>
					Are you sure you want to delete all flight and ticket data?
				</p>
			</div>
			
			<div id="confirmation" class="ui-state-highlight ui-corner-all" style="display: <?php if (isset($result)) echo "block"; else echo "none";?>;">
				<p>
					<span class="ui-icon ui-icon-info"></span>
					<?php
						if (isset($result)) {
							echo $result["msg"];
						}
					?>
				</p>
			</div> <!-- end confirmation -->
			
			<div id="bottom">
				<?php
					$contents = HTML_Utils::span("", array ("class" => array("ui-icon", "ui-icon-unlocked")));
					echo anchor("airuoft/index", $contents, array("title" => "customer portal"));
				?>
			</div>
		</div> <!-- end content -->
	</body>
</html>