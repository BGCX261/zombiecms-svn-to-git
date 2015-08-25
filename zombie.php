<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
include("user_settings.inc.php");
include("functions.inc.php");
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
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="ctrl_panel">
		<div id="top">
			<img src="images/logo.png" alt="logo" id="logo" />
			<div id="quickboard"><a href="search.php"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
			<div id="menu">
				<?php include ("zombie_menu.inc.php"); ?>
			</div>
		</div>
		<div id="main">
			<h2>Welcome <?php echo $_SESSION["admin_user"]?></h2> <span><?php echo $GLOBALS["welcome_phrase"][mt_rand(1,sizeof($GLOBALS["welcome_phrase"]))]; ?></span>
			<?php if($GLOBALS["enable_notifications"] == true)
			{
				include ("notification_system.inc.php"); 
				$admin_account = mysql_fetch_array(mysql_query("select name, pwd from zombie_roots where id=1"));
				if($admin_account["name"] == "admin" && $admin_account["pwd"] == md5("root") && $_SESSION["admin_rights"] == 5) echo "<div id=\"bad_notice\"><img src='images/bad_notice.png' alt='bad_notice' class='notification_icon' /><p>It looks like you still have your default root-account enabled. For security reasons, we suggest you <a href=\"roots.php?id=1\" class=\"white\">delete that one</a> and <a href=\"roots.php\" class=\"white\">create a new one</a></p></div>";
				unset($admin_account);
			}
			?>
			<div class="col3">
			<div class="listview"> <!-- roots -->
				<img src="images/icons/roots.png" alt="roots"/> <strong>Root Users</strong>
				<table cellspacing="0"><tr class="light_blue"><th>Name</th><th>Rights</th><th>PM</th></tr>
				<?php
				$roots = mysql_query("SELECT name, rights, id FROM zombie_roots ORDER BY rights DESC LIMIT 10");
				$i = 1;
				while($row = mysql_fetch_array($roots))
				{
					if($i % 2 == 0) echo "<tr class=\"light_blue\">";
					else echo "<tr class=\"dark_blue\">";
					echo "<td style=\"width: 70%\">",$row["name"],"</td><td style=\"width: 20%\">",$row["rights"],"/5</td><td style=\"width: 10%\"><img src=\"images/icons/pm.png\" alt=\"pm\" /></td></tr>";
					$i++;
				}
				?>
				</table>
				<a href="roots.php">More..</a>
			</div>
			<div class="listview"> <!--  latest users -->
				<img src="images/icons/users.png" alt="users" /> <strong>Latest Registered Users</strong>
				<table cellspacing="0"><tr class="light_blue"><th>Name</th><th>Country</th><th>Go</th></tr>
				<?php
				$articles = mysql_query("SELECT username AS name, id FROM users ORDER BY id DESC LIMIT 5");
				$i = 1;
				while($row = mysql_fetch_array($articles))
				{
					if($i % 2 == 0) echo "<tr class=\"light_blue\">";
					else echo "<tr class=\"dark_blue\">";
					echo "<td style=\"width: 60%\">",substr($row["name"],0,25),"</td><td style=\"width: 30%\">",$row["id"],"</td><td style=\"width: 10%\"><img src=\"images/icons/go.png\" alt=\"pm\" /></td></tr>";
					$i++;
				}
				?>
				</table>
				<?php echo $i % 2 == 0 ? "<div class=\"light_blue\">" : "<div class=\"dark_blue\">"; ?>
				<form method="get" action="search.php" enctype="plain/text">
					<p>
						<label for="user_query">Search Users</label>
						<input type="hidden" name="type" value="users" />
						<input type="text" name="query" id="user_query" class="search_box"/>
						<input type="submit" value="Braaains!" class="tiny_btn" />
					</p>
				</form>
				</div>
				<a href="#empty">More..</a>
			</div>
			</div>
			<div class="col3">
				<div class="listview"> <!--  blog -->
					<img src="images/icons/blog.png" alt="blog" /> <strong>Latest Articles</strong>
					<table cellspacing="0"><tr class="light_green"><th>Name</th><th>Go</th></tr>
					<?php
					$articles = mysql_query("(SELECT head, id FROM articles) ORDER BY id DESC LIMIT 5");
					$i = 1;
					while($row = mysql_fetch_array($articles))
					{
						if($i % 2 == 0) echo "<tr class=\"light_green\">";
						else echo "<tr class=\"dark_green\">";
						echo "<td style=\"width: 90%\">",substr($row["head"],0,40),"</td>";
						echo "<td style=\"width: 10%\"><a href=\"blog.php?p=mod&amp;id=",$row["id"],"\"><img src=\"images/icons/go.png\" alt=\"Go\" /></a></td></tr>";
						$i++;
					}
					?>
					</table>
					<?php echo $i % 2 == 0 ? "<div class=\"light_green\">" : "<div class=\"dark_green\">"; ?>
					<form method="get" action="search.php" enctype="plain/text">
						<p>
							<label for="blog_query">Search Articles</label>
							<input type="hidden" name="type" value="blog" />
							<input type="text" name="query" id="blog_query" class="search_box"/>
							<input type="submit" value="Gffff!" class="tiny_btn" />
						</p>
					</form>
					</div>
					<a href="#empty">More..</a>
				</div>
				<div class="listview"> <!--  latest  comments -->
					<img src="images/icons/blog.png" alt="users" /> <strong>Latest Blog Comments</strong>
					<table cellspacing="0"><tr class="light_green"><th>Name</th><th>Message</th><th>Go</th></tr>
					<?php
					$articles = mysql_query("SELECT author, comment FROM comments ORDER BY id DESC LIMIT 10");
					$i = 1;
					while($row = mysql_fetch_array($articles))
					{
						if($i % 2 == 0) echo "<tr class=\"light_green\">";
						else echo "<tr class=\"dark_green\">";
						echo "<td style=\"width: 20%\">",$row["author"],"</td><td style=\"width: 70%\">",substr($row["comment"],0, 25),"</td><td style=\"width: 10%\"><img src=\"images/icons/go.png\" alt=\"pm\" /></td></tr>";
						$i++;
					}
					?>
					</table>
					<?php echo $i % 2 == 0 ? "<div class=\"light_green\">" : "<div class=\"dark_green\">"; ?>
					<form method="get" action="search.php" enctype="plain/text">
						<p>
							<label for="comment_query">Search Blog Comments</label>
							<input type="hidden" name="type" value="comments" />
							<input type="text" name="query" id="comment_query" class="search_box"/>
							<input type="submit" value="hmff hmff" class="tiny_btn" />
						</p>
					</form>
					</div>
					<a href="#empty">More..</a>
				</div>
			</div>
			<div class="col3">
				<div class="listview"> <!--  files -->
					<img src="images/icons/files.png" alt="blog" /> <strong>Latest Uploaded Files</strong>
					<table cellspacing="0"><tr class="light_red"><th>Name</th><th>Type</th><th>Go</th></tr>
					<?php
					$articles = mysql_query("SELECT name, type, id FROM files ORDER BY id DESC LIMIT 5");
					$i = 1;
					while($row = mysql_fetch_array($articles))
					{
						if($i % 2 == 0) echo "<tr class=\"light_red\">";
						else echo "<tr class=\"dark_red\">";
						echo "<td style=\"width: 70%\">",substr($row["name"],0,25),"</td><td style=\"width: 20%\">",$row["type"],"</td><td style=\"width: 10%\"><img src=\"images/icons/go.png\" alt=\"pm\" /></td></tr>";
						$i++;
					}
					?>
					</table>
					<?php echo $i % 2 == 0 ? "<div class=\"light_red\">" : "<div class=\"dark_red\">"; ?>
					<form method="get" action="search.php" enctype="plain/text">
						<p>
							<label for="file_query">Search Uploaded Files</label>
							<input type="hidden" name="type" value="files" />
							<input type="text" name="query" id="file_query" class="search_box"/>
							<input type="submit" value="Aaaarhhh!" class="tiny_btn" />
						</p>
					</form>
					</div>
					<a href="#empty">More..</a>
				</div>
			</div>
		</div>
	</body>
</html>
<?php mysql_close($con);?>