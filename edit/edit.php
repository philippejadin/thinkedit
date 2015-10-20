<?php
/*
See licence.txt for licence
Edit displays an edit page for the current $table, $id, $db_locale
todo : validation of the request arguments agains the config file to avoid hack

big simplification :

Input is :

- object_id
- object_class
- object_type
-> record edit mode


Input is
- node_id
- (locale)
-> node edit mode

*/

include_once('common.inc.php');


//check_user
check_user();

$url = $thinkedit->newUrl();

/*
echo '<pre>';
print_r($_REQUEST);
*/

/**************** Handle Cancel first ****************/

if ($url->get('cancel_and_return_to_structure'))
{
	$url->redirect('structure.php');
}


/**************** node or record ? ****************/
if ($url->getParam('node_id')) // node and record
{
		$out['edit_node'] = true;
		$edit_node = true;
		$node_id = $url->get('node_id');
		$node = $thinkedit->newNode();
		$node->setId($node_id);
		$node->load();
		$record = $node->getContent();
}
else // only a record
{
		$table = $url->get('type');
		$out['table'] = $table;
		
		$record = $thinkedit->newRecord($url->getParam('type'));
		$table_object = $thinkedit->newTable($url->getParam('type'));
		
		$keys = $record->getPrimaryKeys();
		
		foreach ($keys as $key)
		{
				if ($url->getParam($key))
				{
						$record->set($key, $url->getParam($key));
				}
		}
		$record->load();
}

/****************** Handle save ******************/
if ($url->get('action')=='save')
{
		debug($_REQUEST, 'Request');
		foreach ($record->field as $field)
		{
				if (isset($_POST[$field->getName()]))
				{
						$record->set($field->getName(), $_POST[$field->getName()]);
				}
		}
		
		debug($record, 'Record before saving');
		if ($record->save())
		{
				$out['info'] = translate('item_save_successfully');
				$save_and_close = true;
		}
		else
		{
				trigger_error('edit : failed saving record');
				$save_and_close = false;
		}
}



/****************** Handle Node save ******************/
if (isset($node) && $url->get('action')=='save')
{
		debug($_REQUEST, '(node) Request');
		foreach ($node->record->field as $field)
		{
				// we take only the posted form data with the node_ prefix
				if (isset($_POST['node_' . $field->getName()]))
				{
						$node->record->set($field->getName(), $_POST['node_' . $field->getName()]);
				}
		}
		
		debug($record, 'Node record before saving');
		if ($node->save())
		{
				$out['info'] = translate('item_save_successfully');
				$save_and_close = true;
		}
		else
		{
				trigger_error('edit : failed saving node record');
				$save_and_close = false;
		}
		$node->clearContentCache();
}


/******************* Handle close window ************/
if (isset($save_and_close) && $save_and_close)
{
		$url->redirect ('structure.php');
}


// generating the items list from the config array
/****************** Form items ******************/

$url->set('action', 'save');
$url->keepParam('type');
$url->keepParam('class');
$url->keepParam('mode');
$url->keep('node_id');
$out['save_url'] = $url->render();

foreach ($record->field as $field)
{
		if ($field->isUsedIn('edit'))
		{
				$out['field'][$field->getName()]['ui'] = $field->renderUi();
				if ($field->getType() <> 'id')
				{
						$out['field'][$field->getName()]['title'] = $field->getTitle();
				}
				else
				{
						$out['field'][$field->getName()]['title'] = '';
				}
				$out['field'][$field->getName()]['help'] = $field->getHelp();
		}
}



/****************** Node Form items ******************/

if (isset($node))
{
		foreach ($node->record->field as $field)
		{
				if ($field->isUsedIn('edit'))
				{
						$out['node_field'][$field->getName()]['ui'] = $field->renderUi('node_');
						
						if ($field->getType() <> 'id')
						{
								$out['node_field'][$field->getName()]['title'] = $field->getTitle();
						}
						else
						{
								$out['node_field'][$field->getName()]['title'] = '';
						}
						
						$out['node_field'][$field->getName()]['help'] = $field->getHelp();
				}
		}
}




/****************** Relations ******************/

$url = new url();
$url->addObject($record, 'source_');
$out['relation']['url'] = $url->render('relation.php');

// clean url
$url = new url();


// describes the banner :
$out['banner']['needed'] = true;
$out['banner']['title'] = $record->getTitle();
//$out['banner']['message'] = $record->getHelp();
$out['banner']['image'] = $record->getIcon();


debug($out, 'OUT');
debug($_REQUEST, 'Request');


// include the templates

include('header.template.php');
include('edit.template.php');
include('footer.template.php');

?>
