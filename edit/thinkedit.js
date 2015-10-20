// javascript is not my cup of tea
// but it may change, thanks to jquery (jquery.com)
// global todo : use unobstrusive html classes to trigger javascript




/************************** Various page wide stuff *******************************/

// nicer isn't it : we use jquery to hide loading bar when doc is ready :
$(document).ready(function()
{
	$("#loading").hide();
});


/************************** Thinkedit api *******************************/
// todo ;-)
function te_api_call(action, params)
{
	$.post("api.php?action=" + action, params,function(xml)
	{
		alert( $("message",xml).text() );
		return xml;
	});
	
	/*
	$.ajax({
		type: "POST",
		url: "api.php",
		data: "action=" + action + "params",
		success: function(data){
			message =  $('message', data).text();
			result = $('result', data).text();
			if (result == 1)
			{
				// todo
				
			}
			else
			{
				alert (message);
			}
		}
	});
	*/
}

/************************** User information *******************************/
function te_info(message)
{
	//alert(message);
	$('#info').html(message).show();
}

function te_error(message)
{
	//alert(message);
	$('#error').html(message).show();
}

/************************** Popups *******************************/

/* from http://www.quirksmode.org/js/croswin.html */
function popup(url, name)
{
	var newwindow = window.open(url,name,'height=400,width=500,scrollbars=yes,resizable=yes,modal=yes');
	//window.open(url,'name','height=400,width=500,scrollbars=yes,resizable=yes,modal=yes');
	
	
	if (!newwindow.opener)
	{
		newwindow.opener = self;
	}
	
	if (window.focus) 
	{
		newwindow.focus();
	}
	
	return false;
}


function custompopup(url, name, size)
{
	//alert(window.title);
	
	if(!size)
	{
		size = 50;
	}
	w = screen.width * size / 100;
	
	
	h = screen.height * size / 100;
	var winl = (screen.width-w)/2;
	var wint = (screen.height-h)/2;
	var settings ='height='+h+',';
	settings +='width='+w+',';
	settings +='top='+wint+',';
	settings +='left='+winl+',';
	settings +='scrollbars=yes,';
	settings +='resizable=yes';
	var newwindow=window.open(url,'name',settings);
	//window.open(url,name,settings);
	
	if (!newwindow.opener)
	{
		newwindow.opener = self;
	}
	
	if (window.focus) 
	{
		newwindow.focus()
	}
	
	return false;
}



/************************** Context menu *******************************/
document.onclick=hideMenu;
previous_menu = false;
function showContextMenu(id, event)
{
	hideMenu();
	menu = $('#' + id);
	menu.top(Event.pointerY(event) + 'px');
	menu.left(Event.pointerX(event) + 'px');
	menu.show();
	previous_menu = menu;
	
}

i = 0;
function timeOutMenu()
{
	if (i>0)
	{
		if (previous_menu)
		{
			previous_menu.hide();
		}
		i=0;
	}
	i++;
}


function hideMenu()
{
	if (previous_menu)
	{
		previous_menu.hide();
	}
}




/************************** Popup communication *******************************/
function to_opener(url)
{
	opener.location.href = url;
}

function reload_opener()
{
	opener.location.reload();
}

function self_close()
{
	self.close();
}

function popup_save()
{
	reload_opener();
	self_close();
}

function popup_cancel()
{
	self_close();
}


function edit_save()
{
	self.close();
}


function to_opener_field(field, value)
{
	window.opener.document.edit_form[field].value = value;
}



/************************** Dialog boxes *******************************/
function confirm_link(message, url)
{
	input_box=confirm(message);
	if (input_box==true)
		
	{ 
		// Output when OK is clicked
		window.location.href=url; 
	}
	
	else
	{
		return false;
	}
	
}

function jump(targ,selObj,restore)
{ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}


function ask_title(targ,selObj,restore,text)
{ 
	//v3.0
	title =  prompt(text);
	if (title)
	{
		encoded_title = encodeURIComponent(title);
		//encoded_title = escape(title);
		//encoded_title.replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27').replace(/\//g,'%2F');
		var result = "";
		var length = encoded_title.length;
		for (var i = 0; i < length; i++) 
		{
			var ch = encoded_title.charAt(i);
			switch (ch) 
			{
				case "'":
				result += "%27";
				break;
				default:
				result += ch;
			}
		}
		
		eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&title=" + result +"'");
	}
	if (restore) selObj.selectedIndex=0;
}



function ask_title2(text, url)
{ 
	//v3.0
	title =  prompt(text);
	if (title)
	{
		encoded_title = encodeURIComponent(title);
		//encoded_title = escape(title);
		//encoded_title.replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27').replace(/\//g,'%2F');
		var result = "";
		var length = encoded_title.length;
		for (var i = 0; i < length; i++) 
		{
			var ch = encoded_title.charAt(i);
			switch (ch) 
			{
				case "'":
				result += "%27";
				break;
				default:
				result += ch;
			}
		}
		
		eval("parent.location='"+url+"&title=" + result +"'");
	}
	return false;
	
}



// This will soon be deprecated, we will use ajax for partial page loading
function adjustIFrameSize (iframeWindow) 
{
	if (iframeWindow.document.height) {
		var iframeElement = parent.document.getElementById(iframeWindow.name);
		iframeElement.style.height = iframeWindow.document.height + 50 + 'px';
    }
	else if (document.all) {
		var iframeElement = parent.document.all[iframeWindow.name];
		if (iframeWindow.document.compatMode && iframeWindow.document.compatMode != 'BackCompat') 
		{
			iframeElement.style.height = iframeWindow.document.documentElement.scrollHeight +  50 +  'px';
			
		}
		else {
			iframeElement.style.height = iframeWindow.document.body.scrollHeight  + 50 +  'px';
		}
	}
}
