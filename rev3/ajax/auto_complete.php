<?php
	include("../functions.inc.php");
	$con = connect_and_select($GLOBALS["db_name"]);
	$type = $_GET["type"];
	$query = $_GET["query"];
	$search = new Search();
	switch($type){
		case "blog":
			$results = $search->find_articles($query);
			$index = 0;
			echo "<ul>";
			foreach($results as $key){
				$current = mysql_fetch_array(mysql_query("SELECT head, subh FROM articles WHERE id = '$key'"));
				echo "<li>",$current["head"],"<br /><span class=\"informal\">",$current["subh"],"</span></li>";
				$index++;
				if($index == 5) break;
			}
			echo "<ul>";
		break;
		case "roots":
			$results = $search->find_roots($query);
			echo "<ul>";
			foreach($results as $key){
				$current = Root::get($results[0]);
				echo "<li>",$current["name"],"<br /><span class=\"informal\">",$current["mail"],"</span></li>";
			}
			echo "</ul>";
		break;
		default:
			echo "<ul><li>Search for $type is not implemented yet</li></ul>";
		break;
	}
	mysql_close($con);
?>