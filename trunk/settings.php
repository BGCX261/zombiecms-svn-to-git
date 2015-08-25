<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
include("functions.inc.php");
include("user_settings.inc.php");
if(!isset($_SESSION["admin_rights"]) || $_SESSION["admin_rights"] < 1) header("Location: index.php?ERROR_CODE=3");
$con = connect_and_select($GLOBALS["db_name"]);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="html/xml; charset=ISO-8859-1"/>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Control Panel - ZombieCMS</title>
		<script type="text/javascript" src="lib/js/prototype.js"></script>
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects,dragdrop,builder,controls"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<?php if($GLOBALS["enable_contextmenu"]) { ?><script type="text/javascript" src="lib/js/context.js"></script><?php } ?>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="settings">
		<div id="top">
			<img src="images/logo.png" alt="logo" id="logo" />
			<div id="quickboard"><a href="#search" id="show_search"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
			<div id="menu">
				<?php include ("zombie_menu.inc.php"); ?>
			</div>
		</div>
		<div id="main">
		<?php include("searchbox.inc.php"); ?>
		<?php if($GLOBALS["enable_notifications"]) include ("notification_system.inc.php"); ?>
			<div class="col">
				<form method="post" class="settings_form" action="engine.php?action=mod_prefs" enctype="plain/text">
					<h2>Preferences</h2>
					<p>
						<?php $prefs = mysql_fetch_array(mysql_query("SELECT enable_logging, enable_notifications, theme, menu_type, enable_contextmenu FROM zombie_settings WHERE user_id='".$_SESSION['id']."' LIMIT 1")); ?>
						<label for="enable_logging">Enable Logging?</label> <input type="checkbox" value="1" name="enable_logging" <?php if($prefs["enable_logging"]) echo "checked=\"checked\"";?> id="enable_logging" />
						<label for="enable_notifications">Enable Notifications?</label> <input type="checkbox" value="1" <?php if($prefs["enable_notifications"]) echo "checked=\"checked\"";?> name="enable_notifications" id="enable_notifications" />
						<label for="enable_contextmenu">Enable Context-Menu</label><span> (Double Click Menu)</span> <input type="checkbox" value="1" <?php if($prefs["enable_contextmenu"]) echo "checked=\"checked\"";?> name="enable_contextmenu" id="enable_contextmenu" />
						<label for="theme">Theme </label>
						<select name="theme" id="theme">
							<option value="<?php echo $prefs["theme"]; ?>"><?php echo $prefs["theme"]; ?></option>
							<option value="<?php echo $prefs["theme"]; ?>">----------------------------------------------------------</option>
							<option value="default">Default</option>
							<option value="red">Red</option>
						</select>
						<label for="menu_type">Menu Type</label>
						<select name="menu_type" id="menu_type">
							<option value="<?php echo $prefs["menu_type"]; ?>"><?php echo str_replace("-"," &amp; ",$prefs["menu_type"]); ?></option>
							<option value="<?php echo $prefs["menu_type"]; ?>">----------------------------------------------------------</option>
							<option value="icons-text">Icons &amp; Text</option>
							<option value="icons">Icons only</option>
							<option value="text">Text only</option>
						</select>
						<input type="submit" value="Save me! aergghh" class="btn" /><img src="images/btn_right.png" alt="btn_right" class="btn_right" />
					</p>
				</form>
			</div>
			<div class="col">
				<form method="post" action="engine.php?action=mod_settings" class="settings_form" enctype="plain/text">
					<h2>Settings</h2>
					<p>
						<?php $settings = get_entities("zombie_roots", $_SESSION["id"], array("mail", "rights")); ?>
						<label for="mail">E-Mail</label> <input type="text" name="mail" id="mail" value="<?php echo $settings["mail"];?>"/>
						<label for="pass">New Password</label> <span class="form_tip">(fill this out only if you want to change your password)</span> <input type="password" name="pass" id="pass" />
						<label for="re_pass">Re-type New Password</label> <input type="password" name="re_pass" id="re_pass" />
						<input type="submit" value="no, save ME!" class="btn" /><img src="images/btn_right.png" alt="btn_right" class="btn_right" />
					</p>
				</form>
			</div>
		</div>
	</body>
</html>
<?php mysql_close($con);?>
