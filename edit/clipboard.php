<?php
/*
Thinkedit clipboard

It will keep in a session the node id that is in the clipboard and will paste it when requested (add a new emplacement, or change parent)

Input :
- source_node (for cut and copy)
- target_node (for paste)
- action (cut,copy,paste)
- node_id

Output :
Simple translated messages in clear text, to be shown inside an iframe (status bar)

*/

die('deprecated');


include_once('common.inc.php');
include_once('../class/clipboard.class.php');

//check_user
check_user();

$clipboard = new clipboard();

//echo $clipboard->debug();


$session = $thinkedit->newSession();

if ($url->get('action') == 'cut')
{
		if ($url->get('source_node'))
		{
				/*
				$session->set('clipboard_source_node', $url->get('source_node'));
				$session->set('clipboard_action', 'cut');
				*/
				
				$source_node = $thinkedit->newNode();
				$source_node->setId($url->get('source_node'));
				
				if ($clipboard->cut($source_node))
				{
						$out['info'] = translate('node_cut_ok');
				}
				
				else
				{
						$out['info'] = translate('node_cut_failed');
				}
		}
		
}


if ($url->get('action') == 'copy')
{
		if ($url->get('source_node'))
		{
				/*
				$session->set('clipboard_source_node', $url->get('source_node'));
				$session->set('clipboard_action', 'cut');
				*/
				
				$source_node = $thinkedit->newNode();
				$source_node->setId($url->get('source_node'));
				
				if ($clipboard->copy($source_node))
				{
						$out['info'] = translate('node_copy_ok');
				}
				
				else
				{
						$out['info'] = translate('node_copy_failed');
				}
		}
		
}


if ($url->get('action') == 'paste' && $url->get('target_node'))
{
		$target_node = $thinkedit->newNode();
		$target_node->setId($url->get('target_node'));
		
		if ($clipboard->paste($target_node))
		{
				$out['info'] = translate('node_paste_ok');
				$url = $thinkedit->newUrl();
				$session->set('clipboard_reload', 1);
				//$out['change_url'] = $url->render();
		}
		
		else
		{
				$out['info'] = translate('node_paste_failed');
		}
		
}



$url = $thinkedit->newUrl();
if ($session->get('clipboard_reload'))
{
		$out['reload'] = true;
		$session->delete('clipboard_reload');
}

include_once('clipboard.template.php');

?>
