<?php
	include("settings.inc.php");
	function connect_and_select($db)
	{
		$con = mysql_connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_pass"]);
		$select = mysql_select_db($db);
		if(!$select) { mysql_close($con); return false; }
		return $con;
	}
	function send_to_notify($note, $id)
	{
		$ref = $_SERVER["HTTP_REFERER"]; //get string
		$c = ereg("\?", $ref) ? "&" : "?"; //check previous gets
		
		$ref = str_replace(array("SUCCESS_CODE=", "ERROR_CODE="), "", $ref); //handle chunks
		$ref = ereg_replace("&([0-9])+", "", $ref); //handle leftovers
		
		if($note == "error") header("Location: $ref".$c."ERROR_CODE=$id"); //relocate
		else header("Location: $ref".$c."SUCCESS_CODE=$id");
	}
	function add_to_log($user, $event)
	{
		$now = date("Y-m-d H:i:s");
		$log = mysql_query("INSERT INTO zombie_log (id, root_user, event, time)
				   VALUES('', '$user', '$event', '$now');");
	}
	function get_entities($table, $id, $entities)
	{
		$cols = "";
		for($i = 0; $i<sizeof($entities); $i++)
			$cols .= $entities[$i].",";
		$cols = substr($cols, 0,strlen($cols)-1);
		$query = mysql_fetch_array(mysql_query("SELECT $cols FROM $table WHERE id = '$id'"));
		return $query;
	}
	function install_zombie()
	{
		$bSuccess = true;
		$con = mysql_connect($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass']); //the global variables are found in settings.php
		if(!$con) $bSuccess = false;
		$createDB = mysql_query("CREATE DATABASE ".$GLOBALS['db_name']);
		if(!$createDB) $bSuccess = false;
		$selectDB = mysql_select_db($GLOBALS['db_name'], $con);
		if(!$selectDB) $bSuccess = false;
		$create_roots = mysql_query("CREATE TABLE zombie_roots (id INT NOT NULL AUTO_INCREMENT, name TEXT NOT NULL ,
						pwd TEXT NOT NULL , mail TEXT NOT NULL , rights INT NOT NULL ,
						groups TEXT NOT NULL, INDEX ( id ) ) ENGINE = MYISAM ");
		$add_def = mysql_query("INSERT INTO zombie_roots (id, name, pwd, mail, rights)
						VALUES ('', 'admin', MD5('root'), 'admin@mysite.com', 5);");
		$create_articles = mysql_query("CREATE TABLE articles ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL , head VARCHAR( 255 ) NOT NULL ,
						subh VARCHAR( 255 ) NOT NULL ,
						body TEXT NOT NULL , INDEX ( id ) ) ENGINE = MYISAM ;");
		$articles_meta = mysql_query(" CREATE TABLE articles_meta (`article_id` INT NOT NULL ,author VARCHAR ( 255 ) NOT NULL,`allow_comments` VARBINARY (1) NOT NULL DEFAULT '1',
						`tags` VARCHAR( 1023 ) NOT NULL ,`groups` VARCHAR( 1024 ) NOT NULL DEFAULT 'all',
						published VARBINARY( 1 ) NOT NULL DEFAULT '1', `rating` INT UNSIGNED NOT NULL DEFAULT '0',
						`date_added` DATETIME NOT NULL , `date_modified` DATETIME NOT NULL ,INDEX ( `article_id` )) ENGINE = MYISAM ");
		$articles_comments = mysql_query(" CREATE TABLE articles_comments (`article_id` INT NOT NULL ,
						`author` TINYINT( 255 ) NOT NULL ,`date` DATETIME NOT NULL ,
						`body` TEXT NOT NULL ,`mail` VARCHAR( 255 ) NOT NULL ,`url` VARCHAR( 255 ) NOT NULL ,
						`rating` INT NOT NULL DEFAULT '0', INDEX ( `article_id` )) ENGINE = MYISAM ");
		$articles_stats = mysql_query(" CREATE TABLE articles_stats (`article_id` INT NOT NULL ,
						`views` INT NOT NULL ,`ranking` INT NOT NULL ,`referrers` TEXT NOT NULL ,
						INDEX ( `article_id` )) ENGINE = MYISAM ");
		$create_users = mysql_query("CREATE TABLE users ( id INT NOT NULL AUTO_INCREMENT, username VARCHAR (40) NOT NULL, 
						password VARCHAR (32) NOT NULL, name_first VARCHAR(20) NOT NULL, name_last VARCHAR(40) NOT NULL, INDEX ( id ) ) ENGINE = MYISAM ;");
		$users_settings = mysql_query("CREATE TABLE users_settings (user_id INT NOT NULL, mail TEXT NOT NULL, theme VARCHAR (255) NOT NULL, 
						show_email VARBINARY(1) NOT NULL DEFAULT '0', allow_3rdparty_mail VARBINARY(1) NOT NULL DEFAULT 0,
						allow_admin_mail VARBINARY (1) NOT NULL DEFAULT 0, login_state VARCHAR (255) NOT NULL DEFAULT 'online') ENGINE = MYISAM;");
		$users_friends = mysql_query("CREATE TABLE users_friends (user_id INT NOT NULL, friends TEXT, blocked TEXT) ENGINE = MYISAM;");
		$users_social = mysql_query("CREATE TABLE users_social (user_id INT NOT NULL, im_msn VARCHAR(255), im_yahoo VARCHAR(255), im_gtalk VARCHAR(255), im_skype VARCHAR(255), im_irc VARCHAR(255),
						im_icq VARCHAR(255), twitter VARCHAR(255), facebook VARCHAR(255), myspace VARCHAR(255), blogger VARCHAR(255), presentation TEXT NOT NULL, groups TEXT NOT NULL) ENGINE = MYISAM;");
		$create_menu = mysql_query("CREATE TABLE `menu` ( `id` INT NOT NULL AUTO_INCREMENT , `text` VARCHAR( 100 ) NOT NULL ,
						`link` VARCHAR( 100 ) NOT NULL ,`menu_sort` INT NOT NULL ,
						`childlevel` INT NOT NULL DEFAULT '0' ,`parent_id` INT NOT NULL DEFAULT '0' ,
						`has_children` TINYINT(1) NOT NULL DEFAULT '0', htmlid TEXT NOT NULL,
						htmlclass TEXT NOT NULL, INDEX ( `id` )) ENGINE = MYISAM ;");
		$create_groups = mysql_query("CREATE TABLE groups ( id INT NOT NULL AUTO_INCREMENT, name VARCHAR ( 50 ) NOT NULL, 
						members TEXT NOT NULL, date_created VARCHAR ( 50 ) NOT NULL,
						memeber_count INT NOT NULL, leader VARCHAR ( 50 ) NOT NULL, staff TEXT NOT NULL,
						join_method TEXT NOT NULL, description TEXT NOT NULL, INDEX ( id ) ) ENGINE = MYISAM ;");
		$create_files = mysql_query("CREATE TABLE files ( id INT NOT NULL AUTO_INCREMENT , user_id INT NOT NULL ,
						name TEXT NOT NULL , type TEXT NOT NULL , size VARCHAR ( 30 ) NOT NULL,
						description TEXT NOT NULL ,  for_groups TEXT NOT NULL, path TEXT NOT NULL , INDEX ( id ) );");
		$create_log = mysql_query("CREATE TABLE zombie_log (id INT NOT NULL AUTO_INCREMENT, root_user VARCHAR(50) NOT NULL, 
					  event TEXT NOT NULL, time DATETIME NOT NULL, INDEX(id));");
		$create_comments = mysql_query("CREATE TABLE `comments` (`id` BIGINT NOT NULL AUTO_INCREMENT ,
						`article_id` INT NOT NULL , `head` VARCHAR( 255 ) NOT NULL , `author` VARCHAR( 255 ) NOT NULL ,
						`comment` TEXT NOT NULL , `rating` TINYINT NOT NULL , PRIMARY KEY ( `id` ) ,
						INDEX ( `id` )) ENGINE = MYISAM ");
		$create_settings = mysql_query(" CREATE TABLE `zombie_settings` (`id` INT NOT NULL AUTO_INCREMENT ,
						`user_id` INT NOT NULL ,`enable_logging` VARBINARY( 1 ) NOT NULL DEFAULT '1',
						`enable_notifications` VARBINARY( 1 ) NOT NULL DEFAULT '1',`enable_contextmenu` VARBINARY( 1 ) NOT NULL DEFAULT '1',
						`menu_type` VARCHAR( 10 ) NOT NULL DEFAULT 'icons-text', `theme` VARCHAR( 7 ) NOT NULL DEFAULT 'default',
						PRIMARY KEY ( `id` ) ,INDEX ( `id` )) ENGINE = MYISAM ;");
		$add_settings = mysql_query("INSERT INTO `zombie`.`zombie_settings` (`id` ,`user_id` ,`enable_logging` ,`enable_notifications` ,`enable_contextmenu` ,`menu_type` ,`theme`)
						VALUES (NULL , '1', '1', '1', '1', 'icons-text', 'default');");
		$create_zombie_bans = mysql_query(" CREATE TABLE `zombie`.`zombie_bans` (`id` INT NOT NULL AUTO_INCREMENT ,`root_id` INT NOT NULL,
						`banned_to` DATETIME NOT NULL , banned_from DATETIME NOT NULL ,PRIMARY KEY ( `id` ) ,INDEX ( `id` )) ENGINE = MYISAM ");
		$create_bans = mysql_query(" CREATE TABLE `zombie`.`bans` (`id` INT NOT NULL AUTO_INCREMENT ,`user_id` INT NOT NULL , banned_from DATETIME NOT NULL,
						`banned_to` DATETIME NOT NULL , PRIMARY KEY ( `id` ) ,INDEX ( `id` ) ) ENGINE = MYISAM ");
		if(!$create_roots || !$create_articles || !$create_users || !$create_menu || !$create_groups || !$create_files || !$create_log || !$create_comments || !$create_settings || !$create_zombie_bans || !$create_bans) $bSuccess = false;
		if(!$add_settings || !$users_friends || !$users_social || !$users_settings || !$articles_meta || !$articles_comments || !$articles_stats) $bSuccess = false;
		mysql_close($con);
		return $bSuccess;
	}
	include("functions/root_functions.inc.php");
	include("functions/blog_functions.inc.php");
	include("functions/array_functions.inc.php");
	include("functions/search_functions.inc.php");
?>
