<?php ob_start(); ?>
<?php
include("../user_settings.inc.php");
include("../functions.inc.php");
$con = connect_and_select($GLOBALS["db_name"]);
if(!isset($_SESSION["admin_rights"]) || $_SESSION["admin_rights"] < 5) header("Location: zombie.php?ERROR_CODE=3");
static $COMMANDS = array("ban","clear | cls","unban", "delete", "help", "mod rights", "pm", "sql","show", "purge");
static $COMMANDS_ABOUT = array( "ban" => "Ban a user or root user. Does not work on root-user with rights set to 5",
								"clear" => "Clear the console of text",
								"delete" => "Delete an entity, such as user, root user, file or article.",
								"help" => "Display this help message and quit. For more information try SHOW COMMANDS or HELP COMMAND.",
								"mod rights" => "Set the rights-level of a root-user to the specified argument",
								"pm" => "send a message to a root-user or a user.",
								"sql" => "Execute a SQL query.",
								"show" => "Show is shorthand for some SQL queries, such as viewing the log.",
								"purge" => "Completely delete a file, article, user etc from the system.",
								"unban" => "Remove the ban over a user or root-user");
								
static $COMMANDS_USAGE = array( "ban" => "BAN [user_type = string] [user = string OR int] FOR [length = int] [units = SECONDS, MINUTES, HOURS, DAYS, WEEKS, MONTHS, YEARS]",
								"clear" => "CLEAR",
								"unban" => "UNBAN [user_type = string] [user = string OR int]",
								"delete" => "DELETE [entity = string] [id = int]",
								"help" => "HELP COMMAND",
								"mod rights" => "MOD RIGHTS [user = string OR int] SET TO [rights-level = int (MAX 5)]",
								"pm" => "PM [user_type = string] [user = string OR int] WITH [message = string]",
								"sql" => "SQL [query = string]",
								"show" => "SHOW bans | log | roots | commands",
								"purge" => "PURGE [entity = string] [id = int]");

static $COMMANDS_EXAMPLE = array( "ban" => "BAN root admin FOR 10 days",
								  "clear" => "CLEAR",
								  "unban" => "UNBAN root admin",
								  "delete" => "DELETE article 10",
								  "help" => "HELP DELETE",
								  "mod rights" => "MOD RIGHTS admin SET TO 4",
								  "pm" => "PM root admin WITH call me asap please",
								  "sql" => "SQL select * from users",
								  "show" => "SHOW bans",
								  "purge" => "PURGE file 4");
$q = str_replace(array("\\", "\'", "\\\""), "", $_POST["query"]);
if(ereg("^SHOW|^show",$q))
{
	$q = strtolower($q);
	$q = str_replace("show ", "", $q);
	if(ereg("commands|COMMANDS", $q))
	{
		echo "\n";
		for($i = 0; $i<sizeof($COMMANDS); $i++) echo $COMMANDS[$i],"\n";
		echo "For more information, try HELP\n";
	}
	else if(ereg("help|HELP", $q))
		echo "\nHELP\n",$COMMANDS_ABOUT["help"],"\nUsage:\n\t",$COMMANDS_USAGE["help"],"\nExample:\n\t",$COMMANDS_EXAMPLE["help"],"\n";
	
	else if(ereg("bans|BANS", $q))
	{
		$bans = mysql_query("SELECT root_id, banned_from, banned_to FROM zombie_bans ORDER BY id ASC");
		if(mysql_num_rows($bans) == 0) echo "\nFound no bans\n";
		else {
			echo "\nUSER_ID\t\t\tBANNED_FROM\t\t\t\tBANNED_TO\n";
			while($row = mysql_fetch_array($bans)) echo $row["root_id"],"\t\t\t\t",$row["banned_from"],"\t\t\t",$row["banned_to"],"\n";
		}
	}
	else if(ereg("log|LOG",$q))
	{
		$log = mysql_query("SELECT root_user, event, time FROM zombie_log ORDER BY id ASC");
		echo "\nUser\t\t\tEvent\t\t\tTime\n";
		while($row = mysql_fetch_array($log)) echo $row["root_user"],"\t\t\t",$row["event"],"\t\t\t",$row["time"],"\n";
	}
	else if(ereg("roots|roots",$q))
	{
		$roots = mysql_query("SELECT id, name, rights FROM zombie_roots ORDER BY id ASC");
		echo "\nID\t\t\tName\t\t\tRights\n";
		while($row = mysql_fetch_array($roots)) echo $row["id"],"\t\t\t",$row["name"],"\t\t\t",$row["rights"],"\n";
		
	}
	else echo "\ncan not show \"$q\"\nFor a full list of things you CAN show, run HELP SHOW\n";
}
else if(ereg("^HELP|^help", $q))
{
	$q = strtolower($q);
	$q = str_replace("help ", "", $q);
	$found = false;
	for($i = 0; $i<sizeof($COMMANDS); $i++)
		if($COMMANDS[$i] == $q) {$found = true; break;}
	if(!$found) echo "\nUnknown command \"$q\"\n";
	else echo "\n",strtoupper($q),"\n",$COMMANDS_ABOUT[$q],"\nUsage:\n\t",$COMMANDS_USAGE[$q],"\nExample:\n\t",$COMMANDS_EXAMPLE[$q],"\n";
}
else if(ereg("^SQL|^sql", $q))
{
	$q = str_replace(array("sql ", "SQL "), "", $q);
	$qc = $q;
	if(ereg("select|SELECT", $q) && ereg("from|FROM", $q) || ereg("show|SHOW|describe|DESCRIBE", $q)) { //queries that will return an array and expect ouput
		echo "\n";
		$q = mysql_query($q);
		if(!$q) echo mysql_error(),"\n";
		else {
			if(!is_array(mysql_fetch_array($q))) die("Query returned an empty set\n");
			$heads = array_keys(mysql_fetch_array($q));
			for($u = 1; $u<sizeof($heads); $u+=2) echo $heads[$u],"\t\t\t\t";
			echo "\n";
			while($row = mysql_fetch_array($q)) { 
				for($i = 0; $i<sizeof($row)/2; $i++) echo $row[$i],"\t\t\t\t";
				echo "\n";
			}
		}
		if($GLOBALS["enable_logging"]) add_to_log($_SESSION["admin_user"], "ran query $qc");
	}
	else { //truncate, drop etc
		$q = mysql_query($q);
		if($q) echo "\nQuery executed Successfully\n";
		else echo "\n".mysql_error()."\n";
		if($GLOBALS["enable_logging"]) add_to_log($_SESSION["admin_user"], "ran query $qc");
	}
}
else if(ereg("^BAN|^ban",$q))
{
	$q = strtolower($q);
	preg_match("/(\w{3}\s+(\w{4})\s+(\w+)\s+\w{3}\s+(\d{1,})\s+(\w{3,}))/",$q, $matches);
	if(sizeof($matches) == 0)
		echo "\ninvalid syntax, see HELP BAN for more info.\n";
	else
	{
		echo "\n";
		$user_type = $matches[2];
		$user = $matches[3];
		$length = $matches[4];
		$unit = $matches[5];
		if($user_type == "root")
		{
			$ban = Root::ban($user, $length, $unit);
			if(!$ban) echo "Unsuccessful ban\n";
			else
			{
				echo "$user is banned for $length $unit\n";
				if($GLOBALS["enable_logging"]) add_to_log($_SESSION["admin_user"], "banned ".$user);
			}
		}
	}
}
else if(ereg("^UNBAN|^unban",$q))
{
	$q = strtolower($q);
	$person = str_replace(array("unban root ","unban user "), "", $q);
	if(ereg("root", $q)) $unban = Root::unban($person);
	else if(ereg("user", $q)) $unban = User::unban($person);
	else die("\nInvalid syntax, see HELP UNBAN for more info.\n");
	if($unban == 1)
	{
		echo "\n$person is no longer banned\n";
		if($GLOBALS["enable_logging"]) add_to_log($_SESSION["admin_user"], "unbanned ".$person);
	}
	else if($unban == 0) echo "\nerror unbanning $person\n";
	else if($unban == -1) echo "\nThere is no ban over user $person\n";
}
else if (ereg("^MOD RIGHTS|^mod rights", $q))
{
	$q = explode(" ", $q);
	$root = $q[2];
	$rights = $q[3];
	$old = mysql_query("SELECT rights FROM zombie_roots WHERE name = '$root'");
	$bFound = mysql_num_rows($old);
	$res = mysql_fetch_array($old);
	$update = mysql_query("UPDATE zombie_roots SET rights = '$rights' WHERE name = '$root' AND rights < 5");
	if($old && $bFound > 0 && $res["rights"] < 5){
		echo "\n$root is now a ".$rights."er!\n";
	}
	if($bFound == 0){
		echo "\n$root was not found\n";
	}
	if($res["rights"] == 5){
		echo "\nPermission Denied!\n";
	}
}
else
{
	echo "\nUnknown command ";
	$q = explode(" ", $q);
	echo $q[0],"\n";
}
mysql_close($con);
?>
