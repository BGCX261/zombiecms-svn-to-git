<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
include("user_settings.inc.php");
include("functions.inc.php");
if(!isset($_SESSION["admin_rights"]) || $_SESSION["admin_rights"] < 1) header("Location: index.php?ERROR_CODE=3");
$con = connect_and_select($GLOBALS["db_name"]);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="html/xml; charset=ISO-8859-1"/>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Search - ZombieCMS</title>
		<script type="text/javascript" src="lib/js/prototype.js"></script>
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="search">
		<div id="top">
			<img src="images/logo.png" alt="logo" id="logo" />
			<div id="quickboard"><a href="search.php"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
			<div id="menu">
				<?php include ("zombie_menu.inc.php"); ?>
			</div>
		</div>
		<div id="main">
			<?php
				if(isset($_GET["type"]) && isset($_GET["query"]))
				{
					$type = $_GET["type"]; $q = explode(" ",strtolower($_GET["query"]));
					$q_exact = strtolower($_GET["query"]);
					if($GLOBALS["enable_logging"]) add_to_log($_SESSION["admin_user"], "searched $type for \"$q_exact\"");
					$search = new Search();
					$useless_words = array("the", "of", "and", "it", "to", "a", "an");
					if($type == "blog"){
						$t_start = explode(" ",microtime());
						$t_start = $t_start[1] - $t_start[0];
						$blog = new Blog();
						$all_articles = mysql_query("SELECT * FROM articles ORDER BY id asc");
						$relevance = array(0);
						while($row = mysql_fetch_array($all_articles)){
							$row["head"] = strtolower($row["head"]); $row["subh"] = strtolower($row["subh"]); $row["body"] = strtolower($row["body"]); 
							$body = explode(" ",strtolower($row["body"]));
							array_push($relevance,$row["id"]."=> 0");
							echo "<div style=\"border: 1px solid #aaa; width: 400px; margin: 20px; padding: 10px;\">";
							echo "<a href=\"blog.php?p=mod&amp;id=",$row["id"],"\">Current ID: ",$row["id"],"</a><br />";
							echo "Search Query: <span style=\"color: #0f0; background: #faf2b6;\">",$q_exact,"</span><br />";
							if($q_exact == $row["head"]){
								$relevance[$row["id"]] += 5;
								echo "<span style=\"color: #f00;\">Exact Match: Head +5, Matched Sentance: ",$q_exact,"</span><br />";
							}
							if($q_exact == $row["subh"]){
								$relevance[$row["id"]] += 2;
								echo "<span style=\"color: #f00;\">Exact Match: Subh +2, Matched Sentance: ",$q_exact,"</span><br />";
							}
							if(sizeof($q) >= 1){
								for($i = 0; $i<sizeof($q); $i++) {
									$reg = "((\s".$q[$i]."\s)|(^".$q[$i]."(\s)?)|((\s)?".$q[$i]."$))";
									echo "Searchterm: <span style=\"color: #0f0; background: #faf2b6;\">",$q[$i],"</span><br />";
									if(ereg($reg, $row["head"])){
										$relevance[$row["id"]] += 1;
										echo "<span style=\"color: #00f\">Partial Match: Head +1</span><br />";
									}
									if(ereg($reg, $row["subh"])){
										$relevance[$row["id"]] += 0.5;
										echo "<span style=\"color: #00f\">Partial Match: Subhead +0.5</span><br />";
									}
									for($c=0;$c<sizeof($body);$c++){
										if(ereg($reg,$body[$c])){
											$relevance[$row["id"]] += 0.2;
											echo "<span style=\"color: #00f\">Found word in Body: +0.2</span><br />";
										}
									}
								}
								echo "</div>";
							}
						}
						echo "<br />";
						$clean = array();
						$keys = array_keys($relevance);
						for($i = 1; $i<sizeof($relevance); $i++){
							$relevance[$keys[$i]] -= $keys[$i];
							if($relevance[$keys[$i]] > 0) $clean[$keys[$i]] = $relevance[$keys[$i]];
						}
						$keys = array_keys($clean);
						foreach($clean as $key => $value){
							echo "[",$key,"] => ",$value,"<br />";
						}
						$t_end = explode(" ",microtime());
						$t_end = $t_end[1] - $t_end[0];
						$time = $t_start - $t_end;
						echo "Search took ",substr($time, 0, 8)," seconds and found ",sizeof($clean)," results";
					}
				}
			?>
		</div>
	</body>
</html>
<?php mysql_close($con);?>