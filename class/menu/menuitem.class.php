<?php

/**
* A menu item is exactly this : a menu item ;-)
* Each time you use some menu template tag in thinkedit, it will return an array of menu items
* 
* 
*/
class menuitem
{
		function menuitem($node)
		{
				$this->node = $node;
				$this->content = $node->getContent();
		}
		
		/**
		* Will return the url where this menu item points 
		* This can be directly echoed in your template
		* 
		* 
		*/
		function getUrl()
		{
				global $thinkedit;
				$url = $thinkedit->newUrl();
				$url->set('node_id', $this->node->getId());
				return $url->render();
		}
		
		/**
		* Will return the title of this menu item
		* It can be echoed directly in your template in order to display navigation
		* 
		* 
		*/
		function getTitle()
		{
				return $this->node->getTitle(); // . ' (' . $this->node->getLevel() . ')';
				/*
				$this->content->load();
				return $this->content->getTitle(); // . ' (' . $this->node->getLevel() . ')';
				*/
		}
		
		/**
		* Returns true if this menu item is the last one
		* 
		* 
		* 
		*/
		function isEnd()
		{
				if (isset($this->is_end))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Return true if this menu item is the first one
		* 
		* 
		* 
		*/
		function isStart()
		{
				if (isset($this->is_start))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Return true if this menu item is the same as the current page
		* You can use this to make the current menu item hilighted for instance
		* 
		* 
		*/
		function isCurrent()
		{
				if (isset($this->is_current))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Returns the level in the tree of this menu item
		* Usefull for indentation
		* 
		* 
		*/
		function getLevel()
		{
				return $this->node->getLevel();
		}
		
}

?>
