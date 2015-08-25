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
		<title>Roots - ZombieCMS</title>
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
	<body id="roots">
		<div id="top"><img src="images/logo.png" alt="logo" id="logo" /><div id="quickboard"><a href="#search" id="show_search"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
		<div id="menu"><?php include ("zombie_menu.inc.php"); ?></div></div>
		<div id="main">
			<?php include("searchbox.inc.php"); ?>
			<?php if($GLOBALS["enable_notifications"] == true) include ("notification_system.inc.php"); ?>
			<div class="col">
				<div class="listview"> <!-- roots -->
					<img src="images/icons/roots.png" alt="roots"/> <strong>Top Roots</strong>
					<table cellspacing="0"><tr class="light_blue"><th>Name</th><th>Rights</th><th>PM</th><th>Del</th></tr>
					<?php
					$roots = mysql_query("SELECT name, rights, id FROM zombie_roots ORDER BY rights DESC LIMIT 10");
					$i = 1;
					while($row = mysql_fetch_array($roots)) {
						if($i % 2 == 0) echo "<tr class=\"light_blue\">";
						else echo "<tr class=\"dark_blue\">";
						echo "<td style=\"width: 60%\"><a href=\"roots.php?id=".$row["id"]."\">",$row["name"],"</a></td><td style=\"width: 20%\">",$row["rights"],"/5</td><td style=\"width: 10%\"><img src=\"images/icons/pm.png\" alt=\"pm\" /></td><td style=\"width: 10%\">"; if($row['rights'] < 5 || $row["id"] == 1) { echo "<a href=\"engine.php?action=del_root&amp;id=".$row["id"]."\"><img src=\"images/icons/inline/trash.png\" alt=\"trash\"/></a>";} echo "</td></tr>";
						$i++; }
					?>
					</table>
				</div>
			</div>
			<div class="col">
				<h2>Root Management</h2>
				<ul class="tabs" title="blue">
					<?php if(isset($_GET["id"])) { ?><li class="tab blue"><a href="#empty" id="mod_tab"><img src="images/icons/inline/modify.png" alt="modify"/>Modify</a></li><?php } ?>
					<li class="tab blue"><a href="#empty" id="add_tab"><img src="images/icons/inline/add.png" alt="add"/>Add</a></li>
					<li class="tab blue"><a href="#empty" id="search_tab"><img src="images/icons/inline/find.png" alt="search"/>Search</a></li>
				</ul>
				<?php if(isset($_GET["id"])) { ?><div class="tabbed_content back_blue" id="content_mod_tab">
					<form method="post" class="root_form" action="engine.php?action=mod_root&amp;id=<?php echo $_GET["id"]; ?>" enctype="plain/text">
						<h2>Modify Root-User</h2>
						<?php $root = get_entities("zombie_roots", $_GET["id"], array("name", "mail", "rights"));?>
						<p>Name: <strong><?php echo $root["name"]; ?></strong><br />
						Mail: <strong><?php echo $root["mail"]; ?></strong></p>
						<p>
							<label for="mod_rights">Rights</label><select name="mod_rights" id="mod_rights"><option value="<?php echo $root["rights"]; ?>"><?php echo $root["rights"];?></option>
							<option>----------------------------</option>
							<?php for($i = 1; $i<=5; $i++) echo "<option value=\"$i\">$i</option>"; ?></select>
							<input type="submit" class="btn" value="Redeploy" id="mod_root_btn"/><img src="images/btn_right.png" class="btn_right" alt="btn_right"/>
						</p>
					</form>
				</div><?php } ?>
				<div class="tabbed_content back_blue" id="content_add_tab">
					<form method="post" class="root_form" action="engine.php?action=add_root" enctype="plain/text" id="add_root_form">
					<h2>Add a root-user</h2>
					<p>
						<label for="add_name">Username</label> <input type="text" id="add_name" name="add_name" />
						<label for="add_pass">Password</label> <input type="password" id="add_pass" name="add_pass" />
						<label for="add_repass">Re-type Password</label> <input type="password" id="add_repass" name="add_repass" />
						<label for="add_mail">E-Mail</label> <input type="text" id="add_mail" name="add_mail" />
						<label for="add_rights">Rights</label> <select id="add_rights" name="add_rights">
						<?php for($i = 1; $i<=5; $i++) echo "<option value=\"$i\">$i</option>"; ?></select>
						<input type="button" value="Call for reinforcements" class="btn" id="add_root_btn"/><img src="images/btn_right.png" class="btn_right" alt="btn_right"/>
					</p>
					</form>
				</div>
				<div class="tabbed_content back_blue" id="content_search_tab">
					<form method="get" class="root_form" action="search.php?type=root" enctype="plain/text" id="search_root_form">
						<h2>Find a Root-User</h2>
						<p>
							<label for="search_query">Name or Mail</label> <input type="text" name="search_query" id="search_query" />
							<input type="button" value="He's gotta be there somewhere" class="btn" id="search_root_btn" /><img src="images/btn_right.png" class="btn_right" alt="btn_right" />
						</p>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<?php mysql_close($con);?>
