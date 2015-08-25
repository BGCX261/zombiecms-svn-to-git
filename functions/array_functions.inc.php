<?php 
class ArrayTransform
{
	function keys_eq_values($values)
	{
		$ret_string = "";
		$keys = array_keys($values);
		for($i = 0; $i<sizeof($values); $i++) $ret_string .= $keys[$i]." = '".htmlentities($values[$keys[$i]])."', ";
		return substr($ret_string,0,strlen($ret_string)-2);
		//will take the keys and values of an array and return them as string
		//	key1 = 'value1', key2 = 'value2', key3 = 'value3', keyN = 'valueN'
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