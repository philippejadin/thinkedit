<?php

class menu_base
{
		
		function menu_base($node = false)
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
		
		function setCurrentNode($node_id)
		{
				$this->node_id = $node_id;
		}
		
		function render()
		{
				$out = '';
				if ($this->getArray())
				{
						foreach ($this->getArray() as $item)
						{
								$out .= '<a href="' . $item->getUrl() . '">' . $item->getTitle() . '</a> ';
						}
						return $out;
				}
				else
				{
						return false;
				}
				
		}
		
}

?>
