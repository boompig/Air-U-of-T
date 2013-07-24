<!DOCTYPE html>

<html>
	<head>
		<title>Testing | Assignment 2 | CSC309</title>
		<meta charset="UTF-8" />
		
		<!-- JQuery UI stylesheet -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/excite-bike/jquery-ui.css" />
		
		<!-- Google-hosted libraries -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<!-- JQuery validation plugin -->
		<script src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
		<script src="http://jquery.bassistance.de/validate/additional-methods.js"></script>
		
		<script src="utils.js"></script>
		<script src="form_validate.js"></script>
		
		<style>
			.invalid:not(select) {
				color: red;
			}
			
			input[type=text].invalid, select.invalid {
				box-shadow: 0 0 0.4em red, 0 0 0.3em red;
			}
			
			input[type=text].valid, select.valid {
				box-shadow: 0 0 0.4em green, 0 0 0.3em green;
			}
			
			input [type=submit] {
				display: block;
			}
			
			.looksGood {
				display: none;
			}
			
			.valid + .looksGood {
				display: inline;
			}
			
		</style>
		
		<script>
			
			
			
			$(function() {
				"use strict";
				
				$("input").not("[type=submit]").each(function() {
					var d = $("<div></div>").addClass("looksGood").html("FLOOOF");
					$(this).after(d);
				});
				
				$.validator.setDefaults({
					"errorClass" : "invalid",
					"validClass" : "valid",
					"success" : "valid"
				});
				
				$.validator.addMethod ("validDateFormat", validDateFormat, "Date expected in format YYYY-MM-DD");
				$.validator.addMethod ("validDate", validDate, "Invalid date given");
				$.validator.addMethod ("checkFutureDate", checkFutureDate, "Date must be in the future");
				
				$.validator.addMethod ("validCampus", validCampus, "Campus must be one of UTSG, UTM");
				
				$("form").validate({
					
					"rules" : {
						"date" : {
							"required" : true,
							"validDateFormat" : true,
							"validDate" : true,
							"checkFutureDate" : true
						},
						"to" : {
							"required" : true,
							"validCampus" : true
						}
					}
				});
				
				console.log("here");
			});
		</script>
	</head>
	<body>
		<h1>Testing</h1>
		
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
			<?php
				if (isset($_REQUEST["date"])) {
					echo "<h3> Date: " . $_REQUEST["date"] . " </h3>";
				}
			?>
			
			<div>
				<input type="text" name="date" placeholder="YYYY-MM-DD" />
			</div>
			
			<div>
				<select name="to">
					<option value="" selected="selected">Garbage</option>
					<option value="moon">Moon</option>
					<option value="UTM">UTM</option>
					<option value="UTSG">St. George</option>
				</select>
			</div>
			
			<input type="text" name="optional" />
			
			<input type="submit" value="submit"/>
		</form>
	</body>
</html>

