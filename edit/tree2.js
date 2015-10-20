$('document').ready(function()
{
	$("#loader").hide();
	
	$("#loader").ajaxStart(function(){
		$(this).show();
	});
	
	$("#loader").ajaxStop(function(){
		$(this).hide();
	});
	
	init_nodes();
}
);




function load_node(id)
{
	if (id)
	{
		data = "action=node_info&node_id=" + id;
	}
	else
	{
		data = "action=node_info";
	}

	$.ajax(
	{
		type: "POST",
		url: "api.php",
		data: data,
		success: function(data)
		{
			message =  $('message', data).text();
			result = $('result', data).text();
			
			if (result == 1)
			{
				$('#node_' + id).append('<ul></ul>');
				
				$('children', data).each(function()
				{
					$('#node_' + id + ' ul').append('<li>' +  $('title', this).text() + '</li>');
					$('#node_' + id + ' ul li').click(load_node($('id', this).text()));
				});
				
			}
			else
			{
				alert (message);
			}
		}
	});
	/*
	$('#node_' + id).append('<ul></ul>');
	$('#node_' + id + ' ul').load('node.php?node_id=' + id, init_nodes);
	$('#node_' + id).attr('loaded', 1);
	*/
}



$(document).ready(function()
{
	handle_node_publish();
});


function get_node_id(node_id)
{
	id = node_id.substring(5, node_id.length);
	return id;
}


function handle_node_publish()
{
	$('a.published').unclick();
	$('a.published').click(function()
	{
		button = this;
		node_id = get_node_id($(this).parents('.node').id());
		
		//alert('publish ' + node_id);
		$.ajax({
			type: "POST",
			url: "api.php",
			data: "action=node_unpublish&node_id=" + node_id,
			success: function(data){
				/*alert( "Data Saved: " + msg );*/
				message =  $('message', data).text();
				result = $('result', data).text();
				
				if (result == 1)
				{
					//alert (message);
					$('img', button).src('ressource/image/icon/small/lightbulb_off.png');
					$(button).addClass("unpublished");
					$(button).removeClass("published");
					handle_node_publish()
				}
				else
				{
					alert (message);
				}
			}
		});
		
		return false;
	});
	
	$('a.unpublished').unclick();
	$('a.unpublished').click(function()
	{
		button = this;
		node_id = get_node_id($(this).parents('.node').id());
		
		//alert('publish ' + node_id);
		$.ajax({
			type: "POST",
			url: "api.php",
			data: "action=node_publish&node_id=" + node_id,
			success: function(data){
				/*alert( "Data Saved: " + msg );*/
				message =  $('message', data).text();
				result = $('result', data).text();
				if (result == 1)
				{
					//alert (message);
					$('img', button).src('ressource/image/icon/small/lightbulb.png');
					$(button).addClass("published");
					$(button).removeClass("unpublished");
					handle_node_publish()
				}
				else
				{
					alert (message);
				}
			}
		});
		
		return false;
	});
	
}



function handle_node_clipboard()
{
	$('a.cut_button').unclick();
	$('a.copy_button').unclick();
	$('a.paste_button').unclick();
	
	$('a.cut_button').click(function()
	{
		button = this;
		node_id = get_node_id($(this).parents('.node').id());
		
		params = {action: "node_cut", node_id : node_id }; 
		
		$.post("api.php", params, function(data)
		{
			message =  $('message', data).text();
			result = $('result', data).text();
			$(".context_menu").hide();
			
			if (result == 1)
			{
				te_info(message);
			}
			else
			{
				te_error(message);
			}
			
		});
		return false;
	});
	
	$('a.copy_button').click(function()
	{
		button = this;
		node_id = get_node_id($(this).parents('.node').id());
		
		params = 
		{
			action: "node_copy", 
			node_id : node_id 
		}; 
		
		$.post("api.php", params, function(data)
		{
			message =  $('message', data).text();
			result = $('result', data).text();
			
			$(".context_menu").hide();
			
			if (result == 1)
			{
				te_info(message);
			}
			else
			{
				te_error(message);
			}
			
		});
		return false;
	});
	
	$('a.paste_button').click(function()
	{
		button = this;
		node_id = get_node_id($(this).parents('.node').id());
		
		params = 
		{
			action: "node_paste", 
			node_id : node_id 
		}; 
		
		$.post("api.php", params, function(data)
		{
			message =  $('message', data).text();
			result = $('result', data).text();
			
			$(".context_menu").hide();
			
			if (result == 1)
			{
				reload_node(node_id);
				te_info(message);
			}
			else
			{
				te_error(message);
			}
			
		});
		return false;
	});
	
}




function handle_node_context_menu()
{
	$('html').click(function()
	{
		$(".context_menu").hide();
	});
	// add click handlers
	// bind context menu event
	$('.node').bind( "contextmenu", function() 
	{
		$(".context_menu").hide();
		/*
		node_id = $(this).id();
		id = node_id.substring(5, node_id.length);
		showContextMenu('context_menu_node_' + id, this);
		//alert( 'context clicked' );
		*/
		
		menu = $(this).children(".context_menu");
		menu.top(Event.pointerY(this) + 'px');
		menu.left(Event.pointerX(this) + 'px');
		menu.show();
		return false;
	});
}


function reload_node(id)
{
	$('#node_' + id).attr('loaded', 0);
	$('#node_' + id + " ul").remove();
	load_node(id);
}
/*
function load_node(id)
{
	if ($('#node_' + id).attr('loaded') == 1)
	{
		$('#node_' + id + ' ul').toggle();
	}
	else
	{
		$('#node_' + id).append('<ul></ul>');
		$('#node_' + id + ' ul').load('node.php?node_id=' + id, init_nodes);
		$('#node_' + id).attr('loaded', 1);
	}
}
*/
function init_nodes()
{
	// remove all click handlers
	$('.node .icon').unclick();
	
	// add open close click handler
	$('.node .icon').click(function()
	{
		node_id = $(this).parent().id();
		id = node_id.substring(5, node_id.length);
		/*alert ('clicked on ' + id );*/
		load_node(id);
		return false;
	});
	
	handle_node_context_menu();
	handle_node_publish();
	handle_node_clipboard();
}

