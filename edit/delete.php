<?php
/*
See licence.txt
*/

include_once('common.inc.php');

//check_user
check_user();

if (!$url->get('type'))
{
		error("Please choose a table");
}

$table = $url->get('type');
$out['table'] = $table;

$table_object = $thinkedit->newTable($table);
$record = $url->getObject();

if ($record->load())
{
		if ($record->delete())
		{
		debug($record, 'Record after loading');
		$url->keepParam('class');
		$url->keepParam('type');
		$url->redirect('list.php');
		die();
		}
		else
		{
				error(translate('cannot_delete_record'));
		}
}
else
{
		error(translate('cannot_load_record'));
}

?>
