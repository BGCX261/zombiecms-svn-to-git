<div id="search_box" style="display: none;">
	<form method="get" action="search.php" enctype="plain/text" id="search_form"><p>
		<label for="query">Search </label><input type="text" name="query" value="<?php echo isset($_GET["query"]) ? $_GET["query"] : ""; ?>" id="query" size="40"/>
		<label for="type">Where? </label><select name="type" id="type">
		<?php if(isset($_GET["type"])) {echo "<option value=\"",$_GET["type"],"\">",$_GET["type"],"</option>";}?>
		<option value="blog">Blog</option>
		<option value="files">Files</option>
		<option value="users">Users</option>
		<option value="roots">Roots</option>
		</select>
		<input type="submit" value="Go find it!" />
	</p><div id="auto_complete"></div></form>
</div>