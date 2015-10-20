<?php
/*
Node cached class

Using a simple cache to store node data

Here we reimplement only the db intensive functions using cached data

*/
require_once 'node.class.php';

class node_cached extends node
{
		
		
		function node_cached($table = 'node')
		{
				parent::node($table);
				/*
				global $thinkedit;
				$this->record = $thinkedit->newRecord($table);
				$this->table = $table;
				$this->initNodeList();
				*/
				
		}
		
		
		
		
		function loadCache()
		{
				if (isset($this->cache))
				{
						return true;
				}
				else
				{
						global $thinkedit;
						
						if ($this->cache = $thinkedit->cache->get('node_internal_cache' . $this->getId() ))
						{
								return true;
						}
						else 
						{
								$this->cache = false;
								return false;
						}
				}
		}
		
		function saveCache()
		{
				global $thinkedit;
				return $thinkedit->cache->save($this->cache, 'node_internal_cache_' . $this->getId() );
		}
		
		/*
		function getLevel()
		{
				// if we can get the level from the node 'level' row, we do it, because it's far faster
				if (!$this->record->field['level']->isEmpty())
				{
						return $this->record->field['level']->get();
				}
				
				// else we compute the level :
				$parents = $this->getParentUntilRoot(); // todo : optimize!
				if ($parents)
				{
						$level = count($parents);
				}
				else
				{
						$level = 0;
				}
				
				// and we save it in the node DB for future use
				$this->record->set('level', $level);
				$this->record->save();
				
				// finally we return it, of course
				return $level;
		}
		*/
		
		function getChildren($only_published = false)
		{
				$this->loadCache();
				if (isset($this->cache['children']))
				{
						if (is_array($this->cache['children']))
						{
								global $thinkedit;
								foreach ($this->cache['children'] as $child)
								{
										$childs[] = $thinkedit->newNode($this->table, $child);
								}
								return $childs;
						}
						else
						{
								return false;
						}
				}
				else
				{
						$children = parent::getChildren();
						if (is_array($children))
						{
								foreach ($children as $child)
								{
										$this->cache['children'][] = $child->getId();
								}
						}
						else
						{
								$this->cache['children'] = false;
						}
				$this->saveCache();
				return $children;
				}
		}
		
		
		function getLevel($only_published = false)
		{
				$this->loadCache();
				if (isset($this->cache['level']))
				{
						return $this->cache['level'];
				}
				else
				{
						$level = parent::getLevel();
						$this->cache['level'] = $level;
						$this->saveCache();
						return $level;
				}
		}
		
		
		function getTitle()
		{
				$this->loadCache();
				if (isset($this->cache['title']))
				{
						return $this->cache['title'];
				}
				else
				{
						$title = parent::getTitle();
						$this->cache['title'] = $title;
						$this->saveCache();
						return $title;
				}
		}
		
		
		function getParentUntilRoot()
		{
				$this->loadCache();
				if (isset($this->cache['parents_until_root']))
				{
						if (is_array($this->cache['parents_until_root']))
						{
								global $thinkedit;
								foreach ($this->cache['parents_until_root'] as $parent)
								{
										$parents[] = $thinkedit->newNode($this->table, $parent);
								}
								return $parents;
						}
						else
						{
								return false;
						}
				}
				else
				{
						$parents = parent::getParentUntilRoot();
						if (is_array($parents))
						{
								foreach ($parents as $parent)
								{
										$this->cache['parents_until_root'][] = $parent->getId();
								}
						}
						else
						{
								$this->cache['parents_until_root'] = false;
						}
				$this->saveCache();
				return $parents;
				}
		}
		
}
?>
