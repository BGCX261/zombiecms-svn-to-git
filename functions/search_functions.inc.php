<?php
class Search
{
	function exact_match($set, $query)
	{
		$data = mysql_fetch_array(mysql_query("SELECT id FROM $set WHERE head = '$query' OR subh = '$query';"));
		return is_array($data) ? array("") : $data;
	}
}
?>