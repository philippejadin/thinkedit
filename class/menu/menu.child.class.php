<?php

require_once 'menu.base.class.php';

class menu_child extends menu_base
{
		
		function menu_child($node = false)
		{
				if ($node)
				{
						$this->node = $node;
				}
				else
				{
						global $thinkedit;
						$this->node = $thinkedit->newNode();
						$this->node->loadRootNode();
				}
		}
		
		
		function render()
		{
				$out = '';
				if ($this->node->getChildren(true))
				{
						foreach ($this->node->getChildren(true) as $child)
						{
								$content = $child->getContent();
								$content->load();
								$url = new url();
								$url->set('node_id', $child->getId());
								$out .= '<a href="' . $url->render() . '">' . $content->getTitle() . '</a> <br/>';
						}
						return $out;
				}
				else
				{
						return false;
				}
				
		}
		
		
		function getArray()
		{
				$out = '';
				if ($this->node->getChildren(true))
				{
						require_once 'menuitem.class.php';
						foreach ($this->node->getChildren(true) as $child)
						{
								$content = $child->getContent();
								if ($content->isUsedIn('navigation'))
								{
										$menuitem = new menuitem($child);
										$result[] = $menuitem;
								}
						}
						return $result;
				}
				else
				{
						return false;
				}
				
		}
		
		
}

?>
