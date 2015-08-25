Event.observe(window, "load", function()
{
	if($("search_box"))
	{
		Event.observe("show_search", "click", function(){Effect.toggle($("search_box"),"slide", {duration: 0.5}); $("query").focus()});
		if($$(".article_body")) $("query").focus(); expandable_results();
		//var auto = new Ajax.Autocompleter("query", "auto_complete", "ajax/auto_complete.php", {minChars: 4, paramName: "query", parameters: "type="+$F("type"), method: "get"});
		if($("search")) $("search_box").style.display = "block";
	}
	if($("bad_notice")) { notify("bad_notice"); }
	if($("good_notice"))  { notify("good_notice"); }
	if($$(".close_link").length > 0) $$(".close_link").each(function(n){Event.observe(n, "click", function(){var hide = new Effect.toggle(n.parentNode.parentNode, "slide", {duration: 0.5});})});
	if($("login")) {
		Event.observe($("user"), "focus", function() { if($F("user") == "Username") $("user").value = ""; }, false);
		Event.observe($("pwd"), "focus", function() { if($F("pwd") == "Password") $("pwd").value = ""; }, false);
		Event.observe($("user"), "blur", function() { if($F("user") == "") $("user").value = "Username"; }, false);
		Event.observe($("pwd"), "blur", function() { if($F("pwd") == "") $("pwd").value = "Password"; }, false);
		$("user").focus();
	}
	else if($("terminal")) init_terminal();
	else if($("blog") && $("article_form")){
		init_groups();
		init_zombiecode();
		Event.observe($("show_creativemode"), "click", creative_mode, false);
	}
	else if($("roots") || $("files")){
		init_tabs();
		if($("add_root_btn")) Event.observe($("add_root_btn"), "click", function(){validate("add_root");}, false);
		if($("search_root_btn")) Event.observe($("search_root_btn"), "click", function(){validate("search_root");}, false);
	}
},false);

function expandable_results(){
	var rels = $$(".search_result");
	var bodies = $$(".article_body");
	rels.each(function(n, index){
		Event.observe(n, "click", function(){
			var show = new Effect.toggle(bodies[index], 'blind', {duration: 0.5});
		});
	});
}

function validate(_form)
{
	switch(_form)
	{
		case "add_root":
			var inputs = $("add_root_form").getInputs();
			//input map: username, pass, retype pass, email, SUBMIT BUTTON (yes, the submit counts as an input")
			var errors = new Array(false, false, false);
			//error map: mandatory fields missing, password missmatch, email failed validation
			var passed = true;
			for(var i = 0; i<inputs.length-1; i++) {
				if(inputs[i].value.length < 3) errors[0] = true;
			}
			if(inputs[1].value != inputs[2].value) errors[1] = true;
			else errors[1] = false;
			if(!inputs[3].value.match(/(_)?([\d\S]){1,}(_-+.[\d\S])?@[\d\S]{1,}(\.)[a-z]{2,5}(\.[a-z])?/)) errors[2] = true;
			var error_info = "Error:";
			if(errors[0]) error_info += "\nMandatory fields left empty, or without enough information.";
			if(errors[1]) error_info += "\nPassword Missmatch";
			if(errors[2]) error_info += "\nEmail failed validation";
			if(errors[0] || errors[1] || errors[2]) alert(error_info);
			if(!errors[0] && !errors[1] && !errors[2])	$("add_root_form").request({onComplete: function(){location.reload(true);}});
		break;
		default:
			alert("form is not specified");
		break;
	}
}

function init_zombiecode()
{
	var links = $$("#zombiecode_panel a img");
	links.each(function(n){
		n.onmouseup = function() {
			switch(n.getAttribute("alt"))
			{
				case "bold": $("body").value += "[b][/b]"; break;
				case "underline": $("body").value += "[u][/u]"; break;
				case "italic": $("body").value += "[i][/i]"; break;
				case "align_left": $("body").value += "[left][/left]"; break;
				case "align_center": $("body").value += "[center][/center]"; break;
				case "align_right": $("body").value += "[right][/right]"; break;
				case "paragraph": $("body").value += "\n\n"; break;
				case "unordered_list": $("body").value += "[list]\n*item\n*another item\n[/list]"; break;
				case "ordered_list": $("body").value += "[number list]\n*item\n*another item\n[/number list]"; break;
			}
			$("body").focus();
		}
	});
}

function creative_mode()
{
	var hide_creative_mode = function() {
		$("body").value = $("creativeroom_text").value;
		$("creativemode").remove();
		$("body").focus();
	}
	var dims = $("blog").getDimensions();
	var exit = "<div style=\"position: absolute; top: 0px; left: 0px; width: 150px; height: 22px;\"><a href=\"#empty\" id=\"exit_creativemode\" class=\"white\" style=\"font-family: 'Lucida Console';\">Exit (Esc)</a></div>";
	var room = "<div id=\"creativemode\" style=\"width: "+dims.width+"px; height: "+dims.height+"px; \"><textarea rows=\"90\" cols=\"40\" style=\"position: relative; border: 0px; top: 0px; left: 0px; overflow: hidden; width: 80%; height: 100%; margin: 0px auto; background-color: #000; color: #62b914; font-size: 15px; padding: 10px;\" id=\"creativeroom_text\"></textarea></div>";
	$("blog").insert(room, {position: "top"});
	$("creativemode").insert(exit, {position: "top"});
	$("creativeroom_text").value = $("body").value;
	$("creativeroom_text").focus();
	Event.observe($("exit_creativemode"), "click", hide_creative_mode,false);
	//creds to some site for the catching of the esc key code...
	Event.observe($("creativeroom_text"), "keyup", function(e){
		var kC  = (window.event) ? event.keyCode : e.keyCode;
		var Esc = (window.event) ? 27 : e.DOM_VK_ESCAPE;
		if (kC == Esc) hide_creative_mode();
	}, false);
}

function notify(whatness)
{
	if(whatness == "bad_notice") {
		var settings = {startcolor: "#ffffff", endcolor: "#912a2a"};
		Event.observe($("bad_notice"), "click", function(){$("bad_notice").fade()},false);
	}
	else {
		var settings = {startcolor: "#ffffff", endcolor: "#236639"};
		Event.observe($("good_notice"), "click", function(){$("good_notice").fade()},false);
	}
	var noti = new Effect.Highlight(whatness, settings);
}

function init_groups()
{
	var selected_groups = new Array();
	var groups = $$("#blog #main form ul li a");
	for(var i = 0; i<groups.length; i++)
		if(groups[i].hasClassName("selected")) selected_groups.push(groups[i].getAttribute("rel"));
	for(var i = 0; i<groups.length; i++)
	{
		Event.observe(groups[i], "click", function(){
			var bFound = false;
			var l = selected_groups.length;
			var rel = this.getAttribute("rel");
			for(var a = 0; a<l; a++) if(selected_groups[a] == rel)	bFound = true;
			if(!bFound) {
				selected_groups.push(rel);
				this.addClassName("selected");
			}
			else {
				selected_groups.pop(rel);
				this.removeClassName("selected");
			}
		},false);
	}
}

function init_tabs()
{
	var color = $$(".tabs")[0].getAttribute("title");
	var links = $$(".tabs a");
	var tabs = $$(".tabs .tab");
	links[0].addClassName(color+"_active");
	var content = $$(".tabbed_content");
	content[0].style.display = "block";
	for(var i = 0; i<links.length; i++) {
		Event.observe(links[i], "click", function(){
			for(var a = 0; a<links.length; a++) {
				content[a].style.display = "none";
				links[a].removeClassName(color+"_active");
			}
			$(this.getAttribute("id")).addClassName(color+"_active");
			$("content_"+this.getAttribute("id")).style.display = "block";
		},false);
	}
}

function init_terminal()
{
	var old_query = " ";
	$("terminal_query").focus();
	var query = $F("terminal_query");
	Event.observe($("exec_query"), "click", function() {
		if(old_query != "" || $F("terminal_query").length < old_query.length)
		{
			if(old_query.match(/>>/))
				var new_query = "query="+$F("terminal_query").substr(1,$F("terminal_query").length);
			else
				var new_query = "query="+$F("terminal_query").substr(old_query.length, $F("terminal_query").length);
		}
		else new_query = "query="+$F("terminal_query");
		if(new_query.match(/clear|CLEAR|cls|CLS/)) {
			old_query = "clear";
			$("terminal_query").focus();
			$("terminal_query").value = ">";
			var really = false;
		}
		else if(new_query.match(/^sql|^SQL/) && new_query.match(/truncate|TRUNCATE|drop|DROP/)) var really = confirm("are you sure you want to "+new_query.substr(10,new_query.length).toUpperCase());
		if(really != false) {
			$("console").request({parameters: new_query, onComplete: function(transport){
					$("terminal_query").value += transport.responseText + ">";
					old_query = $F("terminal_query");
					$("terminal_query").focus();
			}});
		}
		else {
			old_query = $F("terminal_query") + ">";
			$F("terminal_query").value += "\n>";
			$("terminal_query").focus();
		}
	}, false);
}