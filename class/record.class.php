<?php

/**
* A record is the base class for any record manipulation in the DB
* Use $record = $thinkedit->newRecord($table_name) to instantiate a new record for any use (read and write) 
* 
* This record class uses the active record pattern
* 
*/
class record
{
		/**
		* When you instantiate a record, use $thinkedit->newRecord($table)
		* 
		* 
		* 
		*/
		function record($table)
		{
				
				// todo : optimize !!!!!
				
				$this->table_name = $table;
				$this->table = $table;
				
				// load config
				
				global $thinkedit;
				if (isset($thinkedit->config['content'][$table]))
				{
						$this->config = $thinkedit->config['content'][$table];
				}
				elseif (isset($thinkedit->config['table'][$table]))
				{
					$this->config = $thinkedit->config['table'][$table];
				}
				else
				{
						trigger_error('record::record() Table called "' . $this->table . '" not found in config, check table id spelling in config file / in code', E_USER_ERROR);
				}
				
				
				// init fields
				if (is_array($this->config['field']))
				{
						foreach ($this->config['field'] as $id=>$field)
						{
								$this->field[$id] = $thinkedit->newField($table, $id);
						}
				}
				else
				{
						trigger_error('record::record() Table called "' . $this->table . '" has no fields defined. Check config file', E_USER_ERROR);
				}
				
				// init DB
				$this->db = $thinkedit->getDb();
				
				//echo '<pre>';
				//print_r($this->config);
		}
		
		function getTableName()
		{
				return $this->table_name;
		}
		
		/**
		* Returns the value of the field named $field
		* 
		* 
		* 
		*/
		function get($field)
		{
				if (isset($this->field[$field]))
				{
						return $this->field[$field]->get();
				}
				else
				{
						return false;
						//trigger_error('record::get() field ' . $field .' not found');
				}
		}
		
		function getSqlSafe($field)
		{
				global $thinkedit;
				$db = $thinkedit->getDb();
				return $db->escape($this->field[$field]->get());
		}
		
		/**
		* Sets the value of the field $field to $value
		* 
		* 
		* 
		*/
		function set($field, $value)
		{
				$this->is_loaded = false;
				//debug ($field, 'field');
				//debug($value, 'value');
				if (isset($this->field[$field]))
				{
						$this->field[$field]->set($value);
				}
				else
				{
						//trigger_error('record::set() field ' . $field .' not found');
						return false;
				}
		}
		
		
		/**
		* Load will only load a single record and assign values to the current object
		* Look at find() for multiple load
		* 
		* You must fill all the primary keys of this record to be able to load it
		* 
		*/
		function load()
		{
				global $thinkedit;
				$user = $thinkedit->getUser();
				if ($user->hasPermission('view', $this))
				{
						if (isset($this->is_loaded) && $this->is_loaded)
						{
								return true;
						}
						else
						{
								
								if ($this->checkPrimaryKey())
								{
										
										$sql = "select * from " . $this->getTableName() . " where ";
										foreach ($this->field as $field)
										{
												if ($field->isPrimary())
												{
														$where[] =  $field->getId() . '=' . "'" . $this->db->escape($field->get()) . "'";
												}
										}
										$sql .= implode($where, ' and ');
										
										debug($sql, 'Sql query');
										
										global $thinkedit;
										$db = $thinkedit->getDb();
										
										$results = $db->select($sql);
										
										if ($results && count($results) == 1)
										{
												debug($results, 'results for select query');
												foreach ($results[0] as $key=>$field)
												{
														$this->set($key, $field);
												}
												$this->is_loaded = true;
												return true;
										}
										else
										{
												return false;
										}
								}
								else
								{
										// is it an error to try to load a record without filling all the primary keys?
										//trigger_error("record::load() you must set all primary keys if you want to load a record");
										// we should return false and set an error somewhere... 
										return false;
								}
						}
				}
		}
		
		
		
		/**
		* given an array, the record is filled with the data, as long as the array contains all the fields of this record
		* if it is the case, $this->is_loaded = true, and further request for $this->load() won't do an sql query 
		* 
		* This is an optimization
		*/
		function loadByArray($data)
		{
				foreach ($this->field as $field)
				{
						if (array_key_exists($field->getId(), $data))
						{
								$this->set($field->getId(), $data[$field->getId()]);
						}
						else
						{
								//trigger_error('object not loaded');
								return false;
						}
				}
				$this->is_loaded = true;
				return true;
		}
		
		/**
		* Will return an array of records
		* 
		* @param array $where is an array of field / values to limit returned records
		* @param array $order is an array of field / 'asc' or 'desc' values to order
		* @param array $limit set $limit['start'] and $limit['stop'] if you want to limit
		*/
		function find($where = false, $order = false, $limit = false)
		{
				/*
				This is nicer
				or
				Is this nicer : 
				
				$args['where'][0]['field'] = 'title'
				$args['where'][0]['cond'] = 'like';
				$args['where'][0]['value'] = '%test%';
				
				$args['limit']['start'] = 10;
				$args['limit']['end'] = 100;
				
				$args['sort'][0]['field'] = 'title';
				$args['sort'][0]['order'] = 'asc';
				
				
				$record->find($args) ...
				
				or provide both ?
				
				*/
				
				global $thinkedit;
				$user = $thinkedit->getUser();
				if ($user->hasPermission('view', $this))
				{
						$sql = "select * from " . $this->getTableName();
						
						if (is_array($where))
						{
								$sql .= " where ";
								foreach ($where as $key=>$value)
								{
										$where_clause[] =  $key . '=' . "'" . $this->db->escape($value) . "'";
										
								}
								$sql .= implode($where_clause, ' and ');
						}
						
						
						// todo  : validation !
						if (is_array($order))
						{
								$sql .= " order by ";
								foreach ($order as $field=>$sort_order)
								{
										$order_by_clause[] =  $field . " " . $sort_order;
										
								}
								$sql .= implode($order_by_clause, ' , ');
						}
						
						if (is_array($limit))
						{
								$sql .= " limit  " . $limit['start'] . ',' . $limit['stop'];
						}
						
						
						debug($sql, 'record:find() sql');
						
						global $thinkedit;
						
						
						$results = $this->db->select($sql);
						
						if ($results && count($results) > 0)
						{
								global $thinkedit;
								//debug($results, 'record:find() results for select query');
								foreach ($results as $result)
								{
										$record = $thinkedit->newRecord($this->getTableName());
										foreach ($result as $key=>$field)
										{
												$record->set($key, $field);
										}
										$record->is_loaded = true;
										$records[] = $record;
								}
								return $records;
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
		
		/**
		* Returns the number of records (you can use $where)
		* @param array $where @see record::find()
		* 
		* 
		*/
		function count($where = false)
		{
				global $thinkedit;
				$sql = "select count(*) from " . $this->getTableName();
				
				if (is_array($where))
				{
						$sql .= " where ";
						foreach ($where as $key=>$value)
						{
								$where_clause[] =  $key . '=' . "'" . $this->db->escape($value) . "'";
								
						}
						$sql .= implode($where_clause, ' and ');
				}
				
				debug($sql, 'record:count() sql');
				$results = $this->db->select($sql);
				if ($results)
				{
						$count = $results[0]['count(*)'];
						//print_r($results);
						//print_r($count);
						return $count;
				}
				else
				{
						return 0;
				}
				/*
				// todo use mysql count instead
				$results = $this->find($where, $order, $limit);
				if (is_array($results))
				{
						return count($results);
				}
				else
				{
						return false;
				}
				}
				*/
		}
		
		/**
		* Will return the first found item, this is much like $this->find()
		* 
		* 
		* 
		*/
		function findFirst($where = false, $order = false)
		{
				$results = $this->find($where, $order, '1');
				if (is_array($results))
				{
						return $results[0];
				}
				return false;
		}
		
		/**
		* Will save the current record to the DB
		* If some of the primary keys are filled and the record is found in the DB, the record is updated
		* Else, the record is inserted in the DB
		* 
		* 
		*/
		function save()
		{
				// build an array of primary fields
				foreach ($this->field as $field)
				{
						if ($field->isPrimary())
						{
								//	if ($field->isEmpty())
								//	{
										//trigger_error(__METHOD__ . ' cannot save if all primary keys are not defined');
								//	}
								$fields[$field->getId()] = $field->get();
						}
				}
				
				if (is_array($fields))
				{
						// if I find the same record in the DB based on the keys, I update
						if ($this->find($fields))
						{
								return $this->update();
						}
						else // else I insert
						{
								return $this->insert();
						}
				}
				else
				{
						trigger_error('record::save() :  no primary fields, cannot save');
						return false;
				}
		}
		
		
		
		
		/**
		* Will update the DB with this record
		* 
		* 
		* 
		*/
		function update()
		{
				global $thinkedit;
				$user = $thinkedit->getUser();
				if ($user->hasPermission('insert', $this))
				{
						global $thinkedit;
						
						$sql = "update " . $this->getTableName() . ' set ';
						foreach ($this->field as $id=>$field)
						{
								$set[] =  $id . '=' . "'" . $this->db->escape($this->get($id)) . "'"; 
						}
						$sql .= implode($set, ', ');
						
						$sql .= " where ";
						foreach ($this->field as $field)
						{
								if ($field->isPrimary())
								{
										if ($field->isEmpty())
										{
												trigger_error('record::update cannot save if all primary keys are not defined');
										}
										$where[] =  $field->getId() . '=' . "'" . $this->db->escape($field->get()) . "'";
								}
						}
						$sql .= implode($where, ' and ');
						debug($sql, 'record::update()');
						if ($this->db->query($sql))
						{
								return true;
						}
						else
						{
								trigger_error('record::save() failed while updating record', E_USER_WARNING);
								return false;
						}
				}
		}
		
		
		/**
		* Will insert this record in the DB
		* 
		* 
		* 
		*/
		function insert()
		{
				$sql = "insert into " . $this->getTableName();
				foreach ($this->field as $id=>$field)
				{
						// we don't want to use id's in insert clause as we imply that id's are autoincrement fields
						if ($field->getType() <> 'id')
						{
								$fields_names[] =  $id;
						} // but if id is set, we use it (currently only used by node->saveRootNode() but hell...
						elseif ($field->get())
						{
								$fields_names[] =  $id;
						}
				}
				
				// todo : build a list of fields then use it for both the field name and value to build the sql query
				
				$sql.= ' ( ';
				$sql .= implode($fields_names, ', ');
				$sql.= ' ) ';
				$sql.= ' values ';
				foreach ($this->field as $id=>$field)
				{
						// idem : 
						// we don't want to use id's in insert clause as we imply that id's are autoincrement fields
						if ($field->getType() <> 'id')
						{
								$values[] =  "'" . $this->db->escape($this->get($id)) . "'";
						}
						elseif ($field->get())
						{
								$values[] =  "'" . $this->db->escape($this->get($id)) . "'";
						}
				}
				$sql.= ' ( ';
				$sql .= implode($values, ', ');
				$sql.= ' ) ';
				
				debug($sql, 'record::insert()');
				if ($this->db->query($sql))
				{
						// when finished, set the id of the field to the new autoinserted id (mysql at least)
						$this->field[$this->getIdField()]->set($this->db->insertID());
						return true;
				}
				else
				{
						trigger_error('record::save() failed while inserting record', E_USER_WARNING);
						return false;
				}
		}
		
		/**
		* Will delete this record form the DB
		* 
		* 
		* 
		*/
		function delete()
		{
				$this->is_loaded = false;
				global $thinkedit;
				$user = $thinkedit->getUser();
				if ($user->hasPermission('delete', $this))
				{
						if ($this->checkPrimaryKey())
						{
								
								$sql = "delete from " . $this->getTableName() . " where ";
								
								foreach ($this->field as $field)
								{
										if ($field->isPrimary())
										{
												$where[] =  $field->getId() . '=' . "'" . $this->db->escape($field->get()) . "'";
										}
								}
								
								$sql .= implode($where, ' and ');
								
								$sql .= ' limit 1 ';
								
								$results = $this->db->query($sql);
								
								if ($results && count($results) == 1)
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
								trigger_error("record::delete() you must set all primary keys if you want to delete a record", E_USER_WARNING);
								return false;
						}
				}
		}
		
		
		
		
		/**
		* returns true if all primary keys are _set_
		* false else
		* 
		* 
		* 
		*/
		function checkPrimaryKey()
		{
				foreach ($this->field as $field)
				{
						if ($field->isEmpty() and $field->isPrimary())
						{
								return false;
						}
				}
				
				return true;
				
				
		}
		
		
		
		/**
		* Returns the primary keys in this record
		* 
		* 
		* 
		*/
		function getPrimaryKeys()
		{
				foreach ($this->field as $field)
				{
						if ($field->isPrimary())
						{
								$list[] = $field->getId();
						}
				}
				if (is_array($list))
				{
						return $list;
				}
				else
				{
						trigger_error('record::getPrimaryKeys() : no primary keys found in table called ' . $this->table, E_USER_WARNING);
						return false;
				}
		}
		
		
		
		/**
		* Will set all fields from an array of key=> values (for instance from $_POST)
		* 
		* Then you can validate and or save
		* 
		*/
		function setArray($array)
		{
				// trigger_error('deprecated, use $this->loadByArray instead');
				$this->is_loaded = false;
				if (is_array($array))
				{
						foreach ($array as $id=>$value)
						{
								if (isset($this->field[$id]))
								{
										$this->field[$id]->set($value);
								}
						}
						return true;
				}
				else
				{
						trigger_error('$array is not an array');
						return false;
				}
				
		}
		
		/**
		* Will return an array of all fields
		* 
		* 
		* 
		*/
		function getArray()
		{
				foreach ($this->field as $field)
				{
						$data[$field->getId()] = $field->get();
				}
				return $data;
		}
		
		
		
		/**
		* Returns the ID of this record
		* 
		* 
		* 
		*/
		function getId()
		{
				foreach ($this->field as $field)
				{
						if ($field->getType() == 'id' || $field->getType() == 'stringid')
						{
								return $field->get();
						}
				}
				trigger_error('record::getId() : no id field found in table called ' . $this->getTableName(), E_USER_WARNING);
				return false;
		}
		
		
		function getIdField()
		{
				foreach ($this->field as $field)
				{
						if ($field->getType() == 'id' || $field->getType() == 'stringid')
						{
								return $field->getName();
						}
				}
				trigger_error('record::getId() : no id field found in table called ' . $this->getTableName(), E_USER_WARNING);
				return false;
		}
		
		// very important concept
		function getUid()
		{
				$data['class'] = 'record';
				$data['type'] = $this->getTableName();
				// builds an array of all primary keys
				foreach ($this->field as $field)
				{
						if ($field->isPrimary())
						{
								$data[$field->getId()] = $field->get();
						}
						
				}
				
				return $data;
		}
		
		
		function getClass()
		{
				return 'record';
		}
		
		function getType()
		{
				return $this->getTableName();
		}
		
		function getTitle($size = false)
		{
				/*
				if (!$this->is_loaded)
				{
						trigger_error('record::getTitle() trying to get title on an unloaded record');
				}
				*/
				$title = '';
				foreach ($this->field as $field)
				{
						if ($field->isTitle())
						{
								$title[]= $field->get();
						}
				}
				if (is_array($title))
				{
						return implode($title, ', ');
				}
				else
				{
						trigger_error('No title defined in config for table ' . $this->table_name);
						return false;
				}
		}
		
		
		/**
		* Sets the title of this record
		* 
		*
		*/
		function setTitle($title)
		{
				foreach ($this->field as $field)
				{
						if ($field->isTitle())
						{
								$this->field[$field->getId()]->set($title);
								return true;
						}
				}
				trigger_error('No title defined in config for table ' . $this->table_name);
				return false;
		}
		
		function getIcon()
		{
				if (isset($this->config['icon']))
				{
						return 'ressource/image/icon/small/' . $this->config['icon'];
				}
				else
				{
						return 'ressource/image/icon/small/page_white.png';
				}
		}
		
		
		
		function debug()
		{
				$out = '';
				$out .= '<div>';
				$out .= '<h1>Record debug</h1>';
				foreach ($this->field as $field)
				{
						$out .= '<b>' .  $field->getId() . ' : ' .  $field->get(); 
						if ($field->validate())
						{
								$out .= ' (valid field)';
						}
						else
						{
								$out .= ' (invalid field)';
						}
						
						$out .= '</b><br/>';
				}
				
				if ($this->validate())
				{
						$out .= ' (valid record)';
				}
				else
				{
						$out .= ' (invalid record)';
				}
				
				$out .= '</div>';
				return $out;
		}
		
		
		function useInNavigation()
		{
				trigger_error('deprecated, use $this->isUsedIn(\'navigation\') instead');
				
		}
		
		
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
		
		
		/**
		* Returns true if this record is multilingual
		* of course, only record_multligula objects will return true
		*
		*/
		function isMultilingual()
		{
				return false;
		}
		
		
		
		function validate()
		{
				$validate = true;
				if (is_array($this->field))
				{
						foreach ($this->field as $field)
						{
								if (!$this->field[$field->getId()]->validate())
								{
										$validate = false;
								}
						}
				}
				else
				{
						$validate = false;
				}
				
				return $validate;
		}
		
		
}

?>
