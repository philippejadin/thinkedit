$(document).ready(function()
{
	
	/*************** MENU ***************/
	previous_menu = false;
	$(".menu").click(function(event)
	{
		//$(this).next('.menu').slideDown('fast');
		if (previous_menu)
		{
			previous_menu.hide();
		}
		previous_menu = $('.menu_items', this).show();
		event.stopPropagation();
	});
	
	$(document).click(function()
	{
		if (previous_menu)
		{
			previous_menu.hide();
		}
	});
	
	
	/*************** PUBLISH ***************/
	
	// not yet but almost there
	/*
	$("a.structure_publish").click(function(event)
	{
		link = this;
		url = $(this).href() + "&output=xml";
		//alert(url);
		
		$.ajax({
			type: 'GET',
			url: url,
			success: function(data){
				
				//alert(data);
				//return false;
				message =  $('message', data).text();
				result = $('result', data).text();
				
				if (result == 1)
				{
					node_status = $('node_status', data).text();
					if (node_status=='published')
					{
						$('img', link).removeClass('disabled');
					}
					if (node_status=='unpublished')
					{
						$('img', link).addClass('disabled');
					}
					
					
				}
				else
				{
					alert(message);
				}
			}
		});
		
		return false;
		
	});
	*/
	
});


