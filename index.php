<?php

/*********************** Is thinkedit installed ? ******************/
// should be : if (!$thinkedit->isInstalled())
if (!file_exists('config/db.php'))
{
		$msg = '<h1>Thinkedit is not installed. Go to the <a href="./install/">installer</a> to install it</h1>';
		//trigger_error($msg);
		echo ($msg);
		die();
}



/******************* Init *******************/
//user
//thinkedit
require_once('thinkedit.init.php');



require_once ROOT . '/class/url.class.php';


// helpers classes :
//url
$url = new url();



$cache_id = 'node_' . $url->get('node_id') . $url->get('locale');


if ($url->get('no_cache'))
{
		$cache_enabled = false;
}
else
{
		$cache_enabled = true;
}



if ($url->get('refresh'))
{
		if ($thinkedit->outputcache->get($cache_id))
		{
				$thinkedit->outputcache->remove($cache_id);
		}
}

if ($url->get('clear_cache'))
{
		$thinkedit->outputcache->clean();
}


if ($cache_enabled && $thinkedit->outputcache->start($cache_id))
{
		require_once ROOT . '/lib/thinkedit/template.lib.php';
		echo te_admin_toolbox();
		
		if (function_exists('xdebug_dump_function_profile') && !$thinkedit->isInProduction())
		{
				xdebug_dump_function_profile(4);
		}
		
		exit; 
}
else
{
		
		
		/******************* Node *******************/
		
		$node = $thinkedit->newNode();
		if ($url->get('node_id'))
		{
				$node->setId($url->get('node_id'));
				if (!$node->load())
				{
						//include(ROOT . '/design/default/header.template.php');
						include(ROOT . '/design/' . $thinkedit->configuration->getDesign() . '/404.template.php');
						//include(ROOT . '/design/default/footer.template.php');
						die();
				}
		}
		else
		{
				if ($node->loadRootNode())
				{
				}
				else
				{
						die('<h1>There is no root node in the DB, your website is empty, aborting. Go to install and create your first node!</h1>');
				}
		}
		
		/******************* Content *******************/
		
		$content = $node->getContent();
		$content->load();
		
		
		/******************* Relations *******************/
		$relation = $thinkedit->newRelation();
		
		
		/******************* Menu *******************/
		require_once ROOT . '/class/menu/menu.breadcrumb.class.php';
		$breadcrumb = new menu_breadcrumb($node);
		
		require_once ROOT . '/class/menu/menu.main.class.php';
		$main_menu = new menu_main();
		
		
		require_once ROOT . '/class/menu/menu.child.class.php';
		$child_menu = new menu_child($node);
		
		require_once ROOT . '/class/menu/menu.sitemap.class.php';
		$sitemap = new menu_sitemap($node);
		
		require_once ROOT . '/class/menu/menu.context.class.php';
		$context_menu = new menu_context($node);
		
		require_once ROOT . '/class/menu/menu.sibling.class.php';
		$sibling_menu = new menu_sibling($node);
		
		
		/******************* Template helpers (aka "tags") *******************/
		require_once ROOT . '/lib/thinkedit/template.lib.php';
		
		/******************* Choose template *******************/
		// Which design ?
		
		$design = $thinkedit->configuration->getDesign();
		
		// find the right template
		// if  a file called $content->getType .template.php exists, it is used as the template else, we use content.template.php
		
		
		
		if ($node->get('template'))
		{
				$template_file = ROOT . '/design/'. $design . '/templates/' . $node->get('template');
		}
		else
		{
				$template_file = ROOT . '/design/'. $design . '/' . $content->getType() . '.template.php';
		}
		
		if (!file_exists($template_file))
		{
				$template_file = ROOT . '/design/'. $design .'/content.template.php';
		}
		
		
		
		
		/******************* Render *******************/
		
		// include renderer
		include(ROOT . '/design/'. $design .'/render.php');
		
		/*
		// include header
		include(ROOT . '/design/'. $design .'/header.template.php');
		
		// include template
		include($template_file);
		
		// include footer
		include(ROOT . '/design/'. $design .'/footer.template.php');
    */
		
		
		if ($cache_enabled)
		{
				$thinkedit->outputcache->end();
		}
		
}

/*
echo 'Total Queries : ' . $thinkedit->db->getTotalQueries();
echo '<br/>';
echo 'Total time : ' . $thinkedit->timer->render();
*/

echo te_admin_toolbox();


if (function_exists('xdebug_dump_function_profile') && !$thinkedit->isInProduction())
{
		xdebug_dump_function_profile(4);
}


?>
