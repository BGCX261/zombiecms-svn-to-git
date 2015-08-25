<?php
class Blog
{
	function add($data_array)
	{
		$fields = ArrayTransform::keys_to_fields($data_array);
		$values = ArrayTransform::values_to_string($data_array);
		$insert = mysql_query("INSERT INTO articles ($fields) VALUES($values);");
		if($insert) {
			$id = mysql_fetch_array(mysql_query("SELECT id FROM articles ORDER BY id DESC LIMIT 1"));
			$stats = Blog::create_stats($id["id"]);
			return $id["id"];
		}
		return false;
	}
	function add_meta($id, $meta_data_array)
	{
		$now = date("Y-m-d H:i:s");
		$fields = ArrayTransform::keys_to_fields($meta_data_array);
		$fields .= ", date_added, date_modified, rating";
		$values = ArrayTransform::values_to_string($meta_data_array);
		$values .= ", '$now', '$now', '0'";
		$insert = mysql_query("INSERT INTO articles_meta (article_id, $fields) VALUES($id,$values);");
		if($insert) return true;
		return false;
	}
	function modify($id, $data_array)
	{
		$data = ArrayTransform::keys_eq_values($data_array);
		$query = "UPDATE articles SET $data WHERE id='$id'";
		$mod = mysql_query($query);
		if($mod) return true;
		return false;
	}
	function modify_meta($id, $meta_data_array)
	{
		$now = date("Y-m-d H:i:s");
		$data = ArrayTransform::keys_eq_values($meta_data_array);
		$data .= ", date_modified = '$now'";
		$query = "UPDATE articles_meta SET $data WHERE article_id = '$id'";
		$mod = mysql_query($query);
		if($mod) return true;
		return false;
	}
	function create_stats($id)
	{
		$create = mysql_query("INSERT INTO articles_stats (article_id, views, ranking, referrers) VALUES('$id', 0, 0, '');");
		if($create) return true;
		return false;
	}
	function get($id)
	{
		return mysql_fetch_array(mysql_query("SELECT head, subh, body FROM articles WHERE id='$id' LIMIT 1"));
	}
	function get_sub($subsec, $id, $entities)
	{
		$cols = ArrayTransform::values_to_fields($entities);
		return mysql_fetch_array(mysql_query("SELECT $cols FROM articles_".$subsec." WHERE article_id = '$id'"));
	}
}
?>
