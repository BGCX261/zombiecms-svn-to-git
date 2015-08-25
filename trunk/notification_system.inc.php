<?php 
if(isset($_GET['ERROR_CODE']))
	echo "<div id=\"bad_notice\"><img src='images/bad_notice.png' alt='bad_notice' class='notification_icon' /><p>",$GLOBALS["errors"][$_GET['ERROR_CODE']],"</p></div>";

if(isset($_GET["SUCCESS_CODE"]))
	echo "<div id=\"good_notice\"><img src='images/good_notice.png' alt='good_notice' class='notification_icon' /><p>", $GLOBALS["success"][$_GET['SUCCESS_CODE']], "</p></div>";
?>