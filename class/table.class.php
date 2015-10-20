<?php
/*
Class to deal with sql tables on the server (will allow to create tables for instance)
*/
class table
{
		
		
		function table($table)
		{
				$this->table=$table;
				global $thinkedit;
				$this->db = $thinkedit->getDb();
				
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
						trigger_error('table::table() Table called "' . $this->table . '" not found in config, check table id spelling in config file / in code');
				}
		}
		
		
		/*
		Returns table id fomr config
		*/
		function getTableName()
		{
				return $this->table;
		}
		
		
		function getTitle()
		{
				global $thinkedit;
				$user = $thinkedit->getUser();
				if (isset($this->config['title'][$user->getLocale()]))
				{
						return $this->config['title'][$user->getLocale()];
				}
				else
				{
						return $this->table;
				}
		}
		
		function getHelp()
		{
				global $thinkedit;
				$user = $thinkedit->getUser();
				if (isset($this->config['help'][$user->getLocale()]))
				{
						return $this->config['help'][$user->getLocale()];
				}
				else
				{
						return $this->table;
				}
		}
		
		function getIcon()
		{
				//todo : implement this!
				if (isset($this->config['icon']))
				{
						return ROOT_URL . '/edit/ressource/image/icon/small/' . $this->config['icon'];
				}
				else
				{
						return ROOT_URL . '/edit/ressource/image/icon/small/text-x-generic.png';
				}
		}
		
		
		/*
		Returns real sql table name, not table id in config
		*/
		function getSqlTableName()
		{
				// todo : handle table name in config instead of using id from config
				// todo use this function instead of getTableName in sql statements
				// todo feature : sql table prefix like thinkedit_mytable or te_mytable (prefix = te_)
				// prefix could be a tag in config
				return $this->table;
		}
		
		
		// very important concept
		function getUid()
		{
				$data['class'] = 'table';
				$data['type'] = $this->getTableName();
				return $data;
		}
		
		function getId()
		{
				return $this->getTableName();
		}
		
		
		function hasField($field_name)
		{
				$fields = $this->db->select('describe ' . $this->getTableName() );
				debug($fields, 'Fields found in table ' . $this->getTableName() );
				foreach ($fields as $field)
				{
						if ($field['Field'] == $field_name)
						{
								return true;
						}
				}
				return false;
		}
		
		
		function createField($field_id)
		{
				if (isset($this->config['field'][$field_id]))
				{
						
						$type = $this->config['field'][$field_id]['type'];
						$name = $field_id; // todo configurability !
						
						$sql = 'alter table ' . $this->getTableName() . ' add column ';
						//$name, $type, $extra = false
						
						if ($type == 'string')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'email')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'login')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'template')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'publish')
						{
								$sql .= $name . ' int';
						}
						
						elseif ($type == 'stringid')
						{
								$sql .= $name . ' varchar(255)';
						}
						elseif ($type == 'locale')
						{
								$sql .= $name . ' varchar(10)';
						}
						
						elseif ($type == 'password')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'image')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'file')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'publicfile')
						{
								$sql .= $name . ' varchar(255)';
						}
						
						elseif ($type == 'text')
						{
								$sql .= $name . ' mediumtext';
						}
						
						elseif ($type == 'richtext')
						{
								$sql .= $name . ' mediumtext';
						}
						
						elseif ($type == 'int')
						{
								$sql .= $name . ' int';
						}
						
						elseif ($type == 'lookup')
						{
								$sql .= $name . ' int';
						}
						
						elseif ($type == 'datetime')
						{
								$sql .= $name . ' ' . $type;
						}
						elseif ($type == 'created')
						{
								$sql .= $name . ' datetime';
						}
						elseif ($type == 'date')
						{
								$sql .= $name . ' date';
						}
						elseif ($type == 'order')
						{
								$sql .= $name . ' float';
						}
						
						else
						{
								trigger_error("type $type is not supported");
						}
						
						
						
						debug($sql, 'Sql from createField');
						
						$results = $this->db->query($sql);
						
						// handle the case of add a locale field
						if ($type == 'locale')
						{
								
								/*
								This is a very special case !!!
								
								When we add a locale field to the db, a new primary kay must be defined, including the locale field (and not only id)
								This seems the most logical place to handle this.
								
								Once added, a locale field cannot be easily removed, because it would break primary key contraints
								*/
								
								/*
								ALTER TABLE `multilingual_page` DROP PRIMARY KEY ,
								ADD PRIMARY KEY ( `id` , `locale` )
								*/
								
								
								$pk_locale_sql = 'ALTER TABLE `' . $this->getTableName() . '` DROP PRIMARY KEY ,';
								$pk_locale_sql .= 'ADD PRIMARY KEY ( `id` , `'. $name .'` )';
								$this->db->query($pk_locale_sql);
						}
						
						return $results;
						//examples :
						// ALTER TABLE `article` ADD `test` VARCHAR( 250 ) NOT NULL ;
				}
				else
				{
						trigger_error(__METHOD__ . " field $field_id not in config");
						return false;
				}
				
		}
		
		function isUsedIn($what)
		{
				
				if (isset($this->config['use'][$what]))
				{
						//print_r ( $this->config['use']);
						if ($this->config['use'][$what] == 'true' || $this->config['use'][$what] == 1)
						{
								return true;
						}
				}
				return false;
		}
		
}

?>
