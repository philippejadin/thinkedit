<?php

/*
Clipboard
Use case:

We have a node, we put it in the clipboard : 

$clipboard->cut($node);
$clipboard->paste($other_node)


cut() puts the item reference in a session

paste() will add() the items cutted, and will change parents 


*/


class clipboard
{
	
	
	function clipboard()
	{
		global $thinkedit;
		$this->session = $thinkedit->newSession();
	}
	
	
	/**
	* Adds the item to the clipboard, and marks it for "move" (deletion) if (and only if) it is pasted somewhere
	*/
	function cut($source)
	{
		// first remove items to copy
		$this->session->delete('clipboard_copy');	
		
		//$this->clear(); // todo, this should be not needed
		
		if ($source->getUid())
		{
			$clipboard_cut = $this->session->get('clipboard_cut');
			$clipboard_cut[] = $source->getUid();
			$this->session->set('clipboard_cut', $clipboard_cut);
			return true;
		}
		else
		{
			trigger_error('clipboard::cut() : $source has no getUid() method');
			return false;
		}
		
	}
	
	/**
	* Adds an item to the clipboard. It won't be moved (nor deleted), but copied, when pasted
	*/
	function copy($source)
	{
		// first remove items to cut
		$this->session->delete('clipboard_cut');
		
		//$this->clear(); // todo, this should be not needed
		
		
		if ($source->getUid())
		{
			$clipboard_copy = $this->session->get('clipboard_copy');
			$clipboard_copy[] = $source->getUid();
			$this->session->set('clipboard_copy', $clipboard_copy);
			return true;
		}
		else
		{
			trigger_error('clipboard::copy() : $source has no getUid() method');
			return false;
		}
	}
	
	/**
	* Move cuted items
	* Add a new parent to copied items
	*/
	function paste($target)
	{
		global $thinkedit;
		
		
		if (!$this->getContent())
		{
			trigger_error('clipboard::paste() clipboard empty');
			return false;
		}
		
		// check if $target is a node
		if (!$target->getType() == 'node')
		{
			trigger_error('clipboard::paste() $target must be a node');
			return false;
		}
		
		$clipboard_cut = $this->session->get('clipboard_cut');
		$clipboard_copy = $this->session->get('clipboard_copy');
		
		// first handle items that must be cut and pasted, simply change their parent
		if (is_array($clipboard_cut))
		{
			foreach ($clipboard_cut as $clipboard_cut_item)
			{
				$content = $thinkedit->newObject($clipboard_cut_item);
				if ($content->getType() == 'node')
				{
					$content->changeParent($target->getId());
				}
				else
				{
					trigger_error('clipboard::paste() $source in clipboard must be a node, node found is titled ' . $content->getTitle());
				}
			}
			$this->clear();
			return true;
		}
		
		if (is_array($clipboard_copy))
		{
			foreach ($clipboard_copy as $clipboard_copy_item)
			{
				$node = $thinkedit->newObject($clipboard_copy_item);
				if ($node->getType() == 'node')
				{
					$content = $node->getContent();
					$target->add($content);
				}
				else
				{
					trigger_error('clipboard::paste() $source in clipboard must be a node, node found is titled ' . $node->getTitle());
				}
			}
			$this->clear();
			return true;
		}
		
		
	}
	
	
	
	
	
	
	
	/**
	* Clear the clipboard
	*/
	function clear()
	{
		$this->session->delete('clipboard_cut');
		$this->session->delete('clipboard_copy');
		return true;
	}
	
	
	/**
	* Returns an array of clipboard content items
	* or false if empty
	*/
	function getContent()
	{
		global $thinkedit;
		
		$clipboard_cut = $this->session->get('clipboard_cut');
		$clipboard_copy = $this->session->get('clipboard_copy');
		
		if (is_array($clipboard_cut))
		{
			foreach ($clipboard_cut as $item)
			{
				$clipboard_content[] = $item;
			}
		}
		
		if (is_array($clipboard_copy))
		{
			foreach ($clipboard_copy as $item)
			{
				$clipboard_content[] = $item;
			}
		}
		
		/*
		// Sounds ugly :
		if (is_array($clipboard_cut) && is_array($clipboard_copy))
		{
			$clipboard_content array_merge ($clipboard_cut, $clipboard_copy);
		}
		elseif (is_array($clipboard_cut))
		{
			$clipboard_content = $clipboard_cut;
		}
		elseif (is_array($clipboard_copy))
		{
			$clipboard_content = $clipboard_copy;
		}
		// ... definitely ;-)
		//
		// but I don't know how to mergo two arrays if you are not sure that one of them is not an array
		// array_merge doesn't allow that in php5 ...
		*/
		
		/*
		if is_array
		$clipboard_content = array_merge((array)$clipboard_cut, (array)$clipboard_copy);
		*/
		
		//print_r ($clipboard_content);
		
		if (isset($clipboard_content) && is_array($clipboard_content))
		{
			foreach ($clipboard_content as $clipboard_item)
			{
				$content[] = $thinkedit->newObject($clipboard_item);
			}
			return $content;
		}
		else
		{
			return false;
		}
	}
	
	
	function debug()
	{
		$out = '<h1>clipboard content</h1>';
		//$out.= '<br/>';
		
		$content = $this->getContent();
		if (is_array($content))
		{
			foreach ($content as $item)
			{
				$out.= '<li>';
				$out.= $item->getTitle();;
			}
		}
		else
		{
			$out.= 'clipboard is empty';
			
		}
		return $out;
	}
}

?>
