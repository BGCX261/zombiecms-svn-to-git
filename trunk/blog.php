<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
include("functions.inc.php"); 
include("user_settings.inc.php"); 
if(!isset($_SESSION["admin_rights"]) || $_SESSION["admin_rights"] < 1) header("Location: zombie.php?ERROR_CODE=3");
$con = connect_and_select($GLOBALS["db_name"]);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="html/xml; charset=ISO-8859-1"/>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Blog - ZombieCMS</title>
		<script type="text/javascript" src="lib/js/prototype.js"></script>
		<script type="text/javascript" src="lib/js/scriptaculous.js?load=effects,builder,dragdrop,controls"></script>
		<script type="text/javascript" src="lib/js/rounded_corners_lite.inc.js"></script>
		<script type="text/javascript" src="lib/js/scripts.js"></script>
		<?php if($GLOBALS["enable_contextmenu"]) { ?><script type="text/javascript" src="lib/js/context.js"></script><?php } ?>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/divs.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/styles.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/menu.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/<?php echo $GLOBALS["theme"] ?>/links.css" />
	</head>
	<body id="blog">
		<div id="top"><img src="images/logo.png" alt="logo" id="logo" /><div id="quickboard"><a href="#search" id="show_search"><img src="images/icons/search.png" alt="search" /></a><a href="settings.php"><img src="images/icons/settings.png" alt="settings" /></a><a href="#empty"><img src="images/icons/about.png" alt="about" /></a><a href="log.php?dir=out"><img src="images/icons/logout.png" alt="logout" /></a></div>
		<div id="menu"><?php include ("zombie_menu.inc.php"); ?></div></div>
		<div id="main">
		<?php include("searchbox.inc.php"); ?>
		<?php if($GLOBALS["enable_notifications"] == true) include ("notification_system.inc.php"); ?>
			<?php
				$p = isset($_GET["p"]) ? $_GET["p"] : "add";
				switch($p)
				{
					case "mod":
					case "add":
					$blog = new Blog();
					$data = array("head" => "", "subh" => "", "body" => "");
					$meta = array("allow_comments" => 1, "tags" => "", "published" => 1, "author" => $_SESSION["admin_user"]);
					$root = get_entities("zombie_roots", $_SESSION["id"], array("groups"));
					$root = explode(", ",$root["groups"]);
					$meta["groups"] = array("all");
					if(isset($_GET["id"]) && isset($_GET["p"]) && $_GET["p"] == "mod") {
						$data = $blog->get($_GET["id"], array("head", "subh", "body"));
						$meta = $blog->get_sub("meta", $_GET["id"], array("allow_comments","tags","author","groups","published"));
						$groups = explode(", ",$meta["groups"]);
					}
			?>
			<form method="post" id="article_form" action="engine.php?action=<?php echo $p == "add" ? "add_article" : "mod_article";?><?php if(isset($_GET["id"])) echo "&amp;id=".$_GET["id"]; ?>" enctype="plain/text" id="article_form">
				<p>
				<label for="head">Header</label> <input type="text" name="head" id="head" value="<?php echo $data["head"];?>"/>
				<label for="subh">Sub-header</label><span>(Optional)</span> <input type="text" name="subh" id="subh" value="<?php echo $data["subh"];?>"/>
				<label for="author">Author</label><span>(Semi-Automatic)</span><input type="text" name="author" id="author" value="<?php echo $meta["author"];?>"/>
				<label for="body">Body</label>
				</p>
				<div id="zombiecode_panel">
					<a href="#empty" title="Stylize Bold"><img src="images/icons/inline/article/bold.png" alt="bold" /></a> <a href="#empty" title="Stylize Underline"><img src="images/icons/inline/article/underline.png" alt="underline" /></a> <a href="#empty" title="Stylize Italic"><img src="images/icons/inline/article/italic.png" alt="italic" /></a><div class="spacer"></div>
					<a href="#empty" title="Justify Left"><img src="images/icons/inline/article/align_left.png" alt="align_left" /></a> <a href="#empty" title="Justify Center"><img src="images/icons/inline/article/align_center.png" alt="align_center" /></a> <a href="#empty" title="Justify Right"><img src="images/icons/inline/article/align_right.png" alt="align_right" /></a><div class="spacer"></div>
					<a href="#empty" title="Add Paragraph"><img src="images/icons/inline/article/paragraph.png" alt="paragraph" /></a> <a href="#empty" title="Add a List"><img src="images/icons/inline/article/unordered_list.png" alt="unordered_list" /></a> <a href="#empty" title="Add a Numerical List"><img src="images/icons/inline/article/ordered_list.png" alt="ordered_list" /></a><div class="spacer"></div>
					<a href="#" title="Hit F11 for more effect" id="show_creativemode">CreativeMode</a>
				</div>
				<p>
				<textarea name="body" id="body" cols="30" rows="10"><?php echo $data["body"];?></textarea>
				<label for="tags">Tags</label><span>(Optional, severed by commas)</span> <input type="text" name="tags" id="tags" value="<?php echo $meta["tags"];?>"/>
				<label for="groups">Publish for Groups</label><span>(click to select)</span> 
				</p>
				<ul id="groups">
					<li><a href="#empty" rel="all">All</a></li>
					<!--<li><a href="#empty" rel="all" <?php if($meta["groups"][0] == "all") echo "class=\"selected\"";?>>All</a></li>-->
					<?php
						/*for($i = 0; $i<sizeof($root); $i++) {
							$bFound = false;
							for($a = 0; $a<sizeof($meta["groups"]); $a++){
								if($root[$i] == $meta["groups"][$a]) {
									$bFound = true;
									break;
								}
							}
							if($bFound) {
								echo "<li><a href=\"#empty\" rel=\"".$root[$i]."\" class=\"selected\">".$root[$i]."</a></li>";
							}
							else echo "<li><a href=\"#empty\" rel=\"".$root[$i]."\">".$root[$i]."</a></li>";
						}*/
					?>
				</ul>
				<p>
				<label for="allow_comments">Allow Comments?</label><span>(check to apply)</span>
				<input type="checkbox" name="allow_comments" id="allow_comments" <?php if($meta["allow_comments"]) echo "checked=\"checked\"";?>/>
				<label for="published">Publish?</label><span>(check to apply)</span>
				<input type="checkbox" name="published" id="published" <?php if($meta["published"]) echo "checked=\"checked\"";?>/>
				<input type="submit" class="btn" <?php echo $p == "add" ? "value=\"Create a bloody masterpiece\" id=\"add_article\"" : "value=\"Re-animate\" id=\"mod_article\"";?> /><img src="images/btn_right.png" alt="btn_right" class="btn_right" />
				</p>
			</form>
			<?php
				break;
				case "read":
					if(isset($_GET['id'])) {
						$id = $_GET["id"];
						$blog = new Blog();
						$article = $blog->get($id);
						$meta = $blog->get_sub("meta",$id,array("author"));
						echo "<a href=\"blog.php?p=mod&amp;id=",$id,"\">Modify this Article</a> or <a href=\"blog.php?p=del&amp;id=",$id,"\">delete it</a>";
						echo "<h2>",$article["head"],"</h2>";
						if($article["subh"] != null) echo "<h3>",$article["subh"]," - By: ",$meta["author"],"</h3>";
						$body = str_replace(array("\n\n","\n"), array("</p><p>","<br />",), $article["body"]);
						echo "<p>",$body,"</p>";
						echo "<a href=\"blog.php?p=mod&amp;id=",$id,"\">Modify this Article</a> or <a href=\"blog.php?p=del&amp;id=",$id,"\">delete it</a>";
					}
					else {
						echo "No article selected";
					}
				break;
				case "del":
					$id = $_GET["id"];
					if(!isset($_GET["ERROR_CODE"]) || !isset($_GET["SUCCESS_CODE"])){
						echo "<p>Are you sure you want to delete this article?<br />";
						echo "<a href=\"engine.php?action=del_article&amp;id=",$id,"\">Yes</a> <a href=\"blog.php?p=read&amp;id=",$id,"\">No</a></p>";
					}
				break;
				default:
					echo "<p>wait, what? that shouldn't be a page?</p>";
				break;
			}
			?>
		</div>
	</body>
</html>
<?php mysql_close($con); ?>
