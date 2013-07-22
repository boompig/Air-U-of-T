<?php
	if(validation_errors()) {
		$view = "block";
	} else {
		$view = "none";
	} 
?>

<div style="display:<?=$view ?>;" class="errorBox ui-state-highlight ui-corner-all">
	<p>
		<span class="ui-icon ui-icon-alert"></span>
		<span><?=validation_errors() ?></span>
	</p>
</div>