<?php


require_once 'menu.base.class.php';

class menu_sitemap extends menu_base
{
		
		function displayChildren($node_id, $level = false, $out = false) 
		{
				if (!$level)
				{
						$level = 0;
				}
				global $thinkedit;
				$node = $thinkedit->newNode();
				$node->load($node_id);
				debug($node);
				if ($node->hasChildren())
				{
						$out .= '<ul>'; 
						$children = $node->getChildren(true);
						// display each child
						foreach  ($children as $child)
						{
								$content = $child->getContent(true);
								$content->load();
								// indent and display the title of this child
								//$out .= str_repeat('  ',$level) . '(' . $child->getId(). ')' . $content->getTitle() ."\n";
								
								if (isset($this->node_id) && $this->node_id == $child->getId())
								{
										$class = ' class="current"';
								}
								else
								{
										$class = '';
								}
								$out .= '<li' . $class . '>' . $content->getTitle() . '</li>';
								debug($out, 'out');
								// call this function again to display this
								// child's children
								// limit levels
								if ($level > 20)
								{
										trigger_error('menu::displayChildren() level higher than 20, infinite loop ?');
								}
								else
								{
										$out = $this->displayChildren($child->getId(), $level+1, $out);
								}
						}
						$out .= '</ul>'; 
						
					
				}
				else
				{
						
				}
				
				return $out;
		}
		
		
		function render($start = 0)
		{
				return $this->displayChildren($start);
		}
		
}

?>
