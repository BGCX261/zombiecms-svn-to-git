<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php include("functions.inc.php"); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="html/xml; charset=ISO-8859-1"/>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Log in - ZombieCMS</title>
		<script type="text/javascript" src="lib/js/prototype.js"></script>
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/default/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/default/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/default/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/default/links.css" />
	</head>
	<body id="login">
		<div id="top"><img src="images/logo.png" alt="logo" id="logo" /></div>
		<div id="main">
			<div id="login_box">
				<form method="post" action="log.php?dir=in" enctype="plain/text">
					<p>
						<input type="text" name="user" id="user" value="Username"/>
						<input type="password" name="pwd" id="pwd" value="Password"/>
					</p>
					<p style="width: 60%">
					<?php
					if(isset($_GET['ERROR_CODE'])) {
						$c = $_GET['ERROR_CODE'];
						echo "<span style=\"color: #f00; font-weight: 700;\">Error: ",$GLOBALS['errors'][$c],"</span>";
					}
					else if(isset($_GET['SUCCESS_CODE'])) {
						$c = $_GET["SUCCESS_CODE"];
						echo "<span style=\"color: #21a713; font-weight: 700;\">Success: ",$GLOBALS['success'][$c],"</span>";
					}
					else if(!connect_and_select($GLOBALS['db_name'])) { ?> 
						It looks like you don't have zombieCMS installed, take a chance, <a href="engine.php?action=install">install it now</a>.
					<?php } ?>
					</p>
					<p><img src="images/btn_right.png" class="btn_right"/><input type="submit" value="Ghhrrf, brains!" class="btn"/></p>
				</form>
			</div>
		</div>
	</body>
</html>
