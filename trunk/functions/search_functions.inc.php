<?php
class Search
{
	private $num_results = 0;
	private $time_start = 0;
	private $time_stop = 0;
	
	
	function num_results(){
		return $this->num_results; 
	}
	
	function time(){
		return $this->time_stop - $this->time_start;
	}
	
	function find_articles($query){
		error_reporting(E_WARNING);
		$this->time_start = explode(" ",microtime());
		$this->time_start = $this->time_start[0] + $this->time_start[1];
	//step 1: input sanitation
		$useless_words = array(" the ", " of ", " and ", " it ", " to ", " a ", " an ");
		$special_chars = array(".",",","-","[","]","\\","/","|","(",")","!","@","#","\$","%","&","*","_","?");
		$query = str_replace($special_chars, "", strtolower($query));
	//step 2: prep
		if(sizeof(explode(" ",$query)) <= 1) $q = array(strtolower($query));
		else $q = explode(" ", strtolower($query));
		foreach($q as $key => $value) if($value == "") $q[$key] = "xylophones"; //yes, i know.. but who are you to judge me?
		$all_articles = mysql_query("SELECT * FROM articles WHERE 1 ORDER BY id asc");
		$relevance = array(0);
	//step 3: search data
		while($row = mysql_fetch_array($all_articles)){
			$row["head"] = strtolower($row["head"]); $row["subh"] = strtolower($row["subh"]); $row["body"] = strtolower($row["body"]); 
			$body = explode(" ",strtolower(str_replace($special_chars,"",$row["body"])));
			array_push($relevance,$row["id"]."=> 0");
			if($query == $row["head"]) $relevance[$row["id"]] += 5;
			if($query == $row["subh"]) $relevance[$row["id"]] += 2;
			foreach($q as $i => $qv){
				$reg = "(\S)?(".$q[$i].")(\S)?";
				if(ereg($reg, $row["head"]) && $q[$i] != "") $relevance[$row["id"]] += 1;
				if(ereg($reg, $row["subh"]) && $q[$i] != "") $relevance[$row["id"]] += 0.5;
				foreach($body as $bk => $bv) if($qv == $bv) $relevance[$row["id"]] += 0.2;
				//for($c=0;$c<sizeof($body);$c++) if(ereg($reg,$body[$c])) $relevance[$row["id"]] += 0.2;
			}
		}
	//step 4: search meta-data
		$all_meta = mysql_query("SELECT article_id AS id, tags, author FROM articles_meta");
		while($row = mysql_fetch_array($all_meta)){
			$tags = strtolower($row["tags"]); $row["author"] = strtolower($row["author"]);
			$tags = explode(", ", $tags);
			$qSize = sizeof($q);
			for($i = 0; $i<$qSize; $i++){
				if(ereg("^".$row["author"]."\$", $query)) $relevance[$row["id"]] += 5;
				$tSize = sizeof($tags);
				for($a=0;$a<$tSize;$a++) if($q[$i] == $tags[$a]) $relevance[$row["id"]] += 5;
			}
		}
	//step 5: output sanitation
		$stripped = array();
		foreach($relevance as $key => $value){
			$relevance[$key] -= $key;
			if($relevance[$key] > 0) $stripped[$key] = $relevance[$key];
		}
	//step 6: sort by relevance
		arsort($stripped);
	//step 7: send time
		$this->num_results = sizeof($stripped);
		$this->time_stop = explode(" ", microtime());
		$this->time_stop = $this->time_stop[0] + $this->time_stop[1];
	//step 8: return findings
		return $stripped;
	}
	function find_roots($query)
	{
		$query = strtolower($query);
		$this->time_start = explode(" ", microtime());
		$this->time_start = $this->time_start[1] + $this->time_start[0];
		$get = mysql_query("SELECT id FROM zombie_roots WHERE LOWER(name) LIKE '%$query%' OR LOWER(mail) LIKE '%$query%'");
		$this->time_stop = explode(" ", microtime());
		$this->time_stop = $this->time_stop[1] + $this->time_stop[0];
		$this->num_results = mysql_num_rows($get);
		return mysql_fetch_array($get);
		
	}
}
?>