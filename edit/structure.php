<?php
/*
See licence.txt for licence

Structure manages any thinkedit (or other) class in a node tree
You can add existing / add new / delete nodes
For this, you have a popup browser

INPUT :
- node_id : id of the current node
- object_ : object on which to do an action
- action : add, delete, moveup, movedown, movetop, movebottom

*/


/************************* INIT ****************************/

include_once('common.inc.php');

//check_user
check_user();

if ($url->get('info'))
{
		$out['info'] = translate($url->get('info'));
}


if ($url->get('error'))
{
		$out['error'] = translate($url->get('error'));
}


$current_node = $thinkedit->newNode();
if ($url->get('node_id'))
{
		$node_id = $url->get('node_id');
		$current_node->setId($node_id);
		if (!$current_node->load())
		{
				trigger_error('structure : node not found', E_USER_WARNING);
		}
		
}
else // we are in root
{
		
		if (!$current_node->loadRootNode())
		{
				die('No root node found : go to installer and create your first node');
		}
}


debug($current_node, 'Current node init');


/************************* ACTIONS ****************************/


/********************* Edit action **********************/


// handle adding new node from new record
if ($url->get('mode') == 'new_node')
{
		// todo : url loading of objects, universal object instancifier
		// done ??
		$url = $thinkedit->newUrl();
		$object = $url->getObject('object_');
		
		$current_node->add($object);
		$url->keep('node_id');
		//$url->debug();
		$url->redirect();
		
}



/********************* New node action **********************/
// handle adding new node directly
// would be called like this : structure.php?action=new_node&object_class=record&object_type=page&title=hello+world
if ($url->get('action') == 'new_node')
{
		$url = $thinkedit->newUrl();
		$object = $url->getObject('object_');
		$object->setTitle($url->get('title'));
		
		//echo $object->debug();
		
		$object->save();
		$current_node->add($object);
		$out['info'] = translate('node_added_sucessfully');
		
		$url->keep('node_id');
		$url->redirect();
}


/********************* Delete action **********************/
// handle deleting node
if ($url->get('action') == 'delete')
{
		$node_to_delete = $current_node;
		
		if ($node_to_delete->getParent())
		{
				if ($node_to_delete->delete())
				{
						$url->set('info', 'node_deleted_successfully');
						$url->redirect();
				}
				else
				{
						$url->set('error', 'node_not_deleted');
						$url->redirect();
				}
		}
		else
		{
				$out['error'] = translate('cannot_delete_root_node');
		}
		
}

/********************* Publish action **********************/

if ($url->get('action') == 'publish')
{
	if ($current_node->publish())
	{
		if ($url->get('output') == 'xml')
		{
			$out['result'] = 1;
			$out['node_status'] = 'published';
			$out['message'] = translate('node_published_successfully');
		}
		else
		{
			$url->set('info', 'node_published_successfully');
			$url->redirect();
		}
		
	}
	else
	{
		if ($url->get('output') == 'xml')
		{
			$out['result'] = 0;
			$out['message'] = translate('node_not_published');
		}
		else
		{
			$url->set('error', 'node_not_published');
			$url->redirect();
		}
	}
}


if ($url->get('action') == 'unpublish')
{
	if ($current_node->unPublish())
	{
		if ($url->get('output') == 'xml')
		{
			$out['result'] = 1;
			$out['node_status'] = 'unpublished';
			$out['message'] = translate('node_unpublished_successfully');
		}
		else
		{
			$url->set('info', 'node_unpublished_successfully');
			$url->redirect();
		}
		
	}
	else
	{
		if ($url->get('output') == 'xml')
		{
			$out['result'] = 0;
			$out['message'] = translate('node_not_unpublished');
		}
		else
		{
			$url->set('error', 'node_not_unpublished');
			$url->redirect();
		}
		
	}
	
}


/********************* Move action **********************/
if ($url->get('action') == 'moveup')
{
		//$node_to_move = $current_node;
		
		if ($current_node->moveUp())
		{
				$url->set('info', 'node_moved_successfully');
				$url->redirect();
				//$out['info'] = translate('node_moved_successfully');
		}
		else
		{
				$url->set('error', 'node_not_moved');
				$url->redirect();
				//$out['error'] = translate('node_not_moved');
		}
}


if ($url->get('action') == 'movedown')
{
		if ($current_node->moveDown())
		{
				$url->set('info', 'node_moved_successfully');
				$url->redirect();
		}
		else
		{
				$url->set('error', 'node_not_moved');
				$url->redirect();
		}
}


if ($url->get('action') == 'movetop')
{
		if ($current_node->moveTop())
		{
				$url->set('info', 'node_moved_successfully');
				$url->redirect();
		}
		else
		{
				$url->set('error', 'node_not_moved');
				$url->redirect();
		}
}

if ($url->get('action') == 'movebottom')
{
		if ($current_node->moveBottom())
		{
				$url->set('info', 'node_moved_successfully');
				$url->redirect();
		}
		else
		{
				$url->set('error', 'node_not_moved');
				$url->redirect();
		}
}


/********************* Clipboard action **********************/

include_once('../class/clipboard.class.php');
$clipboard = new clipboard();

if ($url->get('action') == 'cut')
{
	if ($clipboard->cut($current_node))
	{
		$out['info'] = translate('node_cut_ok');
	}
	
	else
	{
		$out['info'] = translate('node_cut_failed');
	}
	
}


if ($url->get('action') == 'copy')
{
	if ($clipboard->copy($current_node))
	{
		$out['info'] = translate('node_copy_ok');
	}
	
	else
	{
		$out['info'] = translate('node_copy_failed');
	}
	
}


if ($url->get('action') == 'paste')
{
	if ($clipboard->paste($current_node))
	{
		$out['info'] = translate('node_paste_ok');
	}
	
	else
	{
		$out['info'] = translate('node_paste_failed');
	}
	
}







/********************** Locale *********************/

$out['locales'] = $thinkedit->configuration->getLocaleList(); 



/********************** LIST *********************/

// new approach using open and close actions to nodes : 
$url = $thinkedit->newUrl();
$session = $thinkedit->newSession();
$opened_nodes = $session->get('opened_nodes');

if (!is_array($opened_nodes))
{
		$opened_nodes[] = 0;
}


if ($url->get('action') == 'open_node')
{
	$opened_nodes[] = $current_node->getId();
	$session->set('opened_nodes', $opened_nodes);
}

if ($url->get('action') == 'close_node')
{
	//$opened_nodes[] = $open_node;
	// when we close a node, we have to look if it is not the ancestor of some other opened nodes. If it's the case, we remove them as well
	foreach ($opened_nodes as $opened_node_id)
	{
		$opened_node = $thinkedit->newNode();
		$opened_node->setId($opened_node_id);
		if ($current_node->isAncestorOf($opened_node) || $current_node->getId() == $opened_node->getId())
		{
			// we remove it from the opened_node array
			//echo $opened_node->getId() . ' must be closed';
		}
		else
		{
			//echo $opened_node->getId() . ' can stay open';
			$new_opened_nodes[] = $opened_node_id;
		}
	}
	
	//$opened_nodes = array_values(array_diff($opened_nodes,array($current_node->getId())));
	$opened_nodes = $new_opened_nodes;
	$session->set('opened_nodes', $opened_nodes);
}


$root = $thinkedit->newNode();

$root->loadRootNode();

$nodes = $root->getAllChildren($opened_nodes);

if (isset($nodes) && is_array($nodes))
{
		$i = 0;
		foreach ($nodes as $node_item)
		{
				// reset node_info for next loop
				$node_info = false;
				
				/********************* Init *****************/
				
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$content = $node_item->getContent();
				$content->load();
				
				$node_info['id'] = $node_item->getId();
				$node_info['title'] = te_short($node_item->getTitle(), 60); // . ' (' . $node_item->getOrder() . ')';
				$node_info['full_title'] = $node_item->getTitle();
				$node_info['icon'] = $content->getIcon();
				$node_info['url'] = $url->render();
				$node_info['level'] = $node_item->getLevel();
				
				
				/********************* Visit link *****************/
				// a node can be "visited" only if it is allowed to add something inside it
				// else users could go inside a node and nothing could be done there
				if ($node_item->getAllowedItems())
				{
						
					if (in_array($node_item->getId(), $opened_nodes))
					{
						//$url = new url();
						$url->set('action', 'close_node');
						$node_info['visit_url'] = $url->render();
					}
					else
					{
						//$url = new url();
						$url->set('action', 'open_node');
						$node_info['visit_url'] = $url->render();
					}
				}
				
				
				
				
				/********************* Delete link *****************/
				$url->set('action', 'delete');
				$node_info['delete_url'] = $url->render();
				
				
				/********************* Move links *****************/
				
				$url->set('action', 'moveup');
				$node_info['moveup_url'] = $url->render();
				
				$url->set('action', 'movetop');
				$node_info['movetop_url'] = $url->render();
				
				$url->set('action', 'movedown');
				$node_info['movedown_url'] = $url->render();
				
				$url->set('action', 'movebottom');
				$node_info['movebottom_url'] = $url->render();
				
				/********************* Edit link *****************/
				
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$node_info['edit_url'] = $url->render('edit.php');
				
				/********************* Publish link *****************/
				if ($node_item->isPublished())
				{
						$url = new url();
						$url->set('node_id', $node_item->getId());
						$url->set('action', 'unpublish');
						$node_info['publish_url'] = $url->render();
						$node_info['publish_title'] = translate('unpublish');
						$node_info['published'] = 1;
						
				}
				else
				{
						$url = new url();
						$url->set('node_id', $node_item->getId());
						$url->set('action', 'publish');
						$node_info['publish_url'] = $url->render();
						$node_info['publish_title'] = translate('publish');
						$node_info['published'] = 0;
				}
				
				
				/********************* Preview link *****************/
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$node_info['preview_url'] = $url->render('../index.php');
				$node_info['preview_title'] = translate('preview');
				
				
				/********* node is current ? *******/
				if ($node_item->getId() == $current_node->getId())
				{
						$node_info['is_current'] = true;
				}
				
				/************************ Allowed items ************************/
				
				$item = array();
				
				$allowed_items = $node_item->getAllowedItems();
				if (is_array($allowed_items))
				{
					foreach ($allowed_items as $allowed_item)
					{
						if ($allowed_item['class'] == 'record')
						{
							$table = $thinkedit->newTable($allowed_item['type']);
							$item['title'] = $table->getTitle();
							$item['icon'] = $table->getIcon();
							$url = new url();
							$url->set('mode', 'new_node');
							$url->set('node_id', $node_item->getId());
							$url->addObject($table);
							$item['action'] = $url->render('edit.php');
							
							$url = new url();
							$url->set('action', 'new_node');
							$url->set('node_id', $node_item->getId());
							$tmp_record = $thinkedit->newRecord($table->getId()); 
							$url->addObject($tmp_record, 'object_');
							$item['direct_add_action'] = $url->render();
							
							
							$node_info['allowed_items'][] = $item;
						}
					}
					
				}
				
				/******* clipboard links ****/
				
				
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$url->set('action', 'cut');
				$node_info['clipboard']['cut_link'] = $url->render();
				
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$url->set('action', 'copy');
				$node_info['clipboard']['copy_link'] = $url->render();
				
				
				$url = new url();
				$url->set('node_id', $node_item->getId());
				$url->set('action', 'paste');
				$node_info['clipboard']['paste_link'] = $url->render();
				
				
				/******* locales links ****/
				if ($content->isMultilingual())
				{
						$locales = $thinkedit->configuration->getLocaleList();
						foreach ($locales as $locale)
						{
								$url = new url();
								$url->set('node_id', $node_item->getId());
								$content->setLocale($locale);
								$url->addObject($content);
								$url->set('mode', 'edit_node');
								$node_info['locale'][$locale]['edit_url'] = $url->render('edit.php');
								$node_info['locale'][$locale]['locale'] = $locale;
						}
				}
				
				/******* opened / closed / empty class ****/
				if ($node_item->hasChildren())
				{
					if (in_array($node_item->getId(), $opened_nodes))
					{
						$node_info['status'] = 'opened';
					}
					else
					{
						$node_info['status'] = 'closed';
					}
				}
				else
				{
					$node_info['status'] = 'empty'; 
				}
				
				
				
				/******* append this node info to out nodes list ****/
				$out['nodes'][] = $node_info;
				$i++;
		}
		
}

// build a breadcrumb of parent items
// add breadcrumb
$url = new url();
$out['breadcrumb'][1]['title'] = translate('structure_title');
$out['breadcrumb'][1]['url'] = $url->render();


/************************* TEMPLATES / RENDER TO XML ****************************/

debug($out, 'OUT');

$url = new url();
if ($url->get('output') == 'xml')
{
	header("Content-Type: text/xml");
	echo (array_to_xml($out));
}
else
{
	// include template :
	include('header.template.php');
	include('structure.template.php');
	include('footer.template.php');
}




?>
