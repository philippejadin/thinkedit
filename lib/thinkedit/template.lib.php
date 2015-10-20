<?php
/* 
Thinkedit template functions
*/


/************************ Content related functions **********************/


function te_title()
{
	global $content;
	return $content->getTitle();
}

function te_get($id)
{
	global $content;
	return $content->get($id);
}



function te_translate($id)
{
	trigger_error('translation support not yet added for frontend duties');
	return $id;
}


/*************************** Paths, urls, links *******************/
/*
Returns the url of the current design (usefull for linking to css or design images)
*/
function te_design()
{
	global $thinkedit;
	$configuration = $thinkedit->newConfig();
	$design = $configuration->getDesign();
	return ROOT_URL . '/design/' . $design;
}


// returns root node
function te_root()
{
	global $thinkedit;
	if (isset($thinkedit))
	{
		$node = $thinkedit->newNode();
		if (isset($node))
		{
			if ($node->loadRootNode())
			{
				return $node;
			}
		}
	}
	return false;
}


// returns a link to the root node
function te_root_link()
{
	return te_link(te_root());
}


// returns a link to the administrative view of the current page
function te_admin_link()
{
	global $node;
	if ($node)
	{
		return ROOT_URL . '/edit/structure.php?node_id=' . $node->getId();
	}
	else
	{
		return ROOT_URL . '/edit/structure.php';
	}
}


// returns a link to the edit view of the current page
function te_edit_link()
{
	global $node;
	if ($node)
	{
		return ROOT_URL . '/edit/edit.php?node_id=' . $node->getId();
	}
	else
	{
		return ROOT_URL . '/edit/structure.php';
	}
}



/*
Returns the local path of the current design (usefull for including design specific php files)
*/
function te_design_path()
{
	global $thinkedit;
	$configuration = $thinkedit->newConfig();
	$design = $configuration->getDesign();
	return ROOT . '/design/' . $design;
}


/*
Returns a link to the passed object. If absolute is set to true, you will have an absolute link
*/
function te_link($object, $absolute = false)
{
	global $thinkedit;
	$url = $thinkedit->newUrl();
	if ($object->getType() == 'node')
	{
		$url->set('node_id', $object->getId());
		if ($absolute)
		{
			return $url->render(ROOT_URL . '/index.php');
		}
		else
		{
			return $url->render();
		}
	}
	elseif ($object->getClass() == 'filesystem')
	{
		$url->keep('node_id');
		return $url->render($object->getUrl());
	}
	else
	{
		trigger_error('te_link() : object is not a node or a filesystem, not yet supported. Most probably, you made a relation to a file or a content object instead of a node. Delete the relations you made to those. Or ask on Thinkedit mailing list');
		return false;
	}
}

/*********** Menu handling template tags ***************/
/*
This functions returns an array if a menu exists or false if no menu is found.
The array is an array of menuitems objects, providing some methods
*/

// returns an array of menu items of the main menu
function te_main_menu()
{
	require_once ROOT . '/class/menu/menu.main.class.php';
	//global $node;
	$menu = new menu_main();
	return $menu->getArray();
}

// returns a contextual menu
function te_context_menu()
{
	require_once ROOT . '/class/menu/menu.context.class.php';
	global $node;
	$menu = new menu_context($node);
	return $menu->getArray();
}


// returns a child menu
function te_child_menu()
{
	require_once ROOT . '/class/menu/menu.child.class.php';
	global $node;
	$menu = new menu_child($node);
	return $menu->getArray();
}


function te_breadcrumb_menu()
{
	require_once ROOT . '/class/menu/menu.breadcrumb.class.php';
	global $node;
	$menu = new menu_breadcrumb($node);
	return $menu->getArray();
}

/**************************** UI widgets ***********************/

/*
Will render a locale chooser link list
*/
function te_locale_chooser()
{
	global $thinkedit;
	global $content;
	$out = '';
	if ($content->isMultilingual())
	{
		$url = $thinkedit->newUrl();
		$url->keep('node_id');
		$locales = $content->getLocaleList();
		foreach ($locales as $locale)
		{
			$url->set('locale', $locale);
			$out .= '[<a href="' . $url->render() . '">' . $locale . '</a>]';
			$out .= ' ';
		}
		return $out;
	}
	else
	{
		return false;
	}
}




function te_admin_toolbox()
{
	global $te_admin_toolbox_written;
	if (!isset($te_admin_toolbox_written))
	{
		$te_admin_toolbox_written = true;
		
		global $thinkedit;
		if ($thinkedit->user->isAdmin())
		{
			$out = '';
			
			// add jquery code
			$out = te_jquery();
			
			// todo move style sheet somewhere, but this one is common to all designs, and designs author can do what they want with it
			// done, file is in /edit/toolbar.css
			$out .= '<link type="text/css" href="' . ROOT_URL . '/edit/ressource/css/toolbar.css" rel="stylesheet" media="screen"/>';
			
			$out .= '<div class="te_tools">';
			$out .= '<div class="te_toolbar">';
			
			$out .= '<div class="te_toolbar_logo">';
			$out .= '<b>ThinkEDIT</b>'; // todo add version number automagically
			$out .= '</div>';
			
			// logout
			$url = $thinkedit->newUrl();
			$out .= '<a href="' . ROOT_URL . '/edit/logout.php" class="te_toolbar_button">'. translate('logout') .'</a>';
			
			
			
			// refresh page link
			$url = new url();
			$url->keep('node_id');
			$url->set('refresh', 1);
			$out .= '<a href="' . $url->render() . '" class="te_toolbar_button">'. translate('refresh_page') .'</a>';
			
			// refresh site link
			$url = new url();
			$url->keep('node_id');
			$url->set('clear_cache', 1);
			$out .= '<a href="' . $url->render() . '" class="te_toolbar_button">'. translate('refresh_site') .'</a>';
			
			
			// edit page link
			$url = new url();
			$url->keep('node_id');
			$out .= '<a href="' . $url->render('./edit/structure.php') . '" target="_blank" class="te_toolbar_button">'. translate('edit') .'</a>';
			
			// show hide profiling
			$out .= '<a class="te_toolbar_button" onclick="$(\'.te_profiling\').toggle()">'. translate('toggle_profiling') .'</a>';
			
			// show hide errors
			if (isset($thinkedit->errors))
			{
				$out .= '<a class="te_toolbar_button te_toolbar_error_button" onclick="$(\'.te_error_log\').toggle()">'. translate('toggle_errors') .'</a>';
			}
			
			$out .= '</div>'; // end of toolbar
			
			$out .= '<div class="te_profiling te_console" style="display: none">';
			$out .= 'Total Queries : ' . $thinkedit->db->getTotalQueries();
			$out .= '<br/>';
			$out .= 'Total time : ' . $thinkedit->timer->render();
			
			
			
			global $db_debug;
			if (isset($db_debug))
			{
				if (!$thinkedit->isInProduction())
				{
					foreach ($db_debug as $sql)
					{
						$out .= "<li>{$sql}</li>";
					}
				}
				else
				{
					$out .= "<li>SQL not shown in production mode</li>";
				}
			}
			
			
			$out .= '</div>'; // end of profiling
			
			// include error log
			$out .= te_error_log();
			
			$out .= '</div>'; // end of tools
			
			return $out;
		}
		else
		{
			return false;
		}
	}
}

// will return the html needed to show errors
function te_error_log()
{
	global $thinkedit;
	// global var that is used to know if errors have been written already
	global $te_error_written;
	
	if (!isset($te_error_written))
	{
		if (isset($thinkedit->errors))
		{
			//$out = '<div class="te_error_log te_console" style="display: none">';
			// by default errors are shown
			$out = '<div class="te_error_log te_console">';
			foreach ($thinkedit->errors as $error)
			{
				$out .= '<div>';
				$out .= $error['message'];
				$out .= '<div>';
			}
			$out .= '</div>';
			$te_error_written = true;
			return $out;
		}
		else 
		{
			return false;
		}
	}
	
	
}

/**
* Will return the script tag to have jquery added to your page. Echo this in the head of your template header.
*/
function te_jquery()
{
	global $te_jquery_added;
	
	if (isset($te_jquery_added))
	{
	}
	else
	{
		return  '<script type="text/javascript" src="' . ROOT_URL . '/lib/jquery/jquery.js"></script>';
		$te_jquery_added = true;
	}
}	

/************************ Tools / helpers *********************/

/*
Returns a short version of the string passed with [...] apended to it if the stirng is longer than size
see http://be2.php.net/manual/en/function.explode.php#70574 for a better implementation (todo)

*/
function te_short($string, $size=30)
{
	if (strlen($string) > $size)
	{
		return strip_tags((substr($string, 0, $size -4) . '...'));
	}
	return strip_tags($string);
}



function te_every($size)
{
	static $i;
	$i++;
	if (($i % $size) == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
Todo

function te_get_caller_context($levels='', $die=FALSE) 
{
	$debug = debug_backtrace();
	if ($levels == '') 
	{
		$levels = count($debug);//print count($debug);die();
	}
	$caller_context = '';
	$ctr = -1;
	while ($ctr < ($levels-1)) 
	{
		$ctr++;
		$caller_context = $debug[$ctr]["file"] . '::' . $debug[$ctr]["function"] . '::' . $debug[$ctr]["line"] . '==>' . "\n" . $caller_context;
	}
	$caller_context = trim($caller_context);
	$caller_context = preg_replace("/==>$/", '', $caller_context);
	$caller_context = preg_replace("/^::::==>/", '', $caller_context);
	$caller_context = preg_replace("/\/var\/www\/[a-z]+\.ookles\.net/", '.', $caller_context);
	print "\n\n\n\n$caller_context\n\n\n\n";
	if ($die) 
	{
		die('Died in function te_get_caller_context');
	}
}
*/


?>
