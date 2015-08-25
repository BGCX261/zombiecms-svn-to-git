<?php ob_start(); ?>
<?php
	session_start();
	include("functions.inc.php");
	include("user_settings.inc.php");
	if($_GET["action"] == "install") /*Doesn't need log in*/
	{
		if(install_zombie())
		{
			$con = connect_and_select($GLOBALS["db"]);
			add_to_log("admin", "installed zombieCMS");
			mysql_close($con);
			header("Location: index.php?SUCCESS_CODE=1");
		}
		else header("Location: index.php?ERROR_CODE=1");;
	}
	else if($_SESSION["logged"] == true) /*Logged in*/
	{
		$con = connect_and_select($GLOBALS["db_name"]);
		switch($_GET["action"])
		{
			case "mod_prefs":
				$id = $_SESSION["id"];
				$enable_logging = $_POST["enable_logging"];
				$enable_notifications = $_POST["enable_notifications"];
				$enable_contextmenu = $_POST["enable_contextmenu"];
				$menu_type = $_POST["menu_type"];
				$theme = $_POST["theme"];
				$mod = mysql_query("UPDATE zombie_settings SET enable_logging='$enable_logging', enable_contextmenu='$enable_contextmenu', enable_notifications='$enable_notifications', menu_type='$menu_type', theme='$theme' WHERE user_id='$id';");
				if($enable_logging == 1) add_to_log($_SESSION["admin_user"], "modified preferences");
				if($mod) send_to_notify("success", 15);
				else send_to_notify("error",17);
			break;
			case "mod_settings":
				$id = $_SESSION["id"];
				$mail = $_POST["mail"];
				$pwd = $_POST["pass"];
				$repwd = $_POST["re_pass"];
				if($pwd != $repwd && $pwd != "" && $repwd != "")
					send_to_notify("error", 9);
				else if($pwd == $repwd && $pwd != "" && $repwd != "")
					$update = mysql_query("UPDATE zombie_roots SET mail = '$mail', pwd = MD5('$pwd') WHERE id = '$id'");
				else if($pwd == "" && $repwd == "")
					$update = mysql_query("UPDATE zombie_roots SET mail = '$mail' WHERE id = '$id'");
				if($update) {
					if($GLOBALS["enable_logging"] == true) add_to_log($_SESSION["admin_user"], "modified settings");
					send_to_notify("success", 18);
				}
				else send_to_notify("error", 18);
			break;
			case "add_root":
				$name = $_POST["add_name"];
				$pass = $_POST["add_pass"];
				$mail = $_POST["add_mail"];
				$rights = $_POST["add_rights"];
				$add_root = Root::add($name, $pass, $mail, $rights);
				if($add_root == 1)
				{
					if($GLOBALS["enable_logging"] == true) add_to_log($_SESSION["admin_user"], "Created Root $name");
					send_to_notify("success", 17);
				}
				else if($add_root == -1) send_to_notify("error", 11); 
				else send_to_notify("error", 15);
			break;
			case "del_root":
				$del = Root::delete($_GET["id"]);
				if($del == 1)
				{
					if($GLOBALS["enable_logging"] == true) add_to_log($_SESSION["admin_user"], "Deleted Root ".$_GET["id"]);
					send_to_notify("success", 12);
				}
				else if($del == -1) send_to_notify("error", 3);
				else send_to_notify("error", 19);
			break;
			case "add_article":
			case "mod_article":
				//data
				$data = array("head" => $_POST["head"], "subh" => $_POST["subh"], "body" => $_POST["body"]);
				//meta-data
				$allow_comments = $_POST["allow_comments"] == "on" ? 1 : 0;
				$published = $_POST["published"] == "on" ? 1 : 0;
				$meta_data = array("tags" => $_POST["tags"], "author" => $_POST["author"], "allow_comments" => $allow_comments, "published" => $published);
				$blog = new Blog();
				if($_GET["action"] == "add_article") {
					$id = $blog->add($data);
					if($id) $add_meta = $blog->add_meta($id, $meta_data);
					//send ->
					if($add_meta) {
						if($GLOBALS["enable_logging"] == true) add_to_log($_SESSION["admin_user"], "Created Article $head");
						send_to_notify("success", 2);
					}
					else send_to_notify("error", 20);
				}
				else if($_GET["action"] == "mod_article") {
					$id = $_GET["id"];
					$mod = $blog->modify($id, $data);
					$mod_meta = $blog->modify_meta($id, $meta_data);
					//send ->
					if($mod && $mod_meta) send_to_notify("success", 6);
					else send_to_notify("error", 21);
				}
			break;
			case "del_article":
				$blog = new Blog();
				$id = $_GET["id"];
				if($blog->del($id)) send_to_notify("success",10);
				else send_to_notify("error",22);
			break;
			default:
				send_to_notify("error", 14);
			break;
		}
		mysql_close($con);
	}
	else echo "permission denied"; /*Not logged in*/
?>
