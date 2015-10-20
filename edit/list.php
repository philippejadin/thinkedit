<?php
/*
See licence.txt for licence info
List displays a list page for the current $table
todo : validation of the request arguments against the config file to avoid hack
*/

include_once('common.inc.php');

//check_user
check_user();


// -----------------------------
// we need a table if we want to work on it
// -----------------------------
if (!$url->get('type'))
{
		error("Please select a type from the main menu");
}
else
{
		$table = $url->get('type');
		$table_object = $thinkedit->newTable($table);
		$record = $thinkedit->newRecord($table);
}


$out['table'] = $table;

// -----------------------------
// Handle icons
// -----------------------------
$out['enable_thumbnails'] = true;



/********** handle relation mode *********/
if ($session->get('action') == 'relate')
{
		$out['mode'] = 'relation';
}


// -----------------------------
// sorting
// -----------------------------

if ($url->get('sort'))
{
		$sort_field = $url->get('sort');
		$out['sort_field'] = $sort_field;
}

/*
if ($config['config']['table'][$table]['sorting']['enable']=='true')
{
		$sort_field = $config['config']['table'][$table]['sorting']['field'];
		$out['enable_sort'] = true;
}
*/




// -----------------------------
// Filters
// -----------------------------


if ($url->get('action') =='add_filter')
{
		$_SESSION['filters'][$table][$filter]['value']=$filter_value;
}


if ($url->get('action')=='remove_filter')
{
		unset ($_SESSION['filters'][$table][$filter]);
}






// -----------------------------
// generating the items list from the config array
// -----------------------------
foreach($record->field as $field)
{
		
		if ($field->isUsedIn('list'))
		{
				$out['field'][$field->getName()]['title'] = $field->getTitle();;
				$out['field'][$field->getName()]['help'] = $field->getHelp();
				$out['field'][$field->getName()]['type'] = $field->getType();
				
				if ($field->isSortable())
				{
				$out['field'][$field->getName()]['sortable'] = true;
				$url = new url();
				$url->keep('type');
				$url->keep('class');
				$url->set('sort', $field->getName());
				$out['field'][$field->getName()]['sort_url'] = $url->render();
				}
		}
		
		
}

if (empty($out['field']))
{
		trigger_error('list : no title fields for this table');
}



//print_a($out['batch']);


// -----------------------------
// query items to show content on the table page
// -----------------------------


// sorting

if (isset($sort_field))
{
$sort_query = array($sort_field => 'asc');

}
else
{
		$sort_query = false;
}

// limit
$record_count = $record->count();
if ($record_count > 25)
{
		if ($url->get('page'))
		{
				$limit['start'] = $url->get('page') * 25;
				$limit['stop'] = ($url->get('page') * 25) + 25;
		}
		else
		{
				$limit['start'] = 0;
				$limit['stop'] = 25;
		}
}
else
{
		$limit = false;
}

$records = $record->find(false, $sort_query, $limit); 



$i=0;

if ($records)
{
		foreach ($records as $item)
		{
				$out['data'][$item->getId()]['icon'] = $item->getIcon();
				$out['data'][$item->getId()]['uid'] = $item->getUid();
				
				$url = new url();
				$out['data'][$item->getId()]['edit_url'] = $url->linkTo($item, 'edit.php');
				$out['data'][$item->getId()]['delete_url'] = $url->linkTo($item, 'delete.php');
				
				if ($session->get('action') == 'relate')
				{
						$url->set('action', 'relate');
						$out['data'][$item->getId()]['relate_url'] = $url->linkTo($item, 'relate.php');
				}
				//$out['data'][$item->getId()]['plugin_url'] = $url->linkTo($record, '');
				// todo plugin urls
				
				foreach ($item->field as $field )
				{
						$out['data'][$item->getId()]['field'][$field->getName()] = substr($field->get(), 0, 20);
						// $out['data'][$item['id']][$item['locale']][$key] = substr($val, 0, 15);
        }
		}
		$i++;
}



// -----------------------------
//handle pagination
// -----------------------------

if ($record_count > 25)
{
		// find number of pages
		$number_of_pages = intval($record_count / 25) + 1;
		
		for ($i=0; $i<$number_of_pages; $i++)
		{
				$out['pagination'][$i]['title'] = $i + 1;
				$url = new url();
				$url->keep('class');
				$url->keep('type');
				$url->keep('sort');
				$url->set('page', $i);
				$out['pagination'][$i]['url'] = $url->render();
				
				$url = new url();
				if ($url->get('page') == $i)
				{
						$out['pagination'][$i]['current'] = true;
				}
		}
		//echo $number_of_pages;
		// create pages array
}

/*
echo '<pre>';
print_r($out['pagination']);
*/



// -----------------------------
//handle global actions
// -----------------------------

// add
$url = new url();
$add_action['title'] = translate('add');
$add_action['url'] = $url->linkTo($table_object, 'edit.php');
$out['global_action'][] = $add_action;



// -----------------------------
// generates the breadcrumb data
// -----------------------------
$out['breadcrumb'][1]['title'] = $table_object->getTitle();
$out['breadcrumb'][1]['url'] = $url->linkTo($table_object, 'list.php');





// -----------------------------
// describes the banner :
// -----------------------------
$out['banner']['needed'] = true;
$out['banner']['title'] = $table_object->getTitle();
$out['banner']['message'] = $table_object->getHelp();
$out['banner']['image'] = $table_object->getIcon();




// -----------------------------
// handle plugins :
// -----------------------------
if (isset($config['config']['table'][$table]['plugin']))
{
		foreach($config['config']['table'][$table]['plugin'] as $key=>$plugin)
		{
				$out['plugins'][$key] = $plugin;
		}
		//print_a ($config['config']['table'][$table]['plugin']);
		//print_a ($out);
}


debug($out, 'OUT');


if ($url->get('info'))
{
		$out['info'] = translate($url->get('info')); // todo security check in translate and in record
}


// -----------------------------
// include the templates
// -----------------------------
include('header.template.php');

if (isset($error)) // deprecated; must check if still used
{
		include('error.template.php');
}
else
{
		include('list.template.php');
}



include('footer.template.php');



?>

