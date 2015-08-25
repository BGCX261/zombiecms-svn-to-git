<?php
/*
	This class is mostly going to be used to get send data to different functions within zombieCMS from forms.
	It's a convinent way to send multiple variables as a key => value array instead of just adding arrays.
	That way, If you want to update 2 out of N fields in a database, you only have to update 2 fields instead of n.
	Example:
		updateTable("mytable", array("field1" => "value1", "field2" = 2, "field3" => "another value"));
	on the backend:
		function updateTable($table, $everything){
			$everything = ArrayTransform::keys_eq_values($everything);
			mysql_query("UPDATE TABLE $table SET $eveything"); //TADA!
		}
*/
class ArrayTransform
{
	function keys_eq_values($values)
	{
		$ret_string = "";
		$keys = array_keys($values);
		for($i = 0; $i<sizeof($values); $i++) $ret_string .= $keys[$i]." = '".htmlentities($values[$keys[$i]])."', ";
		return substr($ret_string,0,strlen($ret_string)-2);
		//will take the keys and values of an array and return them as string
		//key1 = 'value1', key2 = 'value2', key3 = 'value3', keyN = 'valueN'
	}
	function keys_to_fields($array)
	{
		$ret_string = "";
		$keys = array_keys($array);
		for($i = 0; $i<sizeof($array); $i++) $ret_string .= $keys[$i].", ";
		return substr($ret_string,0,strlen($ret_string)-2);
		//will take the keys from an array and return them as string = key1, key2, key3, keyN
	}
	function values_to_string($array)
	{
		$ret_string = "";
		$keys = array_keys($array);
		for($i = 0; $i<sizeof($array); $i++) $ret_string .= "'".$array[$keys[$i]]."', ";
		return substr($ret_string,0,strlen($ret_string)-2);
		//will return string = 'value1', 'value2', 'value3', 'valueN'
	}
	function values_to_fields($array)
	{
		$ret_string = "";
		for($i = 0; $i<sizeof($array); $i++) $ret_string .= $array[$i].", ";
		return substr($ret_string,0,strlen($ret_string)-2);
		//will return string = value1, value2, value3, valueN
	}
}
?>
