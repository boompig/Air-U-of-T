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
		<link rel="stylesheet" href="<?=base_url(); ?>/css/navbar.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/error_box.css" />
		<link rel="stylesheet" href="<?=base_url(); ?>/css/seats.css" />
		
		<!-- custom scripts -->
		<script src="<?=base_url(); ?>/js/utils.js"></script>
		
		<script>
			var selected = false;
			
			function seatSelected(seatIndex) {
				$("#seatNum").val(seatIndex);
				
				if (! selected) {
					$("#noSeat").hide();
					$("#seatContainer").show();
					$("input[type=submit]").button({"disabled": false});
					selected = true;
				}
			};
		
			$(function() {
				$("#seatButtons").selectable({
					"filter" : ".available",
					"selected" : function (e, elem) {
						var seatIndex = $(".seatButton").index(elem.selected);
						
						seatSelected(seatIndex);
					}
				});
				
				$("input[type=submit]").button();
				
				// if a seat has previously been chosen, then set that seat
				<?php
					if (isset ($_SESSION['seatNum'])) {
						$i = $_SESSION['seatNum'];
						echo "$('#seatButtons .seat$i').addClass('ui-selected');";
						echo "seatSelected($i);";
					}
				?>
			});
		</script>
		
		<style>
			#seatPanel {
				margin: 30px auto 10px auto;
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
		<?php $this->load->view("header.php"); ?>
		<?php $this->load->view("navbar.php"); ?>
		
		<div id="content">
			<div id="instructionsPanel">
				<h2>Choose a Seat</h2>
				
				<?=HTML_Utils::form_open("airuoft/customerInfo"); ?>
				<div id="seatContainer" style="display: none;">
					<?php
						$data = HTML_Utils::get_input_array("seatNum");
						$data["readonly"] = "readonly";
						$data["required"] = "required";
						$data["pattern"] = "/\d/";
						$data["size"] = 1;
						
						echo form_label("Seat: ", "seatNum");
						echo form_input($data);
					?>
				</div>
				<div id="noSeat">Please select a seat</div>
				<?php
					$attrs = array("name" => "continue", "value" => "Continue", "disabled" => "disabled");
					echo form_submit($attrs);
					echo form_close();
				?>
				
				<?php $this->load->view("error_box.php"); ?>
				
				<div id="selectionHelp" class="ui-state-highlight ui-corner-all">
					<span class="ui-icon ui-icon-info"></span>
					Available seats are white. Occupied seats are yellow. Current seat selection is green.
				</div> <!-- end selection help -->
			</div>
			
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
		</div> <!-- end content -->
	</body>
</html>