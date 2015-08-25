<?php
	error_reporting(E_ALL);
	/* Page name */
	static $page_name = "zombieCMS";
	
	/* MySQL Variables */
	static $db_host = "localhost";
	static $db_user = "root";
	static $db_pass = "";
	static $db_name = "zombie"; 	//this database will be created automagically, however after it is created this variable must not change value
	
	/* Messages */
	static $errors = array(	1 => "zombieCMS didn't install properly",
							2 => "Invalid Password/Username" ,
							3 => "Permission Denied",
							4 => "Script error",
							5 => "SQL Query Issue",
							6 => "Invalid URL",
							7 => "File not found",
							8 => "Missing mandatory information",
							9 => "Password Missmatch",
							10 => "You need at least one super-root account",
							11 => "User already exists.",
							12 => "SQL query returned empty set",
							13 => "Error Sending PM",
							14 => "That function is not implemented yet, sorry you had to fill out the form...",
							15 => "Could not create root-user",
							16 => "This user is banned",
							17 => "Error saving preferences",
							18 => "Error saving settings",
							19 => "Error deleting user",
							20 => "Error Creating Article",
							21 => "Could not Update Article");
	
	static $success = array(1 => "zombieCMS Installed Successfully",
							2 => "Article Added",
							3 => "Menu Appended",
							4 => "User Created",
							5 => "File Uploaded",
							//------------------------
							6 => "Article Modified",
							7 => "Menu Modified",
							8 => "User Modified",
							9 => "File Modified",
							//-------------------------
							10 => "Article Deleted",
							11 => "Menu Deleted",
							12 => "User Deleted",
							13 => "File Destroyed",
							//-------------------------
							14 => "SQL Query Ran Successfully",
							15 => "Preferences Saved",
							16 => "PM Sent Successfully",
							17 => "Root-User Created",
							18 => "Settings Saved");
	
	static $welcome_phrase = array(1 => "Jesus christ, look out, behind you!",
								   2 => "Braaaaaaains!",
								   3 => "glad to have you back.",
								   4 => "Aaaarrggghhhhhhhh",
								   5 => "Blades don't need reloading.",
								   6 => "malls are always a good idea!",
								   7 => "aim for the head.",
								   8 => "what do you mean \"nobody knows how this whole mess got started\"?");
	/* Literal Constants */
	
	
?>
