<header>
	<?php
		$isAdmin = $this->router->fetch_class() === "admin";
		
		if ($isAdmin) {
			$img = base_url() . "/images/god.jpg";
			$imgClass = "admin";
			$subtitle = "Admin Portal";
		} else {
			$img = base_url() . "/images/airplane-med.png";
			$imgClass = "passenger";
		}
	
		$contents = '<img src="' . $img . '" class="' . $imgClass . '" />';
		echo anchor("airuoft/index", $contents);
	?>
	<span class="titleText">
		<h1>Air U of T</h1>
		<?php
			if (isset($subtitle)) {
				echo "<h2>" . $subtitle . "</h2>";
			}
		?>
	</span>
</header>