<?php

require_once 'menu.base.class.php';

class menu_context extends menu_base
{
		
		function menu_context($node = false)
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
				
				global $thinkedit;
				$this->root = $thinkedit->newNode();
				$this->root->loadRootNode();
		}
		
		
		
		
		
		function render()
		{
				
			$menuitems = $this->getArray();
			$out = '';
			
			if ($menuitems)
			{
					$prev_level = 2; // todo : configure this class for different uses cases. with this, it works with a main menu + this one
					$out .= '<ul>';
					foreach ($menuitems as $menuitem)
					{
							$cur_level = $menuitem->node->getLevel();
							
							if ($prev_level > $cur_level)
							{
									$out .= '</ul>';
							}
							
							if ($prev_level < $cur_level)
							{
									$out .= '<ul>';
							}
							
							
							$out .= '<li>';
							$out .= '<a href="'. $menuitem->getUrl() .'">'. $menuitem->getTitle() .'</a>';
							$out .= '</li>';
							
							$prev_level = $cur_level;
					}
					$out .= '</ul>';
			}
			return $out;
				
		}
		
		
		function getArray()
		{
				// get level of current node
				$level = $this->node->getLevel();
				
				// if level = 0, do nothing
				if ($level == 0)
				{
						return false;
				}
				
				// if level = 1, returns childs
				if ($level == 1)
				{
						// $node_list = $this->node->getChildren(array('class'=>'record', 'type' => 'page'));
						$node_list = $this->node->getChildren();
				}
				
				
				// if level = 2, returns siblings
				if ($level == 2)
				{
						$siblings = $this->node->getSiblings(true);
						foreach ($siblings as $sibling)
						{
								// if current, we append the childrens as well
								if ($sibling->getId() == $this->node->getId())
								{
										$node_list[] = $sibling;
										if ($sibling->hasChildren())
										{
												//$children = $sibling->getChildren(array('class'=>'record', 'type' => 'page'));
												$children = $sibling->getChildren();
												foreach ($children as $child)
												{
													
																$node_list[] = $child;
													
												}
										}
										
								}
								else
								{
										$node_list[] = $sibling;
								}
						}
				}
				
				// if level = 3, (?)
				if ($level == 3)
				{
						$parent = $this->node->getParent();
						$siblings = $parent->getSiblings(true);
						
						foreach ($siblings as $sibling)
						{
								// if current, we append the childrens as well
								if ($sibling->getId() == $parent->getId())
								{
										$node_list[] = $sibling;
										if ($sibling->hasChildren(true))
										{
												$children = $sibling->getChildren(true);
												foreach ($children as $child)
												{
														$node_list[] = $child;
												}
										}
										
								}
								else
								{
										$node_list[] = $sibling;
								}
						}
				}
				
				
				if ($level == 4)
				{
						$parent1 = $this->node->getParent();
						$parent = $parent1->getParent();
						$siblings = $parent->getSiblings(true);
						
						foreach ($siblings as $sibling)
						{
								// if current, we append the childrens as well
								if ($sibling->getId() == $parent->getId())
								{
										$node_list[] = $sibling;
										if ($sibling->hasChildren(true))
										{
												$children = $sibling->getChildren(true);
												foreach ($children as $child)
												{
														$node_list[] = $child;
												}
										}
										
								}
								else
								{
										$node_list[] = $sibling;
								}
						}
				}
				
				/*
				echo '<pre>';
				print_r($node_list);
				*/
				
				// return menuitems
				if (isset($node_list) && is_array($node_list))
				{
						foreach ($node_list as $node)
						{
								$content = $node->getContent();
								if ($content->isUsedIn('navigation'))
								{
										$menuitem = new menuitem($node);
										if ($node->getId() == $this->node->getId())
										{
												//$out .=  $content->getTitle();
												$menuitem->is_current = true;
										}
										$menuitems[] = $menuitem;
								}
						}
						if (isset($menu_items))
						{
						return $menuitems;
						}
						
				}
				else
				{
						return false;
				}
				
				return false;
				
		}
		
		
		
		function getArray_old()
		{
				return false;
				
				// handle special case : if the current node is the "root" of the current section, 
				// display siblings
				
				if ($this->node->getLevel() == 1)
				{
						$nodes = $this->node->getChildren();
				}
				else
				{
						// get all parents, including current node
						$this->parents[] = $this->node->getId();			
						if ($this->node->getLevel() == 2)
						{
								$level_node = $this->node;
						}
						
						$parents = $this->node->getParentUntilRoot();
						if (is_array($parents))
						{
								foreach ($parents as $parent)
								{
										$this->parents[] = $parent->getId();
										if ($parent->getLevel() == 2)
										{
												$level_node = $parent;
										}
								}
						}
						$nodes = $this->root->getAllNodes();
				}
				
				
				
				if (is_array($nodes))
				{
						/*
						echo '<pre>';
						print_r($nodes);
						*/
						
						foreach ($nodes as $entry)
						{
								// two things to check :
								// 1. if the node is a parent of the current node
								// or
								// 2. if the parent of the node is the same as the $level_node
								
								$show = false;
								if (isset($this->parents) && in_array($entry->getId(), $this->parents))
								{
										$show = true;
								}
								
								if (isset($level_node) && $entry->isSiblingOf($level_node))
								{
										$show = true;
								}
								
								
								
								// also include childs of this node
								if ($entry->isChildOf($this->node))
								{
										$show = true;
								}
								
								if ($entry->isSiblingOf($this->node))
								{
										$show = true;
								}
								
								if ($entry->getLevel() < 2)
								{
										$show = false;
								}
								
								if ($show)
								{
										$nodes_list[] = $entry;
								}
						}
				}
				
				// now render this stuff
				if (isset($nodes_list) && is_array($nodes_list))
				{
						foreach ($nodes_list as $entry)
						{
								$menuitem = new menuitem($entry);
								if ($entry->getId() == $this->node->getId())
								{
										//$out .=  $content->getTitle();
										$menuitem->is_current = true;
								}
								$menuitems[] = $menuitem;
						}
						
						return $menuitems;
				}
				else
				{
						return false;
				}
				
		}
		
}

?>
