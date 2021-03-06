<?php

/**
* Node base class
*
* I was thinking about extending the record object. Finally, I will use a proxy object (is it the right name for this)
* $this->record contains the reocrd object used by this node.
*
* I feel safer this way
*
*
* TODO : optimize number of sql queries needed.
*
* Using the adjacency list model that evryone uses
* More info at http://www.sitepoint.com/article/hierarchical-data-database/1
* and at http://dev.mysql.com/tech-resources/articles/hierarchical-data.html
* 
* It could be simply doing a single query of the whole node db, store it in a var, and work from this.
* 
* Benchamrk is needed. Maybe do this for a tree smaller than x nodes
* 
* Too early optimisation is the root of all evil
* 
* 
* 
* GENERAL TODO : OPTIMIZE THIS
* 
* @author Philippe Jadin
*/
class node
{
		
		/**
		* Node object constructor.
		*
		*
		**/
		function node($table = 'node')
		{
				// init a record with a tablename = 'node'
				global $thinkedit;
				$this->record = $thinkedit->newRecord($table);
				$this->table = $table;
				
		}
		
		
		/**
		* Sets the id of this node, before load, or whenever you want.
		*
		*
		**/
		function setId($node_id)
		{
				return $this->record->set('id', $node_id);
		}
		
		/**
		* Returns the id of this node
		* 
		* 
		**/
		function getId()
		{
				return $this->record->get('id');
		}
		
		/**
		* Set some $field to $value
		* You must save the node if you update it this way.
		* 
		**/
		function set($field, $value)
		{
				return $this->record->set($field, $value);
		}
		
		/**
		* Returns the value of $field
		* 
		* 
		**/
		function get($field)
		{
				return $this->record->get($field);
		}
		
		
		
		/**
		* Returns true if this node has a parent ( a root node has no parents for instance)
		* 
		* 
		**/
		function hasParent()
		{
				if ($this->getParent())
				{
						return true;
				}
				else
				{
						return false;
				}
				
		}
		
		
		
		/**
		* Returns the parent of this node
		*
		*
		**/
		function getParent()
		{
				if ($this->getId() == 1)
				{
						return false;
				}
				
				global $thinkedit;
				$this->load();
				// todo : returns a node and not a record
				$parent = $this->record->find(array('id'=>$this->record->get('parent_id')) );
				if ($parent)
				{
						$parent_node = $thinkedit->newNode($this->table, $parent[0]->get('id'), $parent[0]->getArray());
						return $parent_node;
				}
				else
				{
						return false;
				}
				
				
		}
		
		/**
		* Returns the id of the parent of this node
		* 
		* 
		**/
		function getParentId()
		{
				trigger_error('getParentId() is it a usefull function ?');
				$parent = $this->record->get('parent_id');
				if (isset($parent))
				{
						return $parent;
				}
				else
				{
						trigger_error('node::getParentId() no parent id found');
						return false;
				}
				
		}
		
		/**
		* Deletes this node. A node can be deleted only if it doesn't have children
		* 
		* 
		**/
		function delete($keep_content = true)
		{
				if ($this->hasChildren())
				{
						trigger_error('node::delete() cannot delete non empty nodes, please delete childs of this node first');
				}
				else
				{
						// delete attached content record
						if (!$keep_content)
						{
								$content = $this->getContent();
								$content->delete();
						}
						$result = $this->record->delete();
						$this->rebuild();
						return $result;
				}
		}
		
		/**
		* Loads a node form the db. If $node_id is defined then the node with this ID is loaded
		* Returns false on failure, true on success
		* 
		**/
		function load($node_id = false)
		{
				if ($node_id)
				{
						$this->setId($node_id);
				}
				
				if (isset($this->is_loaded) && $this->is_loaded)
				{
						return true;
				}
				
				if ($this->record->load())
				{
						//echo 'load record';
						$this->is_loaded = true;
						return true;
				}
				//trigger_error('node::load() cannot load node');
				return false;
		}
		
		
		
		/**
		* Load node data from an array ($data)
		* This array must contain all the fields of the node table defined in config
		* 
		* 
		**/
		function loadByArray($data)
		{
				if (is_array($data))
				{
						foreach ($this->record->field as $field)
						{
								if (array_key_exists($field->getId(), $data))
								{
										$this->set($field->getId(), $data[$field->getId()]);
								}
								else
								{
										return false;
								}
						}
						$this->is_loaded = true;
						return true;
				}
				else
				{
						return false;
				}
		}
		
		
		/**
		* Saves the current state of this node
		* A node can be saved only if it has a parent or if it is root
		* 
		**/
		function save()
		{
				//echo 'record unloaded on node save';
				//$this->record->is_loaded = false;
				//$this->record->load();
				
				return $this->record->save();
				
				// todo, we must be safe with this !
				if ($this->get('parent_id') == 0 && $this->get('id') == 1)
				{
						return $this->record->save();
				}
				elseif ($this->get('parent_id') > 0 && $this->get('id') > 1)
				{
						return $this->record->save();
				}
				else
				{
						trigger_error('node::save() cannot save a node without parent id defined');
						return false;
				}
		}
		
		/**
		* Rebuilds the node nested set.
		* rebuilds nested set tree from adjacency list tree.
		* from http://www.sitepoint.com/article/hierarchical-data-database/3
		* 
		* 
		**/
		function rebuild($parent_id = 0, $left = 1)
		{
				//echo "<h1>rebuild called</h1>";
				
				global $thinkedit;
				$db = $thinkedit->getDb();
				$db->clearCache();
				
				//echo '<h1>rebuild called</h1>';
				// the right value of this node is the left value + 1
				$right = $left+1;
				
				// get all children of this node
				$sql = 'SELECT id FROM ' . $this->table . ' WHERE parent_id=' . $parent_id . ' order by sort_order';
				
				$results = $thinkedit->db->select($sql);
				
				if (is_array($results))
				{
						foreach ($results as $result)
						{
								$right = $this->rebuild($result['id'], $right);
						}
				}
				
				// we've got the left value, and now that we've processed
				// the children of this node we also know the right value
				
				/***************** big todo ***************/
				// todo : update node level as well :
				
				$sql = 'UPDATE '. $this->table .' SET left_id='. $left .', right_id='.	$right .' WHERE id='. $parent_id;
				
				$thinkedit->db->query($sql);
				
				// return the right value of this node (= +1)
				return $right+1; 
				
		}
		
		/**
		* Returns true if this node is a sibling of the given $node
		* Else returns false
		* 
		**/
		function isSiblingOf($node)
		{
				$node->load();
				$this->load();
				if ($this->getParentId() == $node->getParentId())
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Returns true if this node is a child of the given $node
		* Else returns false
		* 
		**/
		function isChildOf($node)
		{
				$node->load();
				$this->load();
				if ($this->getParentId() == $node->getId())
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Returns true if this node is an ancestor of $node
		* This funciton may be expensive in computing time
		* 
		**/
		function isAncestorOf($node)
		{
				$node->load();
				$this->load();
				
				// handle the case of this is parent of $node
				if ($this->getId() == $node->get('parent_id'))
				{
						return true;
				}
				
				$parents = $node->getParentUntilRoot();
				if (is_array($parents))
				{
						foreach ($parents as $parent)
						{
								if ($this->getId() == $parent->get('parent_id'))
								{
										return true;
								}
						}
				}
				return false;
				
		}
		
		/**
		* Returns true if this node has childrens
		* $options is an array documented in $this->getchildren()
		*
		**/
		function hasChildren($options = false)
		{
				global $thinkedit;
				
				if (!$thinkedit->context->enablePreview())
				{
						if ($this->getChildren($options))
						{
								return true;
						}
						else
						{
								return false;
						}
				}
				
				$this->load();
				$right = $this->get('right_id');
				$left = $this->get('left_id');
				
				$childs = ($right - $left - 1) / 2;
				
				if ($childs > 0)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* returns childrens (nodes)
		* 
		* $options is an array.
		* 
		* set :
		
		- $options['type'] to limit to nodes of a specific type
		- $options['class'] to limit to nodes of a specific class
		
		
		**/
		function getChildren($options = false)
		{
				
				/*
				echo  'called get children with options :<br>';
				echo '<pre>';
				print_r($options);
				echo '</pre>';
				*/
				
				$this->load();
				// todo : returns a node and not a record
				$where['parent_id'] = $this->get('id');
				
				global $thinkedit;
				
				if (!$thinkedit->context->enablePreview())
				{
						$where['publish'] = 1;
				}
				
				if (isset($options['type']))
				{
						$where['object_type'] = $options['type'];
				}
				
				if (isset($options['class']))
				{
						$where['object_class'] = $options['class'];
				}
				
				if (isset($option['navigation_only']))
				{
						// todo ;-)
				}
				
				$children =  $this->record->find($where, array('sort_order' => 'asc') );
				
				if ($children)
				{
						global $thinkedit;
						foreach ($children as $child)
						{
								$childs[] = $thinkedit->newNode($this->table, $child->get('id'), $child->getArray());
						}
						return $childs;
				}
				else
				{
						return false;
				}
		}
		
		
		/*
		Returns all sub nodes of this node
		*/
		function getAllChildren($opened_nodes = false)
		{
				global $thinkedit;
				$this->load();
				$left_id = $this->get('left_id');
				$right_id = $this->get('right_id');
				// this is critical function, so we use direct sql to be faster (no use of the record class here)
				// todo : check if it's faster this way
				
				$select = '';
				
				if ($opened_nodes)
				{
					$select = ' and ('; 
					foreach ($opened_nodes as $opened_node)
					{
						$select_opened[] = ' parent_id = ' . (int) $opened_node . ' '; 
					}
					
					$select .= implode (' or ', $select_opened);
					$select .= ')';
					// echo $select;
				}
				
				
				
				$sql = "SELECT * FROM {$this->table} WHERE left_id BETWEEN {$left_id} AND {$right_id} " . $select . " ORDER BY left_id ASC;";
				
				$results = $thinkedit->db->select($sql);
				
				if (is_array($results))
				{
						foreach ($results as $result)
						{
								$nodes[] = $thinkedit->newNode($this->table, $result['id'], $result);
						}
						return $nodes;
						
				}
				else
				{
						return false;
				}
				
		}
		
		
		/*
		Returns an array of 
		of what in fact ?
		
		This will be marked as deprecatd, this class becomes bloated
		*/
		function getFamilly()
		{
				trigger_error('node::getFamilly() is deprecated');
				global $thinkedit;
				$this->load();
				$parent_id = $this->get('parent_id');
				$id = $this->get('id');
				// this is critical function, so we use direct sql to be faster (no use of the record class here)
				// todo : check if it's faster this way
				$sql = "SELECT * FROM {$this->table} WHERE id = {$parent_id} or parent_id = {$parent_id} or parent_id = {$id} ORDER BY left_id ASC;";
				
				$results = $thinkedit->db->select($sql);
				
				if (is_array($results))
				{
						foreach ($results as $result)
						{
								$nodes[] = $thinkedit->newNode($this->table, $result['id'], $result);
						}
						return $nodes;
						
				}
				else
				{
						return false;
				}
		}
		
		function getSiblings($options = false)
		{
				$this->load();
				debug($this->get('parent_id'), 'Sibligns current parent ID');
				
				$where['parent_id'] = $this->get('parent_id');
				
				global $thinkedit;
				
				if (!$thinkedit->context->enablePreview())
				{
						$where['publish'] = 1;
				}
				
				
				$siblings =  $this->record->find($where, array('sort_order' => 'asc') );
				
				if ($siblings)
				{
						global $thinkedit;
						foreach ($siblings as $sibling)
						{
								/*
								echo $sibling->debug();
								echo '<hr>';
								*/
								$siblings_node[] = $thinkedit->newNode($this->table, $sibling->get('id'), $sibling->getArray());
						}
						return $siblings_node;
				}
				else
				{
						return false;
				}
		}
		
		function add($child_object, $where = 'bottom')
		{
				if ($child_object->getUid())
				{
						$uid = $child_object->getUid();
						global $thinkedit;
						$node = $thinkedit->newNode();
						$node->set('object_class', $uid['class']);
						$node->set('object_type', $uid['type']);
						$node->set('object_id', $uid['id']);
						$node->set('parent_id', $this->getId());
						
						
						// todo optimization (heavy optimisation possible, currently, the whole table is updated twice !!!!)
						// almost done
						if ($where=='top')
						{
								// if we add to TOP :
								// compute left and right values
								
								$parent_left = $this->record->get('left_id');
								
								$left = $parent_left + 1;
								$right = $parent_left + 2;
								/*
								echo 'parent left : ' . $parent_left . '<br/>';
								echo 'left : ' . $left . '<br/>';
								echo 'right : ' . $right . '<br/>';
								*/
								// create a "hole" in the tree
								// SQL used : update node set left_id = left_id + 1 where left_id > $parent_left
								
								$sql = "update {$this->table} set left_id = left_id + 2 where left_id > {$parent_left}";
								$results = $thinkedit->db->query($sql);
								
								$sql = "update {$this->table} set right_id = right_id + 2 where right_id > {$parent_left}";
								$results = $thinkedit->db->query($sql);
								
								$node->set('left_id', $left);
								$node->set('right_id', $right);
								
								// set order
								$id = $this->getId();
								$orders = $thinkedit->db->select("select min(sort_order) as sort_order from {$this->table} where parent_id = {$id}"); 
								
								if (is_array($orders))
								{
										$order = $orders[0]['sort_order'];
										//echo 'order = ' . $order;
										
										$node->set('sort_order', $order - 1);
								}
								
								
								// insert the new node
								
								$results = $node->record->insert();
								if ($results)
								{
										//$node->moveTop();
										//$this->rebuild();
										// because we rebuild, left, right and level values are changed.
										// we have to reload the node from the db.
										$node->is_loaded = false;
										$node->is_new = true;
										return $node;
								}
								else
								{
										trigger_error('node::add() failed saving node', E_USER_WARNING);
										return false;
								}
								
						}
						
						
						if ($where=='bottom')
						{
								// if we add to TOP :
								// compute left and right values
								
								$parent_right = $this->record->get('right_id');
								
								$left = $parent_right;
								$right = $parent_right + 1;
								/*
								echo 'parent right : ' . $parent_right . '<br/>';
								echo 'left : ' . $left . '<br/>';
								echo 'right : ' . $right . '<br/>';
								*/
								
								// create a "hole" in the tree
								// SQL used : update node set left_id = left_id + 1 where left_id > $parent_left
								
								$sql = "update {$this->table} set left_id = left_id + 2 where left_id >= {$parent_right}";
								$results = $thinkedit->db->query($sql);
								
								$sql = "update {$this->table} set right_id = right_id + 2 where right_id >= {$parent_right}";
								$results = $thinkedit->db->query($sql);
								
								
								
								$node->set('left_id', $left);
								$node->set('right_id', $right);
								
								
								// set order
								$id = $this->getId();
								$orders = $thinkedit->db->select("select max(sort_order) as sort_order from {$this->table} where parent_id = {$id}"); 
								
								if (is_array($orders))
								{
										$order = $orders[0]['sort_order'];
										//echo 'order = ' . $order;
										
										$node->set('sort_order', $order + 1);
								}
								
								
								// insert the new node
								
								$results = $node->record->insert();
								if ($results)
								{
										//$node->moveTop();
										//$this->rebuild();
										// because we rebuild, left, right and level values are changed.
										// we have to reload the node from the db.
										$node->is_loaded = false;
										$node->is_new = true;
										return $node;
								}
								else
								{
										trigger_error('node::add() failed saving node', E_USER_WARNING);
										return false;
								}
								
						}
						
						
					
						
						$results = $node->record->insert();
						if ($results)
						{
								$node->moveTop();
								$this->rebuild();
								
								// because we rebuild, left, right and level values are changed.
								// we have to reload the node from the db.
								$node->is_loaded = false;
								$node->is_new = true;
								return $node;
						}
						else
						{
								trigger_error('node::add() failed saving node', E_USER_WARNING);
								return false;
						}
				}
				else
				{
						trigger_error('node::add() must be given an object with getUid() method', E_USER_ERROR);
						return false;
				}
		}
		
		
		function changeParent($new_parent_id)
		{
				if ($this->isRoot())
				{
						trigger_error('node::changeParent() cannot change the parent of this node : it is root');
						return false;
				}
				
				if ($this->load())
				{
						
						if ($this->getId() == $new_parent_id)
						{
								trigger_error('node::changeParent() cannot change the parent of this node : you are trying to change the parent to itself');
								return false;
						}
						
						global $thinkedit;
						$parent = $thinkedit->newNode();
						$parent->setId($new_parent_id);
						if (!$parent->load())
						{
						  	trigger_error('node::changeParent() cannot change the parent of this node : I cannot load the parent you want to assing it');
								return false;
						}
						
						$this->set('parent_id', $new_parent_id);
						$this->set('level', false);
						$this->save();
						$this->rebuild();
						return true;
						
				}
				else
				{
						trigger_error('node::changeParent() cannot change the parent of this node because I can\'t load it');
						return false;
				}
		}
		
		function getUid()
		{
				$uid['class'] = 'node';
				$uid['type'] = 'node';
				$uid['id'] = $this->getId();
				return $uid;
		}
		
		
		// what's the status of this ?
		function getType()
		{
				return 'node';
		}
		
		function getClass()
		{
				return 'node';
		}
		
		
		
		/**
		* Returns the content object of this node
		*/
		function getContent()
		{
				
				global $thinkedit;
				$this->load();
				$uid['class'] = $this->get('object_class');
				$uid['type'] = $this->get('object_type');
				$uid['id'] = $this->get('object_id');
				
				////
				/*
				$object = $thinkedit->newObject($uid);
				return $object;
				*/
				////
				
				
				// todo
				/// this is an optimization. Must be turned on, later...
				if ($this->get('cache') <> '')
				{
						$data = unserialize($this->get('cache'));
						//print_r($data);
						return $thinkedit->newObject($uid, $data);
				}
				else
				{
					$object = $thinkedit->newObject($uid);
					if ($object)
					{
						$object->load();
						if ($data = $object->getArray())
						{
							$cache = serialize($data);
							$this->set('cache', $cache);
							$this->save();
						}
						return $object;
					}
					else
					{
						trigger_error('node::getContent() : cannot get content');
						return false;
					}
				}
		}
		
		
		/**
		* Must be called everytime the content attached to this node is updated
		*/
		function clearContentCache()
		{
				$this->set('cache', '');
				$this->save();
		}
		
		function getTitle()
		{
				$this->load();
				$content = $this->getContent();
				$content->load();
				$title = $content->getTitle();
				return $title;
		}
		
		
		function getIcon()
		{
				$content = $this->getContent();
				//$content->load();
				return $content->getIcon();
		}
		
		/**
		* Loads the root node
		*/
		function loadRootNode()
		{
				// optimization done : if root is on id = 1, it is deirectly returned
				$root = $this->load(1);
				
				if ($root)
				{
						return $root;
				}
				
				
				$root = $this->record->find(array('parent_id' => 0));
				
				if ($root)
				{
						return $this->load($root[0]->getId());
				}
				else
				{
						if ($this->record->count() == 0)
						{
								trigger_error('node::loadRootNode() : no nodes found in db. Please create at least one node in admin or in installer', E_USER_WARNING);
								return false;
						}
						else
						{
								trigger_error('node::loadRootNode() : no nodes with parent_id = 0 found in db. Please create at least one node in admin or in installer', E_USER_WARNING);
								return false;
						}
				}
		}
		
		
		
		/**
		* Create a root node using object as the content
		* 
		*/
		function saveRootNode($object)
		{
				
				if ($object->getUid())
				{
						$uid = $object->getUid();
						global $thinkedit;
						$node = $thinkedit->newNode();
						$node->set('object_class', $uid['class']);
						$node->set('object_type', $uid['type']);
						$node->set('object_id', $uid['id']);
						$node->set('parent_id', 0);
						$node->set('id', 1);
						$results = $node->record->insert();
						if ($results)
						{
								return $node;
						}
						else
						{
								trigger_error('node::saveRootNode() failed saving root node', E_USER_WARNING);
								return false;
						}
				}
				else
				{
						trigger_error('node::saveRootNode() must be given an object with getUid() method', E_USER_ERROR);
						return false;
				}
		}
		
		function isRoot()
		{
				if ($this->getId() == 1)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Returns all the parents of this node until root is met
		* 
		*/
		function getParentUntilRoot()
		{
				global $thinkedit;
				$this->load();
				$left_id = $this->get('left_id');
				$right_id = $this->get('right_id');
				
				$sql = "SELECT * FROM {$this->table} WHERE left_id < {$left_id} AND right_id > {$right_id} ORDER BY level desc";
				
				$results = $thinkedit->db->select($sql);
				
				if (is_array($results))
				{
						foreach ($results as $result)
						{
								$nodes[] = $thinkedit->newNode($this->table, $result['id'], $result);
						}
						return $nodes;
						
				}
				else
				{
						return false;
				}
		}
		
		
		/**
		* Return the parent of the curent node which is at the $level level.
		*/
		function getParentByLevel($level)
		{
			$parents = $this->getParentUntilRoot();
			if (is_array($parents))
			{
				foreach ($parents as $parent)
				{
					if ($parent->getLevel() == $level)
					{
						return $parent;
					}
					
				}
			}
			return false;
		}
		
		
		/**
		* Generate the path of this node and save it to the DB
		* 
		*/
		function updatePath()
		{
				$parents[] = $this;
				
				$parents_until_root = $this->getParentUntilRoot();
				
				if (is_array($parents_until_root))
				{
						foreach ($parents_until_root as $parent)
						{
								$parents[] = $parent;
						}
				}
				
				$parents = array_reverse($parents);
				
				$path = '.';
				
				foreach ($parents as $parent)
				{
						$path .= str_pad($parent->getId(), 5, '0', STR_PAD_LEFT) . '.';
						
				}
				$this->set('path', $path);
				$this->save();
				
				return $path;
		}
		
		/**
		* Calculate and returns the path
		* 
		*/
		function getPath()
		{
				$parents[] = $this;
				
				$parents_until_root = $this->getParentUntilRoot();
				
				if (is_array($parents_until_root))
				{
						foreach ($parents_until_root as $parent)
						{
								$parents[] = $parent;
						}
				}
				
				$parents = array_reverse($parents);
				$path = '/';
				
				foreach ($parents as $parent)
				{
						$content = $parent->getContent();
						$content->load();
						$path .= $content->getTitle() . '/';
				}
				
				return $path;
		}
		
		/**
		* Returns the level of the node
		* 
		*/
		function getLevel()
		{
				$this->load();
				
				if ($this->get('parent_id') == 0)
				{
						return 0;
				}
				
				// Optimisation currently removed because this is a kind of cache that must be cleaned on every node move
				// re added, testing in progress
				if ($this->get('level') > 0)
				{
						return $this->get('level');
				}
				
				else
				{
						global $thinkedit;
						$db = $thinkedit->getDb();
						$db->clearCache();
						
						$parents = $this->getParentUntilRoot();
						if ($parents)
						{
								$level = count($parents);
						}
						else
						{
								$level = 0;
						}
						if ($this->load())
						{
								$this->set('level', $level);
								$this->save();
						}
						return $level;
				}
		}
		
		
		
		
		function debug()
		{
				return $this->record->debug();
		}
		
		
		/**
		* Returns a list (array) of allowed items you can add inside this node
		this array is an array of UID's
		
		class / type / (id)
		* 
		*/
		function getAllowedItems()
		{
				$content = $this->getContent();
				
				if ($content)
				{
						if (isset($content->config['allowed_items']['record']))
						{
								foreach ($content->config['allowed_items']['record'] as $key=>$value)
								{
										$item['class'] = 'record';
										$item['type'] = $key;
										$items[] = $item;
								}
								return $items;
								
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
				global $thinkedit;
				
				$config = $thinkedit->newConfig();
				$tables = $config->getTableList();
				
				// all tables
				foreach ($tables as $table_id)
				{
						$table = $thinkedit->newTable($table_id);
						$item['class'] = 'record';
						$item['type'] = $table_id;
						$item['title'] = $table->getTitle();
						$items[] = $item;
						
				}
				
				$item['class'] = 'filesystem';
				$item['type'] = 'main';
				$item['title'] = translate('file');
				$items[] = $item;
				
				return $items;
		}
		
		/**
		* Returns the order of the node
		* 
		*/
		function getOrder()
		{
				return $this->record->get('sort_order');
		}
		
		
		function moveUp()
		{
				$this->load();
				// first find items before this one
				$siblings = $this->getSiblings();
				
				// if we have only one item or no items, not needed to do anything
				if (!is_array($siblings) || count ($siblings) < 2)
				{
						return true;
				}
				
				
				if ($siblings)
				{
						foreach ($siblings as $sibling)
						{
								$sibling->load();
								$sort_orders[] = $sibling->get('sort_order'); 
						}
				}
				
				rsort($sort_orders);
				
				debug($sort_orders, 'Sort Orders');
				
				if (is_array($sort_orders))
				{
						foreach ($sort_orders as $sort_order)
						{
								if ($sort_order < $this->get('sort_order'))
								{
										$higher_orders[] = $sort_order;
								}
						}
				}
				
				if (isset($higher_orders))
				{
						//echo '$higher_orders';
						//print_r ($higher_orders);
						
						
						// if we have 2 or more
						if (count($higher_orders) >= 2)
						{
								$a = $higher_orders[0];
								$b = $higher_orders[1];
								$new_order = $b + (($a - $b) / 2);
								//echo 'New order : ' . $new_order;
								
								
								$this->set('sort_order', $new_order);
								$this->save();
								// this IS a hack ;-). But else DB cache will keep the old order and it will be bad for getChildren (it will give previous order)
								// This took one hour to figure out...
								global $thinkedit;
								$db = $thinkedit->getDb();
								$db->clearCache();
								$this->rebuild();
								return true;
						}
						else // if we have one, move top
						{
								return $this->moveTop();
						}
				}
				else
				{
						// if we have none
						// we are at top, do nothing
						trigger_error('node::moveUp() already on top');
				}
				
		}
		
		function moveDown()
		{
				//echo  'called move down<br>';
				//echo 'order before move' . $this->getOrder();
				
				$this->load();
				// first find items on the same level as this one
				$siblings = $this->getSiblings();
				
				// if we have only one item or no items, not needed to do anything
				if (!is_array($siblings) || count ($siblings) < 2)
				{
						return true;
				}
				
				
				if ($siblings)
				{
						foreach ($siblings as $sibling)
						{
								$sibling->load();
								$sort_orders[] = $sibling->get('sort_order'); 
						}
				}
				
				sort($sort_orders);
				
				debug($sort_orders, 'Sort Orders');
				
				if (is_array($sort_orders))
				{
						foreach ($sort_orders as $sort_order)
						{
								if ($sort_order > $this->get('sort_order'))
								{
										$higher_orders[] = $sort_order;
								}
						}
				}
				
				if (isset($higher_orders))
				{
						//echo '$higher_orders';
						//print_r ($higher_orders);
						
						
						// if we have 2 or more
						if (count($higher_orders) >= 2)
						{
								$a = $higher_orders[0];
								$b = $higher_orders[1];
								$new_order = $b + (($a - $b) / 2);
								//echo 'New order : ' . $new_order;
								
								
								$this->set('sort_order', $new_order);
								$this->save();
								// echo 'order after move save' . $this->getOrder();
								// this IS a hack ;-). But else DB cache will keep the old order and it will be bad for getChildren (it will give previous order)
								// This took one hour to figure out...
								global $thinkedit;
								$db = $thinkedit->getDb();
								$db->clearCache();
								$this->rebuild();
								return true;
								
						}
						else // if we have one, move top
						{
								return $this->moveBottom();
						}
				}
				else
				{
						// if we have none
						// we are at top, do nothing
						trigger_error('node::moveDown() already on bottom');
				}
				
		}
		
		function moveBottom()
		{
				$this->load();
				// first find items before this one
				$siblings = $this->getSiblings();
				
				if (!is_array($siblings) || count ($siblings) < 2)
				{
						return true;
				}
				if ($siblings)
				{
						foreach ($siblings as $sibling)
						{
								$sibling->load();
								$sort_orders[] = $sibling->get('sort_order'); 
						}
				}
				
				
				
				if (is_array($sort_orders))
				{
						rsort($sort_orders);
						debug($sort_orders, 'Sort Orders');
						$new_order = $sort_orders[0] + 1;
						$this->set('sort_order', $new_order);
						$this->save();
						// this IS a hack ;-). But else DB cache will keep the old order and it will be bad for getChildren (it will give previous order)
						// This took one hour to figure out...
						global $thinkedit;
						$db = $thinkedit->getDb();
						$db->clearCache();
						$this->rebuild();
						return true;
				}
		}
		
		function moveTop()
		{
				$this->load();
				// first find items before this one
				$siblings = $this->getSiblings();
				
				if (!is_array($siblings) || count ($siblings) < 2)
				{
						return true;
				}
				
				if ($siblings)
				{
						foreach ($siblings as $sibling)
						{
								$sibling->load();
								$sort_orders[] = $sibling->get('sort_order'); 
						}
				}
				
				
				
				if (is_array($sort_orders))
				{
						sort($sort_orders);
						debug($sort_orders, 'Sort Orders');
						
						$new_order = $sort_orders[0] - 1;
						$this->set('sort_order', $new_order);
						$this->save();
						// this IS a hack ;-). But else DB cache will keep the old order and it will be bad for getChildren (it will give previous order)
						// This took one hour to figure out...
						global $thinkedit;
						$db = $thinkedit->getDb();
						$db->clearCache();
						$this->rebuild();
						return true;
				}
		}
		
		
		function publish()
		{
				global $thinkedit;
				$db = $thinkedit->getDb();
				$db->clearCache();
				
				$this->record->set('publish', 1);
				$this->is_loaded = false;
				
				return $this->record->save();
		}
		
		function unPublish()
		{
				global $thinkedit;
				$db = $thinkedit->getDb();
				$db->clearCache();
				
				$this->is_loaded = false;
				$this->record->set('publish', 0);
				return $this->record->save();
		}
		
		function isPublished()
		{
				if ($publish = $this->record->get('publish'))
				{
						if ($publish == 1)
						{
								return true;
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
		
		
		function useInNavigation()
		{
				trigger_error('deprecated, use $this->isUsedIn(\'navigation\') instead');
				// todo : configurable somewhat :-)
				$content = $this->getContent();
				
				if ($content->useInNavigation())
				{
						return true;
				}
				else
				{
						return false;
				}
				
				
				if ($this->get('object_class') == 'record' && $this->get('object_type') == 'page')
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/**
		* Returns true if this node is used in $what
		* This means that you can define use case for each node. For instance, use could be "public", "private" or wathever
		* Not very usefulle for nodes, but could be used anyway
		*/
		function isUsedIn($what)
		{
				if (isset($this->config['use'][$what]))
				{
						//print_r ( $this->config['use']);
						if ($this->config['use'][$what] == 'false')
						{
								return false;
						}
						else
						{
								return true;
						}
				}
				else
				{
						// this is the default behavior. 
						// If a particular use is not defined in config, we assume the field must be shown. 
						return true;
				}
		}
		
}


?>
