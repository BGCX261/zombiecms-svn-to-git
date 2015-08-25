<?php
class Root
{
	function add($name, $pass, $mail, $rights)
	{
		if(mysql_num_rows(mysql_query("SELECT id from zombie_roots WHERE name = '$name' OR mail = '$mail'")) > 0) return -1; //user already exists
		$add = mysql_query("INSERT INTO zombie_roots (id, name, pwd, mail, rights) VALUES ('', '$name', MD5('$pass'), '$mail', '$rights');");
		$id = mysql_fetch_array(mysql_query("SELECT id FROM zombie_roots ORDER BY id DESC limit 1"));
		$add_settings = mysql_query("INSERT INTO zombie_settings (id, user_id, enable_logging,enable_notifications,enable_contextmenu, menu_type, theme)
									VALUES ('', '".$id[0]."', '1', '1', '1', 'icons-text', 'default');");
		return ($add && $add_settings);
	}
	function delete($id)
	{
		$unban = Root::unban($id);
		$rights = mysql_fetch_array(mysql_query("SELECT rights from zombie_roots WHERE id='$id'"));
		if($rights[0] == 5 && $id != 1) return -1; //tried to delete a super-root-user that wasn't the default admin account
		if(mysql_num_rows(mysql_query("SELECT rights FROM zombie_roots LIMIT 2") <= 1)) return -1; //you can't delete the only root account
		else
		{
			$del = mysql_query("DELETE FROM zombie_roots WHERE id = '$id'");
			$del_settings = mysql_query("DELETE FROM zombie_settings WHERE user_id = '$id'");
			return ($del && $del_settings);
		}
		return false;
	}
	function ban($root, $ban_length, $unit = "days")
	{
		$unit = strtoupper($unit);
		$now = date("Y-m-d H:i:s");
		if(substr($unit, strlen($unit)-1, 1) == "S")
			$unit = substr($unit, 0, strlen($unit)-1);
		$root_info = mysql_fetch_array(mysql_query("SELECT rights, id FROM zombie_roots WHERE name='$root' LIMIT 1"));
		if(!is_numeric($root)) $root = $root_info["id"]; 
		$root_exists = mysql_num_rows(mysql_query("SELECT name FROM zombie_roots WHERE id = '$root'"));
		if($root_exists > 0) {
		$rights = $root_info["rights"];
			if($rights < 5) {
				$unban = Root::unban($root);
				$ban = mysql_query("INSERT INTO zombie_bans (id, root_id, banned_from,banned_to) VALUES ('','$root','$now',TIMESTAMPADD($unit, $ban_length, '$now'))");
				if($ban) return true;
				return false;
			}
		}
		return false;
	}
	function unban($root)
	{
		if(!is_numeric($root)) { $root_id = mysql_fetch_array(mysql_query("SELECT id FROM zombie_roots WHERE name='$root' LIMIT 1")); $root = $root_id[0];}
		$root_id = $root;
		if(Root::is_banned($root_id))  {
			$unban = mysql_query("DELETE FROM zombie_bans WHERE root_id = '$root_id'");
			if($unban) return 1;
			return 0;
		}
		return -1;
	}
	function is_banned($root) {
		$now = date("Y-m-d H:i:s");
		$check_ban = mysql_fetch_array(mysql_query("SELECT id FROM zombie_bans WHERE root_id = '$root' AND banned_to > '$now'"));
		return (sizeof($check_ban) > 1);
	}
	function get($root){
		if(!is_numeric($root)) { $root = mysql_fetch_array(mysql_query("SELECT id FROM zombie_roots WHERE name='$root' LIMIT 1")); $root = $root[0];}
		return mysql_fetch_array(mysql_query("SELECT id, name, mail, rights FROM zombie_roots WHERE id='$root'"));
	}
}
?>