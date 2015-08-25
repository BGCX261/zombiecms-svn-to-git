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
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects,dragdrop,builder,controls"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<?php if($GLOBALS["enable_contextmenu"]) { ?><script type="text/javascript" src="lib/js/context.js"></script><?php } ?>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="search">
		<div id="top">
			<img src="images/logo.png" alt="logo" id="logo" />
			<div id="quickboard"><a href="#search" id="show_search"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
			<div id="menu">
				<?php include ("zombie_menu.inc.php"); ?>
			</div>
		</div>
		<div id="main">
			<?php include("searchbox.inc.php"); ?>
			<?php
				if(isset($_GET["type"]) && isset($_GET["query"]))
				{
					$type = $_GET["type"]; $query = strtolower($_GET["query"]);
					$search = new Search();
					switch($type){
						case "blog":
							$blog = new Blog();
							$results = $search->find_articles($query);
							if($search->num_results() > 0)
							{
								?><p>Order By: <a href="#" id="order_relevance">Relevance</a> | <a href="#" id="order_name">Name</a></p><?php
								$sum = array_sum($results);
								$q = explode(" ", $query);
								foreach($results as $key => $value){
									$current = $blog->get($key);
									$current_meta = $blog->get_sub("meta", $key, array("tags","author"));
									echo "<div class=\"search_result\"><h2><a href=\"blog.php?p=read&amp;id=$key\" rel=\"$value\" id=\"r$key\">",$current["head"],"</a></h2><h3>",$current["subh"],"</h3><span>Relevance: <strong>",substr($value/$sum*100,0,5),"%</strong> ($value pts)</span><div class=\"article_body\" style=\"display: none; margin: 10px;\">";
									echo "<p>";
									$special_chars = array(".",",","-","[","]","\\","/","|","(",")","!","@","#","\$","%","&","*","_","?");
									$body = str_replace(array("\n\n","\n"), array("</p><p>"," <br /> ",), $current["body"]);
									$body = str_replace($special_chars, "", $body);
									$body = explode(" ",$body);
									foreach($q as $qk => $qv){
										$color = "ff".dechex($qk."20")."0f";
										foreach($body as $bk => $bv) if(ereg($qv,$bv)) $body[$bk] = str_replace($bv, "<span style=\"color: #$color; font-size: 18px; font-family: Tahoma\">".$bv."</span>", $body[$bk]);	
									}
									echo implode(" ", $body),"</p><p>Tags: ",$current_meta["tags"],", Written By: ",$current_meta["author"],"</p></div></div>";
								}
							}
							echo "Search took <strong>",substr($search->time(),0, 6),"s</strong> and found <strong>",$search->num_results(),"</strong> results<br />";
						break;
						case "roots":
							$results = $search->find_roots($query);
							$size = sizeof($results);
							print_r(mysql_fetch_array(mysql_query("SELECT id FROM zombie_roots WHERE LOWER(name) LIKE '%$query%' OR LOWER(mail) LIKE '%$query%'")));
							foreach($results as $key){
								$current = Root::get($key);
								echo "<div class=\"search_result\">";
								echo "<h2><a href=\"roots.php?id=",$current["id"],"\">",$current["name"],"</a></h2><p><span>",$current["mail"],"</span></p>";
								echo "</div>";
							}
							echo "Search took <strong>",substr($search->time(), 0, 7),"s</strong> and found <strong>",$search->num_results()," results</strong><br />";
						break;
					}
				}
			?>
		</div>
	</body>
</html>
<?php mysql_close($con);?>
