<?php
	$path = "script/input/ip.csv";
	$content = file_get_contents($path);
	$contentArray = explode(";",$content);
	echo "<p class='publicIP'>Public IP: " . $contentArray[1] . " discovered " . $contentArray[0] . "</p>";
?>
