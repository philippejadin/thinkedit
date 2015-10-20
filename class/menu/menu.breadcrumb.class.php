<?php

require_once 'menu.base.class.php';

class menu_breadcrumb extends menu_base
{
		
		function menu_breadcrumb($node = false)
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
				$items = $this->getArray();
				
				foreach ($items as $item)
				{
						if ($item->isEnd())
						{
								$out .= $item->getTitle();
						}
						else
						{
								$out .= '<a href="' . $item->getUrl() . '">' . $item->getTitle() . '</a> &gt; ';
						}
						
						
				}
				return $out;
		}
		
		
		function getArray()
		{
				require_once 'menuitem.class.php';
				// add current
				$menuitem = new menuitem($this->node);
				$menuitem->is_end = true; // this is in fact the last item of this breadcrumb
				$items[] = $menuitem;
				
				// add parents
				if ($this->node->getParentUntilRoot())
				{
						foreach ($this->node->getParentUntilRoot() as $parent)
						{
								$content = $parent->getContent();
								if ($content->isUsedIn('navigation'))
								{
										$items[] = new menuitem($parent);
								}
						}
				}
				$items = array_reverse($items);
				
				return $items;
		}
}

?>
