<?php
/*
See licence.txt for licence


The browser let's you browse anything from Thinkedit

With it you can easily select something and append it to a parent window

It will be used for 
- relations
- image fields
- add existing content to node


input : 

- start point / start content type
- limit to some content types ? 

- class
- type

- mode : mode defiens the way the browser will send back results.
if mode = relation :
will reload relation iframe


field : the field to update from caller, using javascript



other
- pages
- path (for filemanager)


What to do?
- execute javascript on parent window with UID
- return to / reload new page

*/

include_once('common.inc.php');

//check_user
check_user();

// Load parameters from url or session :

// Class
if  ($url->get('class'))
{
		$class = $url->get('class');
		$session->set('browser_class', $class);
		
}
elseif ($session->get('browser_class'))
{
		$class = $session->get('browser_class');
}
else
{
		$class = false;
}

// Path
if  ($url->get('path'))
{
		$path = $url->get('path');
		$session->set('browser_path', $path);
		
}
elseif ($session->get('browser_path'))
{
		$path = $session->get('browser_path');
}
else
{
		$path = false;
}


// Node ID
if  ($url->get('node_id'))
{
		$node_id = $url->get('node_id');
		$session->set('browser_node_id', $node_id);
		
}
elseif ($session->get('browser_node_id'))
{
		$node_id = $session->get('browser_node_id');
}
else
{
		$node_id = false;
}

//echo 'node_id ' . $node_id;

if ($url->get('mode') == 'field')
{
		$mode = 'field';
		$url->keep('mode');
		$url->keep('field');
		$out['mode'] = 'field';
}


if ($url->get('mode') == 'relation')
{
		$mode = 'relation';
		$url->keep('mode');
		$out['mode'] = 'relation';
}


// check type
$type = $url->get('type');





/*************************** First dropdown ***********/
$out['dropdown']['class']['data'][0]['title'] = ucfirst(translate('file'));
$url->set('class', 'file');
$out['dropdown']['class']['data'][0]['url'] = $url->render();
if ($class=='file')
{
		$out['dropdown']['class']['data'][0]['selected'] = true;
}

$out['dropdown']['class']['data'][1]['title'] = ucfirst(translate('content'));
$url->set('class', 'table');
$out['dropdown']['class']['data'][1]['url'] = $url->render();
if ($class=='table')
{
		$out['dropdown']['class']['data'][1]['selected'] = true;
}



$out['dropdown']['class']['data'][2]['title'] = ucfirst(translate('node'));
$url->set('class', 'node');
$out['dropdown']['class']['data'][2]['url'] = $url->render();
if ($class=='node')
{
		$out['dropdown']['class']['data'][2]['selected'] = true;
}


/*************************** Table dropdown ***********/
if ($class=='table')
{
		$config = $thinkedit->newConfig();
		$tables = $config->getTableList();
		foreach ($tables as $table)
		{
				$table_object = $thinkedit->newTable($table);
				$out['dropdown']['type']['data'][$table]['title'] = $table_object->getTitle();
				$url->set('class', 'table');
				$url->set('type', $table);
				$out['dropdown']['type']['data'][$table]['url'] = $url->render();
				if ($type==$table)
				{
						$out['dropdown']['type']['data'][$table]['selected'] = true;
				}
		}
}

/*************************** File dropdown ***********/

if ($class=='file')
{
		$filesystem = $thinkedit->newFilesystem();
		$folders = $filesystem->getFolderListRecursive();
		debug($folders, 'folders');
		if ($folders)
		{
				foreach ($folders as $folder)
				{
						$folder_out = '';
						$url->set('path', $folder->getPath());
						$url->set('class', 'file');
						$folder_out['title'] = $folder->getPath();
						$folder_out['url'] = $url->render();
						if ($folder->getPath() == $path)
						{
								$folder_out['selected'] = 1;
						}
						$out['dropdown']['path']['data'][] = $folder_out;
				}
		}
}

/*************************** Files items ***********/
if ($class=='file' && $path)
{
		$filesystem = $thinkedit->newFilesystem();
		$filesystem->setPath($path);
		
		if ($url->get('action') == 'upload_file')
		{
				if ($filesystem->addFileFromUpload('uploaded_file'))
				{
						$out['info'] = translate('file_added_successfully');
				}
				else
				{
						$out['error'] = translate('file_added_failed');
				}
		}
		
		// handle uploading of files
		$out['enable_upload'] = true;
		// define action buttons urls
		$url = new url();
		$url->keep('mode');
		$url->set('action', 'add_folder');
		$out['add_folder_url'] = $url->render();
		
		$url = new url();
		$url->keep('mode');
		$url->set('action', 'upload_file');
		$out['upload_file_url'] = $url->render();
		
		
		
		
		$childs = $filesystem->getFiles();
		
		if ($childs)
		{
				foreach ($childs as $child)
				{
						$item['title'] = $child->getFilename();
						$item['icon'] = $child->getIcon();
						$item['url'] = $url->render('browser.php'); // todo default (?)
						
						if ($mode == 'relation')
						{
								$url->addObject($child, 'target_');
								$url->set('action', 'relate');
								$item['url'] = $url->render('relation.php');
						}
						if ($mode == 'field')
						{
								$item['field'] = $url->get('field');
								$item['value'] = $child->getPath();
						}
						$out['items'][] = $item;
				}
		}
		
		
		
		
}


/*************************** Record items ***********/
if ($class=='table' && $type)
{
		$record = $thinkedit->newRecord($type);
		$records = $record->find();
		
		if ($records)
		{
				foreach ($records as $content)
				{
						$item['title'] = te_short($content->getTitle(), 40);
						$item['icon'] = $content->getIcon();
						$url->addObject($content, 'target_');
						$item['url'] = $url->render('relation.php');
						if ($mode == 'relation')
						{
								$url->addObject($content, 'target_');
								$url->set('action', 'relate');
								$item['url'] = $url->render('relation.php');
						}
						
						
						$out['items'][] = $item;
				}
		}
}


/*************************** Node items ***********/
if ($class=='node')
{
		$current_node = $thinkedit->newNode();
		if ($node_id)
		{
				$current_node->setId($node_id);
				
				$we_are_root = false;
				if (!$current_node->load())
				{
						trigger_error('structure : node not found');
						$current_node->loadRootNode();
						$we_are_root = true;
				}
				
				
				$parent_node = $current_node->getParent();
		}
		else // we are in root
		{
				$current_node->loadRootNode();
				$we_are_root = true;
		}
		
		
		
		if ($parent_node = $current_node->getParent())
		{
			$url = new url();
			$url->keep('class');
			$url->keep('mode');
			$url->set('node_id', $parent_node->getId());
			$out['parent_url'] = $url->render();
		}
		else
		{
			$url = new url();
			$url->keep('class');
			$url->keep('mode');
			$url->set('node_id', '');
			$out['parent_url'] = $url->render();
		}
		
		
		// build a list of nodes within the current node :
		// if we are in root
		if ($we_are_root)
		{
				$nodes[] = $current_node;
		}
		//else we are not in root, show childrens 
		else
		{
				if ($current_node->hasChildren())
				{
						$nodes = $current_node->getChildren();
				}
		}
		
		if (isset($nodes) && is_array($nodes))
		{
				$i = 0;
				foreach ($nodes as $node_item)
				{
						$content = $node_item->getContent();
						$content->load();
						$item['title'] = te_short($content->getTitle(), 40);
						$item['icon'] = $content->getIcon();
						$url = new url();
					  $url->keep('class');
						$url->keep('mode');
						$url->set('node_id', $node_item->getId());
						$item['visit_url'] = $url->render();
						if ($mode == 'relation')
						{
								$url->addObject($node_item, 'target_');
								$url->set('action', 'relate');
								$item['url'] = $url->render('relation.php');
						}
						$out['items'][] = $item;
				}
				
				
				
				
		}
}



debug($out, 'OUT');
include('browser.template.php');

?>
