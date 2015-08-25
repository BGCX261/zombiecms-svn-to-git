<?php ob_start(); ?>
<?php
include("functions.inc.php");
$log = $_GET['dir'];
if($log == "in")
{
	$logged = false;
	if(!isset($_POST['user']) || !isset($_POST['pwd'])) header("Location: index.php?ERROR_CODE=8");
	
	$user = strtolower(htmlentities($_POST['user']));
	$pwd = strtolower(md5($_POST['pwd']));
	$con = connect_and_select($GLOBALS['db_name']);
	$ulist = mysql_query("SELECT id, rights, name FROM zombie_roots WHERE name = '$user' AND pwd = '$pwd'");
	if(mysql_num_rows($ulist) == 1)
	{
		$ulist = mysql_fetch_array($ulist);
		$banned = false;
		if(Root::is_banned($ulist["id"])) $banned = true;
		else {
			session_start();
			$_SESSION["id"] = $ulist["id"];
			$_SESSION["admin_rights"] = $ulist['rights'];
			$_SESSION["admin_user"] = $ulist["name"];
			$_SESSION["logged"] = true;
			$logged = true;
			add_to_log($user, "Logged in");
		}
	}
	mysql_close($con);
	if($banned) header("Location: index.php?ERROR_CODE=16");
	else if(!$logged) header("Location: index.php?ERROR_CODE=2");
	else header("Location: zombie.php");
}
else
{
	session_start();
	$con = connect_and_select($GLOBALS['db_name']);
	add_to_log($_SESSION["admin_user"], "Logged out");
	mysql_close($con);
	session_destroy();
	header("Location: index.php");
}
?>
