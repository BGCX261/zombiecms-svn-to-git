Event.observe(window, "load", function() {
	Event.observe(document.body, "dblclick", function(e){context_menu(e)});
});
function context_menu(e)
{
	var mouseX = Event.pointerX(e);
	var mouseY = Event.pointerY(e);
	var xPos = mouseX - 100;
	var yPos = mouseY - 100;
	if($("context_menu")){
		var tog = new Effect.toggle("context_menu", "appear", {duration: 0.25});
		$("context_menu").style.top = yPos+"px";
		$("context_menu").style.left = xPos+"px";
		if($("context_menu").style.display != "none"){
			$$("context_box").each(function(n){
				alert(n);
				Event.observe(n, "hover", function(){
					var scale = new Effect.Scale(n, 150);
				});
			});
		}
	}
	else if(!$("context_menu")) {
		var div = "<div id=\"context_menu\" style=\"top: "+yPos+"px; left: "+xPos+"px;\"></div>";
		document.body.insert(div);
		var p = document.body.getAttribute("id");
		switch(p){
			case "ctrl_panel":
				$("context_menu").insert("<div class=\"context_box\" style=\"width: 100px; height: 22px; margin: 0px auto; position: absolute; top: 10px; left: 50px; background-color: #fff; clear: none; border: 1px solid #aaa;\"></div>");
				$("context_menu").insert("<div class=\"context_box\" style=\"width: 22px; height: 100px; position: absolute; top: 50px; left: 10px; background-color: #fff; clear: none; border: 1px solid #aaa;\"></div>");
				$("context_menu").insert("<div id=\"context_main\" style=\"width: 100px; height: 100px; position: absolute; top: 50px; left: 50px; background-color: #eee; clear: none; border: 1px solid #aaa;\"></div>");
				$("context_menu").insert("<div class=\"context_box\" style=\"width: 22px; height: 100px; position: absolute; top: 50px; left: 170px; background-color: #fff; clear: none; border: 1px solid #aaa;\"></div>");
				$("context_menu").insert("<div class=\"context_box\" style=\"width: 100px; height: 22px; margin: 0px auto; position: absolute; top: 170px; left: 50px; background-color: #fff; clear: none; border: 1px solid #aaa;\"></div>");
			break;
			case "terminal":
				$("context_menu").insert("<p><a href=\"#show_log\" id=\"show_log\">Show Log</a><a href=\"#reset_log\" id=\"reset_log\">Reset Log</a><a href=\"#show_roots\" id=\"show_roots\">Show Roots</a><a href=\"#ban_root\" id=\"ban_root\">Ban a root</a></p>");
				Event.observe("show_log", "click", function() {$("terminal_query").value += "show log"; $("exec_query").focus()});
				Event.observe("reset_log", "click", function() {$("terminal_query").value += "sql truncate table zombie_log"; $("exec_query").focus()});
				Event.observe("show_roots", "click", function() {$("terminal_query").value += "show roots"; $("exec_query").focus()});
				Event.observe("ban_root", "click", function() {$("terminal_query").value += "ban root USER for X days"; $("exec_query").focus()});
			break;
			default:
				$("context_menu").update("<p>"+p+"</p>");
			break;
		}
	}
	var handle = new Draggable('context_menu');
}
