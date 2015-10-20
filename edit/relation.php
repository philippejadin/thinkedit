<?php
/*
See licence.txt
API can be found in the relation.class.php file
*/

//genral setup
include_once('common.inc.php');
require_once ROOT . '/class/relation.class.php';

//check_user
check_user();

if ($url->get('source_class') && $url->get('source_type') && $url->get('source_id')) 
{
		$source = $url->getObject('source_');
		debug($source, 'Source');
		$session->set('relation_source', $source->getUid());
}
elseif ($url->get('action'))
{
		$source = $thinkedit->newObject($session->get('relation_source'));
}
else
{
		echo translate('save_before_relating');
		die();
		// todo more gracefull : do not display relation iframe iframe in edit when editing new record 
}


$relation = new relation();

/*********** handle actions **********/
if ($url->get('action') == 'relate')
{
		//echo 'I must relate';
		$target = $url->getObject('target_');
		$relation->relate($source, $target);
}

if ($url->get('action') == 'unrelate')
{
		//echo 'I must relate';
		$target = $url->getObject('target_');
		$relation->unrelate($source, $target);
}



$relations = $relation->getRelations($source);

if ($relations)
{
		foreach ($relations as $relation_object )
		{
				$relation_object->load();
				$item['title'] = te_short($relation_object->getTitle(), 50);
				$item['icon'] = $relation_object->getIcon();
				$url->set('action', 'unrelate');
				$url->addObject($relation_object, 'target_');
				$item['remove_url'] = $url->render();
				$out['relation']['data'][] = $item;
		}
}


$url = new url();
$url->set('mode', 'relation');
$out['browse_url'] = $url->render('browser.php');


debug($out, 'OUT');

debug($relations);


$out['title'] = 'Relations';

// include template file
include('relation.template.php');

die();

//$debug=true;

// check/validate if we have enough input
// check module name
// todo : need validation (from config file)!

if (!$_REQUEST['module'])
{
		error(translate('please_choose_a_module'));
}

$module = $_REQUEST['module'];
$out['module'] = $module;


if (!$_REQUEST['element'])
{
		error(translate('please_choose_an_element'));
}

$element = $_REQUEST['element'];
$out['element'] = $element;


if (!$_REQUEST['id'])
{
		error(translate('please_choose_an_id'));
}

$id = $_REQUEST['id'];
$out['id'] = $id;



// action is not mandatory
$action = $_REQUEST['action'];

// need validation
$relate_to = $_REQUEST['relate_to'];

// need validation
$unrelate_to = $_REQUEST['unrelate_to'];


// id of the item to move
$item_to_move = $_REQUEST['item_to_move'];


// create the list of target elements
// see config file as well

/*
store_XX : the table storing the relation

current_XX : the current table on which we are working

source_XX : the foreign table

*/


/********* Generate all variables **********/
// source is the list of items we can link to

// $source_module_id is the module id of the source module
$source_module_id = $config['config']['module'][$module]['element'][$element]['source']['name'];

if ($config['config']['module'][$source_module_id]['type'] == 'filemanager')
{
		$use_filemanager = true;
}

$source_module = $config['config']['module'][$module]['element'][$element]['source']['name'];
$source_table = $config['config']['module'][$source_module]['table'];
$source_title_row = $config['config']['module'][$source_module]['title_row'];




$store_table = $config['config']['module'][$module]['element'][$element]['store']['name'];
$store_current_field = $config['config']['module'][$module]['element'][$element]['store']['field']['current'];
$store_source_field = $config['config']['module'][$module]['element'][$element]['store']['field']['source'];

$current_title_row = $config['config']['module'][$module]['title_row'];
$current_table = $config['config']['module'][$module]['table'];

if ($config['config']['module'][$module]['element'][$element]['sorting']['enable'] == 'true')
{
		$enable_sort = true;
		$out['enable_sort'] = true;
		$sort_field = $config['config']['module'][$module]['element'][$element]['sorting']['field'];
}




// Handle order

if ($action=='move_up' or $action=='move_down' or $action=='move_bottom' or $action=='move_top')
{
		// get the order of the current element
		$current_order = $db->get_var("select $sort_field from $store_table where $store_current_field='$id' and $store_source_field = '$item_to_move'");
		if ($debug) $db->debug();
}

if ($action=='move_up') //if we move up, we need to decrease the order field between previous and current
{
		// we need to determine the order of previous element of the current one
		$previous_order = $db->get_var("select $sort_field from $store_table where $sort_field < '$current_order' and $store_current_field = '$id' order by '$sort_field' desc limit 0,1");
		if ($debug) $db->debug();
		$pre_previous_order = $db->get_var("select $sort_field from $store_table where $sort_field < '$current_order' and $store_current_field = '$id' order by '$sort_field' desc limit 1,1");
		if ($debug) $db->debug();
		
		if ($previous_order)
		{
				if (!$pre_previous_order) $pre_previous_order = $previous_order - 1;
				$new_order = $pre_previous_order + (($previous_order - $pre_previous_order) / 2);
				
				if ($debug) echo 'new order : ' . $new_order . '<p>';
				
				$order_changed = true;
		}
}

if ($action=='move_down')
{
		// we need to determine the order of the next element of the current one
		$next_order = $db->get_var("select $sort_field from $store_table where $sort_field > '$current_order' and $store_current_field = '$id' order by '$sort_field' asc limit 0,1");
		if ($debug) $db->debug();
		$next_next_order = $db->get_var("select $sort_field from $store_table where $sort_field > '$current_order' and $store_current_field = '$id' order by '$sort_field' asc limit 1,1");
		if ($debug) $db->debug();
		
		if ($next_order) // only if we have something lower than the current position we need to move
		{
				if (!$next_next_order) $next_next_order = $next_order + 1;
				$new_order = $next_order + (($next_next_order - $next_order) / 2);
				if ($debug) echo 'new order : ' . $new_order . '<p>';
				$order_changed = true;
		}
		
}



if ($action=='move_bottom')
{
		// we need to determine the order of the next element of the current one
		$new_order = $db->get_var("select max($sort_field) from $store_table where $store_current_field = '$id'") + 1;
		// $new_order = $current_order + (($next_order - $current_order) / 2);
		// echo ($new_order);
		// $db->debug();
		
		$order_changed = true;
}


if ($action=='move_top')
{
		// we need to determine the order of the next element of the current one
		$new_order = $db->get_var("select min($sort_field) from $store_table where $store_current_field = '$id'") - 1;
		
		// $new_order = $current_order + (($next_order - $current_order) / 2);
		// echo ($new_order);
		// $db->debug();
		
		$order_changed = true;
}


if ($order_changed)
{
		$set_order_query = "update $store_table set $sort_field = '$new_order' where $store_current_field = '$id' and $store_source_field = '$item_to_move'";
		// echo $set_order_query;
		
		$db->query($set_order_query);
		if ($debug) $db->debug();
}


/********* handle save before generating list **********/
// if action = relate, relate to the new element

if ($action=='relate')
{
		
		// todo : need to check if the values are already in the db
		
		if ($enable_sort)
		{
				// get bigest order
				$latest_order = $db->get_var("SELECT max($sort_field) FROM $store_table where $store_current_field=$id") + 1;
				//SELECT max(order_by) FROM news_authors where news_id=33;
				// insert
				$insert_query = ("insert into $store_table ($store_current_field, $store_source_field, $sort_field) values ($id, $relate_to, $latest_order)");
		}
		else
		{
				$insert_query = ("insert into $store_table ($store_current_field, $store_source_field) values ($id, $relate_to)");
		}
		
		debug($insert_query);
		
		$db->query($insert_query);
		if ($debug) $db->debug();
}





/********* handle remove before generating list **********/
// if action = relate, relate to the new element

if ($action=='unrelate')
{
		
		// todo : need to check if the values are already in the db
		
		
		$delete_query = ("delete from $store_table where $store_current_field = $id and $store_source_field = $unrelate_to");
		
		debug($delete_query);
		
		$db->query($delete_query);
		if ($debug) $db->debug();
}



// handle special case : if we are working with a file manager :


if ($use_filemanager)
{
		// path is the current folder we are
		
		if ($_REQUEST['path'])
		{
				$path = $_REQUEST['path'];
				$_SESSION[$source_module][$element]['path'] = $path;
		}
		elseif ($_SESSION[$source_module][$element]['path'])
		{
				$path = $_SESSION[$source_module][$element]['path'];
		}
		else
		{
				$path = '/';
		}
		
		
		
		// build a list of folders (excluding cache and thumbnails folders / files)
		
		$folders = $db->get_results("select distinct path from $source_table order by path");
		if ($debug) $db->debug();
		
		if ($debug) print_a ($folders);
		
		if ($folders)
		{
				
				$out['folders'] = $folders;
				
		}
		
}


/********* generate list 1 **********/

// $source_query = "select * from $source_table, $store_table where  not ($store_table.$store_source_field=$source_table.id) order by $source_title_row";
//, $store_table where  not ($store_source_field = $source_table.id)
// $source_query = "select * from $source_table order by $source_title_row";
// mysql doesn't support sub selects, found an answer to this solution at : http://forums.devshed.com/archive/4/2003/7/1/67621

if ($use_filemanager)
{
		$where_clause = " and path='$path' ";
}

if ($config['config']['module'][$source_module]['locale']['type'] == 'multilingual')
{
		$where_clause .= " and locale='$preferred_locale' ";
}

$source_query = "SELECT $source_table.* FROM $source_table LEFT JOIN $store_table ON $source_table.id=$store_table.$store_source_field and $store_table.$store_current_field = $id WHERE $store_table.$store_current_field IS NULL $where_clause order by $source_title_row";

$source_results = $db->get_results($source_query);


if ($debug) $db->debug();

if ($db->num_rows > 0)
{
		$i = 0;
		foreach ($source_results as $source_result)
		{
				
				$out['source'][$i]['title'] =   substr($source_result->$source_title_row, 0, 30);
				$out['source'][$i]['id'] =  $source_result->id;
				$i++;
		}
}



/*********  generate list 2 **********/

// create the list of existing elements
// select * from news_authors, authors where news_authors.news_id=2 and news_authors.author_id=authors.id ;


if ($config['config']['module'][$source_module]['locale']['type'] == 'multilingual')
{
		$where_clause = " and locale='$preferred_locale' ";
}

if ($enable_sort)
{
		$existing_query = "select * from $store_table, $source_table where $store_table.$store_current_field=$id and $store_table.$store_source_field=$source_table.id " . $where_clause . " order by " . $store_table . "." . $sort_field;
}
else
{
		$existing_query = "select * from $store_table, $source_table where $store_table.$store_current_field=$id and $store_table.$store_source_field=$source_table.id " . $where_clause . " order by $source_title_row";
}

$existing_results = $db->get_results($existing_query);


if ($debug) $db->debug();

if ($db->num_rows > 0)
{
		$i = 0;
		foreach ($existing_results as $existing_result)
		{
				$out['existing'][$i]['title'] =  substr($existing_result->$source_title_row, 0, 16) . ' ...';
				$out['existing'][$i]['id'] =  $existing_result->id;
				$out['existing'][$i]['order'] =  $existing_result->$sort_field;
				$i++;
		}
}


// if true we use thumbnails (img) in template
$out['enable_thumbnails'] = $use_filemanager;

$out['url'] = $_SERVER['PHP_SELF'] . "?module=$module&element=$element&id=$id";


// if action = create, simple create in the target table, and warn user that he may need to edit in a more detailed manner




// for debug purposes
if ($debug) print_a($out);


// include template file
include('relation.template.php');

?>
