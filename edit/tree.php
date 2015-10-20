<?php

include_once('common.inc.php');

//check_user
check_user();

if ($url->get('id'))
{
	
	$node = $thinkedit->newNode();
	$node->setId($url->get('id'));
	
	if ($node->hasChildren())
	{
		$children = $node->getChildren();
		$out = "";
		foreach ($children as $child)
		{
			$out .= '<div class="node" id="' . $child->getId() . '">' . $child->getTitle() . '</div>';
		}
		
	}
}


if (isset($out))
{
	echo $out;
}

?>
