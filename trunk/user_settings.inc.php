<?php ob_start(); ?>
<?php
	session_start();
	include ("settings.inc.php");
	$con = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pass"]);
	mysql_select_db($GLOBALS["db_name"]);
	$id = $_SESSION["id"];
	$settings = mysql_fetch_array(mysql_query("SELECT * FROM zombie_settings WHERE user_id = '$id' LIMIT 1"));
	$enable_logging = $settings["enable_logging"];
	$enable_notifications = $settings["enable_notifications"];
	$enable_contextmenu = $settings["enable_contextmenu"];
	$menu_type = $settings["menu_type"];
	$theme = $settings["theme"];
	mysql_close($con);
	unset($settings, $id, $con);
	
?>