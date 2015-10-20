<?php
/*
See licence.txt
API can be found in the relation.class.php file

Input : 

* mode
 -> relate : makes a relation, and redirect to url stored in session
 -> init : store referrer url in a cookie and returns to main
 -> cancel : redirect to url storred in session and remove mode within session.

*/



/****************** deprecated ******************/


die ('deprecated');

//genral setup
include_once('common.inc.php');
//check_user
check_user();



if ($url->get('action') == 'init')
{
		if (isset($_SERVER['HTTP_REFERER']))
		{
		$session->set('action', 'relate');
		$session->set('referrer', $_SERVER['HTTP_REFERER']);
		}
		else
		{
				trigger_error('no referrer found, cannot relate currently');
		}
		
}


if ($url->get('action') == 'relate')
{
		$session->delete('action');
		echo 'I need to do a relation';
}

if ($url->get('action') == 'cancel')
{
}


$source = $url->getObject();
debug($source, 'Source');
require_once ROOT . '/class/relation.class.php';

$out['title'] = 'Relations';

$relation = new relation();
$relations = $relation->getRelations($source);

if ($relations)
{
		foreach ($relations as $relation_object )
		{
				$item['title'] = te_short(20, $relation_object->getTitle());
				$item['icon'] = $relation_object->getIcon();
				$out['relation']['data'][] = $item;
		}
}

debug($out, 'OUT');

debug($relations);


die();


?>
