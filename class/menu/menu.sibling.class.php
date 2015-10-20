<?php

require_once 'menu.base.class.php';

class menu_sibling extends menu_base
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
				if ($siblings = $this->node->getSibling(true))
				{
						foreach ($siblings as $child)
						{
								$content = $child->getContent();
								
								if ($content->isUsedIn('navigation'))
								{
										$content->load();
										$url = new url();
										$url->set('node_id', $child->getId());
										$out .= '<a href="' . $url->render() . '">' . $content->getTitle() . '</a> <br/>';
								}
						}
						return $out;
				}
				else
				{
						return false;
				}
				
		}
		
		function getArray($get_all = false)
		{
				if ($siblings = $this->node->getSiblings(true))
				{
						foreach ($siblings as $entry)
						{
								$content = $entry->getContent();
								//if ($content->isUsedIn('navigation') || $get_all) // todo fix this get_all behavior
								if ($content->isUsedIn('navigation'))
								{
										$menuitem = new menuitem($entry);
										if ($entry->getId() == $this->node->getId())
										{
												$menuitem->is_current = true;
										}
										$menuitems[] = $menuitem;
								}
						}
						if (isset($menuitems))
						{
								return $menuitems;
						}
						else
						{
								return false;
						}
				}
				else
				{
						return false;
				}
				
		}
		
		
		
		
}

?>
