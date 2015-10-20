<?php
/*
Thinkedit 2.0 by Philippe Jadin and Pierre Lecrenier
Main displays the homepage of the user

First we'll simply have boxes of items icons-like

We have groups and items in each group

Content
  Articles
	Folders
	Authors
Structure
  My web site
	My custom tree (?)
Files
  File manager
Options


Simply, we have this : 
$out['item'][]['title']
              ['icon']
							['action']
							['help']


*/



include_once('common.inc.php');

//check_user
check_user();


$config_tool = $thinkedit->newConfig();
$tables = $config_tool->getTableList();


$item['title'] = translate('structure');
$item['help'] = '';
$item['icon'] = 'ressource/image/icon/small/go-home.png';
$item['action'] = $url->render('structure.php');
$out['item'][] = $item;

$item['title'] = translate('filemanager_title');
$item['help'] = '';
$item['icon'] = 'ressource/image/icon/small/system-file-manager.png';
$item['action'] = $url->render('file_manager.php');
$out['item'][] = $item;




// generating the table list from the config array
foreach($tables as $table_id)
{
		$table = $thinkedit->newTable($table_id);
		if ($table->isUsedIn('main'))
		{
				$item['title'] = $table->getTitle();
				$item['help'] = $table->getHelp();
				$item['icon'] = $table->getIcon();
				$item['action'] = $url->linkTo($table, 'list.php');
				$out['item'][] = $item;
		}
		
}



// group / title

// foreach group , add module


// generates the breadcrumb data
$out['breadcrumb'][0]['title'] = '';
$out['breadcrumb'][0]['url'] = 'main.php';


// describes the banner :
$out['banner']['needed'] = true;
$out['banner']['title'] = translate('welcome_msg');
$out['banner']['message'] = $thinkedit->getHelp();
$out['banner']['image'] = 'ressource/image/general/icon_banner.gif';

debug($out, 'out');


// include the templates
include('header.template.php');
include('main.template.php');
include('footer.template.php');

?>

