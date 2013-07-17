<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$this->load->model("html_utils");
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Seat Selection | Air U of T</title>
		<meta charset="UTF-8" />
		
		<!-- Google-hosted JQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		
		<!-- Google-hosted JQuery UI -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery UI theme -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
		
		<!-- custom style -->
		<link rel="stylesheet" href="<?=base_url(); ?>/css/style.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/seats.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<script>
			$(function() {
				$("#seatButtons").selectable({
					"selected" : function (e, elem) {
						var seatIndex = $(".seatButton").index(elem.selected);
						$("#seat").val(seatIndex);
					}
				});
				
				$("input[type=submit]").button();
			});
		</script>
		
		<style>
			#seatPanel {
				margin: 50px auto 0 auto;
				text-align: center;
				
				background-image: url("<?=base_url(); ?>/images/plane_hull_lined.png");
				background-size: 100%;
				background-repeat: no-repeat;
				
				height: 460px;
				width: 200px;
				
				/* don't need this except to override default */
				position: relative;
			}
		</style>
	</head>
	
	<body>
		<div id="content">
			<h1>Choose a seat</h1>
			
			<div id="seatPanel">
				<ol id="seatButtons">
					<?php
						foreach($seats as $seat) {
							$classes = array("seatButton", "seat$seat");
							
							if (key_exists($seat, $occupied)) {
								$classes[] = "occupied";
							} else {
								$classes[] = "ui-widget-content";
								$classes[] = "available";
							}
							
							echo HTML_Utils::li("", array("class" => join(" ", $classes)));
						}
					?>
				</ol>
			</div> <!-- end seat panel -->
			
			<?=form_open("airuoft/customerInfo"); ?>
				<input type="hidden" name="seat" id="seat" value="" required="required" />
			<?php
				echo form_submit("continue", "Continue");
				echo form_close();
			?>
			
			<div id="selectionHelp" class="ui-state-highlight ui-corner-all">
				<span class="ui-icon ui-icon-info"></span>
				Available seats are white. Occupied seats are yellow. Current seat selection is green.
			</div> <!-- end selection help -->
		</div> <!-- end content -->
	</body>
</html>