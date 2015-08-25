<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
include("functions.inc.php"); 
include("user_settings.inc.php"); 
if(!isset($_SESSION["admin_rights"]) || $_SESSION["admin_rights"] < 5) send_to_notify("error", 3);
$con = connect_and_select($GLOBALS["db_name"]);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="html/xml; charset=ISO-8859-1"/>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Terminal - ZombieCMS</title>
		<script type="text/javascript" src="lib/js/prototype.js"></script>
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="terminal">
		<div id="top"><img src="images/logo.png" alt="logo" id="logo" /><div id="quickboard"><a href="search.php"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
		<div id="menu"><?php include ("zombie_menu.inc.php"); ?></div></div>
		<div id="main">
			<?php if($GLOBALS["enable_notifications"] == true) include ("notification_system.inc.php"); ?>
			<form method="post" action="ajax/exec.php" enctype="plain/text" id="console">
				<p>
					<textarea cols="175" rows="30" id="query" name="query">&gt;</textarea>
					<input type="button" class="btn" value="kill the zombie (execute)" id="exec_query"/><img src="images/btn_right.png" class="btn_right" alt="btn_right" />
				</p>
			</form>
		</div>
	</body>
</html>
<?php mysql_close($con);?>
