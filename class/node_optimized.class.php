<?php
/*
Node optimized class
It could be simply doing a single query of the whole node db, store it in a var, and work from this.
Benchamrk is needed. Maybe do this for a tree smaller than x nodes
Too early optimisation is the root of all evil
THIS IS THE OPTIMIZED VERSION (see NODE.BAK.PHP for the original)


Here we reimplement only the db intensive functions using a preloaded array of all the nodes.

*/
require_once 'node.class.php';

class node_optimized extends node
{
		
		/**
		* Node object constructor.
		*
		*
		**/
		function node_optimized($table = 'node')
		{
				//parent::node();
				
				global $thinkedit;
				$this->record = $thinkedit->newRecord($table);
				$this->table = $table;
				$this->initNodeList();
				
		}
		
		function initNodeList()
		{
				/*
				How it works ?
				We build an array of all the nodes once and we use this array for all database intensive functions
				
				This array is stored in a global var, which can be shared among the nodes.
				It's simple, but it works
				
				It polutes the main namespace, but I don't care. (for now)
				*/
				global $thinkedit_cache_node_list; // uggly, but does the job
				
				if (isset($thinkedit_cache_node_list))
				{
						$this->node_list_cache = $thinkedit_cache_node_list;
				}
				else
				{
						$records = $this->record->find();
						if (is_array($records))
						{
								foreach ($records as $record)
								{
										foreach ($record->field as $field)
										{
												$node_item[$field->getId()] = $field->get();
										}
										$thinkedit_cache_node_list[$record->getId()] = $node_item;
								}
								$this->node_list_cache = $thinkedit_cache_node_list;
						}
						else
						{
								trigger_error('node_optimized::node_optimized() cannot load full array of nodes or no node found in DB'); 
						}
				}
		}
		
		
		/**
		* returns nodes
		*
		*
		**/
		
		function getParent()
		{
				$this->load();
				
				global $thinkedit;
				foreach ($this->node_list_cache as $node)
				{
						if ($node['id'] == $this->record->get('parent_id'))
						{
								$parent_node = $thinkedit->newNode($this->table, $node['id']);
								return $parent_node;
						}
						
				}
				return false;
				
		}
		
		
		
		function load($node_id = false)
		{
				
				if (!$node_id)
				{
						$node_id = $this->getId();
				}
				
				if (isset($this->is_loaded) && $this->is_loaded)
				{
						return true;
				}
				elseif (isset($this->node_list_cache[$node_id]))
				{
						foreach ($this->node_list_cache[$node_id] as $key=>$value)
						{
								$this->set($key, $value);
						}
						$this->is_loaded = true;
						return true;
						
				}
				return false;
				
		}
		
		
		/**
		* returns childrens (nodes)
		*
		*
		**/
		function getChildren()
		{
				$this->load();
				global $thinkedit;
				// build a list of childs
				foreach ($this->node_list_cache as $node)
				{
						if ($node['parent_id'] == $this->get('id'))
						{
								//echo $node['sort_order'] . '|';
								$child_list[] = $node; //todo change if the sort is the same :-/
						}
				}
				// order the childs
				if (isset($child_list) && is_array($child_list))
				{
						//ksort($child_list);
						$child_list = $this->columnSort($child_list, 'sort_order');
						
						// create node objects
						foreach ($child_list as $child)
						{
								
								$result[] = $thinkedit->newNode($this->table, $child['id']);
						}
						return $result;
				}
				else
				{
						return false;
				}
		}
		
		// from http://be.php.net/manual/en/function.usort.php#54957
		function columnSort($unsorted, $column) 
		{
				$sorted = $unsorted;
				for ($i=0; $i < sizeof($sorted)-1; $i++) {
						for ($j=0; $j<sizeof($sorted)-1-$i; $j++)
						if ($sorted[$j][$column] > $sorted[$j+1][$column]) {
								$tmp = $sorted[$j];
								$sorted[$j] = $sorted[$j+1];
								$sorted[$j+1] = $tmp;
						}
				}
				return $sorted;
		}
		
		
		
		
		/*
		Return a list of all nodes in the right order (from $this node to last leaf)
		*/
		/*
		function getAllNodes($node_id = false, $level = false, $out = false)
		{
				if (!$level)
				{
						$level = 0;
				}
				global $thinkedit;
				$node = $thinkedit->newNode();
				
				if ($node_id)
				{
						$node->load($node_id);
				}
				else
				{
						$node = $this;
						$this->node_list[] = $node;
				}
				debug($node);
				if ($node->hasChildren())
				{
						$children = $node->getChildren();
						// display each child
						foreach  ($children as $child)
						{
								$this->node_list[] = $child;
								if ($level > 20)
								{
										trigger_error('menu::displayChildren() level higher than 20, infinite loop ?');
								}
								else
								{
										$this->getAllNodes($child->getId(), $level+1, $out);
								}
						}
				}
				
				return $this->node_list;
				
		}
		*/
		
		function refreshNodeList()
		{
				
				global $thinkedit_cache_node_list; // uggly, but does the job
				
				// if the cache array is found, unset it
				if (isset($thinkedit_cache_node_list))
				{
						unset($thinkedit_cache_node_list);
				}
				// re-init node list
				$this->initNodeList();
		}
		
		
		function delete()
		{
				$results = parent::delete();
				
				global $thinkedit;
				$db = $thinkedit->getDb();
				$db->clearCache();
				$this->refreshNodeList();
				return $results;
		}
		
		/*
		function add($node)
		{
		}
		*/
}


?>