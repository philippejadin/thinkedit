<?php
/*
Thinkedit API


Input :
- action
- node_publish
- node_unpublish
- node_move
- node_rename

- format (json, xml, php, ...)
+ any other parameters 


Output :
- will return a php/xml/json structure in the asked format
Curently only xml is sent.

Root tag/aray key/ is <thinkedit> 

True is converted to 1
False is converted to 0

*/

require_once 'common.inc.php';
// disable error reporting, we are handling this in the xml outputed
ini_set('display_errors', false);

check_user();

$action = $url->get('action');
$format = $url->get('format');
$results = false;
$out = false;

/************************** Actions ***********************/
// all actions are placed here :
if ($action == 'node_publish')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		$node->load($url->get('node_id'));
		
		if ($node->publish())
		{
			$out['result'] = true;
			$out['message'] = translate('api_node_published');
		}
		else
		{
			$out['result'] = false;
			$out['message'] = translate('api_node_not_published');
		}
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_no_node_id_defined');
	}
	
}
elseif ($action == 'node_unpublish')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		$node->load($url->get('node_id'));
		
		if ($node->unpublish())
		{
			$out['result'] = true;
			$out['message'] = translate('api_node_unpublished');
		}
		else
		{
			$out['result'] = false;
			$out['message'] = translate('api_node_not_unpublished');
		}
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_no_node_id_defined');
	}
	
}
elseif ($action == 'node_cut')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		$clipboard = $thinkedit->getClipboard();
			
		$node->load($url->get('node_id'));
		
		if ($clipboard->cut($node))
		{
			$out['result'] = true;
			$out['message'] = translate('node_cut_ok');
		}
		else
		{
			$out['result'] = false;
			$out['message'] = translate('node_cut_failed');
		}
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_no_node_id_defined');
	}
	
}
elseif ($action == 'node_copy')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		$clipboard = $thinkedit->getClipboard();
			
		$node->load($url->get('node_id'));
		
		if ($clipboard->copy($node))
		{
			$out['result'] = true;
			$out['message'] = translate('node_copy_ok');
		}
		else
		{
			$out['result'] = false;
			$out['message'] = translate('node_copy_failed');
		}
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_no_node_id_defined');
	}
	
}
elseif ($action == 'node_paste')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		$clipboard = $thinkedit->getClipboard();
			
		$node->load($url->get('node_id'));
		
		if ($clipboard->paste($node))
		{
			$out['result'] = true;
			$out['message'] = translate('node_paste_ok');
		}
		else
		{
			$out['result'] = false;
			$out['message'] = translate('node_paste_failed');
		}
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_no_node_id_defined');
	}
	
}
elseif ($action == 'node_info')
{
	$node = $thinkedit->newNode();
	if ($url->get('node_id'))
	{
		if ($node->load($url->get('node_id')))
		{
		}
		else
		{
			$node = false;
		}
	}
	else
	{
		$node->loadRootNode();
	}
	
	if ($node)
	{
		// get basic node info
		$node_info['title'] = $node->getTitle();
		$node_info['icon'] = $node->getIcon();
		
		// get allowed items
		if ($allowed_items = $node->getAllowedItems())
		{
			foreach ($allowed_items as $allowed_item)
			{
				$node_info['allowed_items'] = $allowed_item['type'];
			}
		}
		
		// get children
		if ($children = $node->getChildren())
		{
			foreach ($children as $child)
			{
				$child_info['title'] = $child->getTitle();
				$child_info['icon'] = $child->getIcon();
				$child_info['id'] = $child->getId();
				$node_info['children'][] = $child_info; 
			}
		}
		
		
		$out['node'][] = $node_info;
		$out['result'] = true;
		$out['message'] = translate('api_node_info_done');
	}
	else
	{
		$out['result'] = false;
		$out['message'] = translate('api_node_not_found');
	}
}
else
{
	$out['message'] = translate('api_unknown_action requested');
	$out['result'] = false;
}


/************************** output results ***********************/
header("Content-Type: text/xml");
echo array_to_xml($out);



?>
