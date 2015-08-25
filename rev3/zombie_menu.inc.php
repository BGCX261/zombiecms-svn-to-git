<?php if($GLOBALS["menu_type"] == "icons-text") { ?>
<ul style="width: 852px;">
	<li><a href="zombie.php"><img src="images/icons/ctrl_panel.png" alt="ctrl_panel" />Control Panel</a></li>
	<li><a href="blog.php"><img src="images/icons/blog.png" alt="blog" />Blog</a>
		<ul>
			<li><a href="blog.php?p=add"><img src="images/icons/inline/add.png" alt="add_new"/>New</a></li>
			<li><a href="blog.php?p=read"><img src="images/icons/inline/modify.png" alt="modify"/>Read</a></li>
			<li><a href="search.php?p=search"><img src="images/icons/inline/find.png" alt="search"/>Search</a></li>
		</ul>
	</li>
	<li><a href="#empty"><img src="images/icons/users.png" alt="users" />Users</a></li>
	<li><a href="#empty"><img src="images/icons/menu.png" alt="pages" />Pages</a></li>
	<li><a href="#empty"><img src="images/icons/files.png" alt="files" />Files</a></li>
	<li><a href="terminal.php"><img src="images/icons/terminal.png" alt="terminal" />Terminal</a></li>
	<li><a href="roots.php"><img src="images/icons/roots.png" alt="roots" />Roots</a></li>
	<li><a href="#empty"><img src="images/icons/plugins.png" alt="plugins" />Plug-ins</a></li>
</ul>
<?php } else if($GLOBALS["menu_type"] == "icons") { ?>
<ul style="width: 550px;">
	<li><a href="zombie.php"><img src="images/icons/ctrl_panel.png" alt="ctrl_panel" /></a></li>
	<li><a href="blog.php"><img src="images/icons/blog.png" alt="blog" /></a>
		<ul>
			<li><a href="blog.php?p=add"><img src="images/icons/inline/add.png" alt="add_new"/></a></li>
			<li><a href="blog.php?p=read"><img src="images/icons/inline/modify.png" alt="modify"/></a></li>
			<li><a href="search.php?p=search"><img src="images/icons/inline/find.png" alt="search"/></a></li>
		</ul>
	</li>
	<li><a href="#empty"><img src="images/icons/users.png" alt="users" /></a></li>
	<li><a href="#empty"><img src="images/icons/menu.png" alt="pages" /></a></li>
	<li><a href="#empty"><img src="images/icons/files.png" alt="files" /></a></li>
	<li><a href="terminal.php"><img src="images/icons/terminal.png" alt="terminal" /></a></li>
	<li><a href="roots.php"><img src="images/icons/roots.png" alt="roots" /></a></li>
	<li><a href="#empty"><img src="images/icons/plugins.png" alt="plugins" /></a></li>
</ul>
<?php } else if($GLOBALS["menu_type"] == "text") { ?>
<ul style="width: 650px;">
	<li><a href="zombie.php">Control Panel</a></li>
	<li><a href="blog.php">Blog</a>
		<ul>
			<li><a href="blog.php?p=add">Add</a></li>
			<li><a href="blog.php?p=read">read</a></li>
			<li><a href="search.php?p=search">Search</a></li>
		</ul>
	</li>
	<li><a href="#empty">Users</a></li>
	<li><a href="#empty">Pages</a></li>
	<li><a href="#empty">Files</a></li>
	<li><a href="terminal.php">Terminal</a></li>
	<li><a href="roots.php">Roots</a></li>
	<li><a href="#empty">Plug-ins</a></li>
</ul>
<?php } ?>