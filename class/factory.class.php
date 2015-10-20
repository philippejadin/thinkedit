<?php



/**
* All thinkedit related factory methods are being moved here
* Instead of $thinkedit->newRecord() we'll do $thinkedit->factory->newRecord();
*
*/
class factory
{
	/**
	* Returns a db instance ot be used anywhere. Usually, the main db is used, but multi db can be configured in config file
	*
	*
	**/
	function getDb($id='main')
	{
		if (isset($this->db_instance[$id]))
		{
			return $this->db_instance[$id];
		}
		else
		{
			if ($this->connectToDb($id))
			{
				return $this->db_instance[$id];
			}
			else
			{
				trigger_error('thinkedit::getDb() Cannot instantiate DB');
				return false;
			}
		}
	}
	
	/**
	*  Returns a $context object see context class for it's api
	*/
	function getContext()
	{
		require_once ROOT . '/class/context.class.php';
		return new context();
	}
	
	
	/**
	* Returns the current user object
	*/
	function getUser()
	{
		require_once ROOT . '/class/user.class.php';
		return new user();
	}
	
	function getClipboard()
	{
		require_once ROOT . '/class/clipboard.class.php';
		return new clipboard();
	}
	
	
	/**
	* Returns a cache object used for output caching
	* This initialises a pear cache lite object
	* 
	* 
	* 
	* 
	*/
	function getOutputCache()
	{
		// I hate pear global include system, so I have this "solution" :-/
		require_once ROOT . '/lib/pear/cache/Lite/Output.php';
		$options = array(
		'cacheDir' => TMP_PATH,
		'lifeTime' => 86400,
		'pearErrorMode' => CACHE_LITE_ERROR_DIE,
		'automaticSerialization' => true
		);
		return new Cache_Lite_Output($options);
	}
	
	
	/**
	* Returns a function cache object
	* 
	* 
	* 
	* 
	* 
	*/
	function getFunctionCache()
	{
		// I hate pear global include system, so I have this "solution" :-/
		require_once ROOT . '/lib/pear/cache/Lite/Function.php';
		$options = array(
		'cacheDir' => TMP_PATH,
		'lifeTime' => 86400,
		'pearErrorMode' => CACHE_LITE_ERROR_DIE,
		'automaticSerialization' => true
		);
		return new Cache_Lite_Function($options);
	}
	
	/**
	* Returns a pear cache lite object
	* 
	* 
	* 
	* 
	* 
	*/
	function getCache()
	{
		// I hate pear global include system, so I have this "solution" :-/
		require_once ROOT . '/lib/pear/cache/Lite.php';
		$options = array(
		'cacheDir' => TMP_PATH,
		'lifeTime' => 86400,
		'pearErrorMode' => CACHE_LITE_ERROR_DIE,
		'automaticSerialization' => true
		);
		return new Cache_Lite($options);
	}
	
	
	function getCacheFile($master_file)
	{
		// I hate pear global include system, so I have this "solution" :-/
		require_once ROOT . '/lib/pear/cache/Lite/File.php';
		$options = array(
		'cacheDir' => TMP_PATH,
		'lifeTime' => 86400,
		'pearErrorMode' => CACHE_LITE_ERROR_DIE,
		'automaticSerialization' => true,
		'masterFile' => $master_file
		);
		return new Cache_Lite_File($options);
	}
	
	/**
	* Returns the global timer object used for benchmarking
	* 
	* 
	* 
	* 
	* 
	*/
	function getTimer()
	{
		if (isset($this->timer))
		{
			return $this->timer;
		}
		else
		{
			require_once 'timer.class.php';
			$this->timer = new timer();
			//$this->timer->start();
			return $this->timer;
		}
	}
	
	
	/************************* Factory methods **************************/
	
	/**
	* Based on uid, will instantiate an object
	* 
	* UID= unique ID
	* 
	* a UID is an array with at least : 
	* - class
	* - type
	* - id
	*/
	function newObject($uid, $data = false)
	{
		if (!isset($uid['class']))
		{
			trigger_error('thinkedit::newObject() $uid[\'class\'] not defined', E_USER_WARNING);
			return false;
		}
		
		if (!isset($uid['type']))
		{
			trigger_error('thinkedit::newObject() $uid[\'type\'] not defined', E_USER_WARNING);
			return false;
		}
		
		if ($uid['class'] == 'record')
		{
			if (isset($uid['id']))
			{
				return $this->newRecord($uid['type'], $uid['id'], $data);
			}
			else
			{
				return $this->newRecord($uid['type']);
			}
			
		}
		elseif ($uid['class'] == 'node')
		{
			if (isset($uid['id']))
			{
				return $this->newNode($uid['type'], $uid['id'], $data);
			}
			else
			{
				return $this->newNode($uid['type']);
			}
			
		}
		elseif ($uid['class'] == 'filesystem')
		{
			return $this->newFilesystem($uid['type'], $uid['id']);
		}
		
		else
		{
			trigger_error("thinkedit::newObject() class " . $uid['class'] . "not supported", E_USER_WARNING);
			return false;
		}
		
		return false;
	}
	
	
	
	/**
	* Given a table_name, instantiate a table
	**/
	function newTable($table_name)
	{
		// will include the right module class if needed, for example, specialized modules like ftp datasource
		// currently the base module is used
		if ($table_name<>'')
		{
			require_once('table.class.php');
			return new table($table_name);
		}
		else
		{
			trigger_error('thinkedit::newTable() $table_name empty');
		}
	}
	
	
	/**
	* Given a type and an id, instantiate a record
	* If no id given, instantiate a new empty record of type, using the right class for this record type
	* For instance if it is a multilingual table, it will return a multilingual record object (@todo)
	*
	**/
	function newRecord($table, $id=false, $data = false)
	{
		// will include the right module class if needed, for example, specialized modules like ftp datasource
		// currently the base module is used
		if ($table<>'')
		{
			// optimization : file is required on top of this class file
			// optimization removed, because no real speed impact found
			
			// find if the record has a locale field
			$multilingual = false;
			if (isset($this->config['content'][$table]['field']))
			{
				foreach ($this->config['content'][$table]['field'] as $field)
				{
					if ($field['type'] == 'locale')
					{
						$multilingual = true;
					}
				}
			}
			elseif (isset($this->config['table'][$table]['field']))
			{
				foreach ($this->config['table'][$table]['field'] as $field)
				{
					if ($field['type'] == 'locale')
					{
						$multilingual = true;
					}
				}
			}
			
			if ($multilingual)
			{
				require_once('record.multilingual.class.php');
				$record = new record_multilingual($table);
			}
			else
			{
				require_once('record.class.php');
				$record = new record($table);
			}
			
			
			if ($id)
			{
				$record->set('id', $id);
			}
			if ($data)
			{
				$record->loadByArray($data);
			}
			return $record;
		}
		else
		{
			trigger_error('thinkedit::newRecord() $table not defined');
		}
	}
	
	
	/**
	* Will return a new node object
	* 
	* @param string $table is the table id (not needed most of the time)
	* @param integer $id is the id needed
	* @param array $data may contain the data of the node, in order to preload it with data
	* 
	*/
	function newNode($table = "node", $id = false, $data = false)
	{
		// will include the right module class if needed, for example, specialized modules like ftp datasource
		// currently the base module is used
		if ($table<>'')
		{
			require_once('node.class.php');
			$node = new node($table);
			// experimental optimized node class support :
			/*
			require_once('node_cached.class.php');
			$node = new node_cached($table);
			*/
			if ($id)
			{
				$node->setId($id);
			}
			// if a data array is passed, we assign it to the node, and assume it is loaded. This is an optimization
			if ($data)
			{
				$node->loadByArray($data);
			}
			
			return $node;
		}
		else
		{
			trigger_error('thinkedit::newNode() $table not defined');
			return false;
		}
		return false;
	}
	
	
	
	
	/**
	* Given an id and a path, instantiate a filesystem
	* id and path can be ommited
	*
	**/
	function newFilesystem($id='main', $path=false)
	{
		// will include the right module class if needed, for example, specialized modules like ftp datasource
		// currently the base module is used
		require_once('filesystem.class.php');
		return new filesystem($id, $path);
	}
	
	
	
	/**
	* Returns a new config object
	* 
	* 
	* 
	*/
	function newConfig()
	{
		require_once('config.class.php');
		$config = new config();
		return $config;
	}
	
	
	/**
	* Returns a field object
	* 
	* @param string $table the table id
	* @param string $field the field id
	* @param mixed $data preloaded data if needed
	* 
	*/
	function newField($table, $field, $data = false)
	{
		if (isset($this->config['content'][$table]['field'][$field]['type']))
		{
			$type = $this->config['content'][$table]['field'][$field]['type'];
		}
		elseif (isset($this->config['table'][$table]['field'][$field]['type']))
		{
			$type = $this->config['table'][$table]['field'][$field]['type'];
		}
		else
		{
			trigger_error("thinkedit::newElement config error, $field type not found in config");
		}
		
		// todo : class path management
		$file = ROOT . '/class/field.' . $type . '.class.php';
		$class = 'field_' . $type;
		
		require_once($file);
		return new $class($table, $field, $data);
	}
	
	/**
	* Returns a new relation object
	* 
	* 
	* 
	*/
	function newRelation()
	{
		require_once ROOT . '/class/relation.class.php';
		return new relation();
	}
	
	/**
	* Returns a new session object
	* 
	* 
	* 
	*/
	function newSession()
	{
		require_once ROOT . '/class/session.class.php';
		return new session();
	}
	
	/**
	* Returns a new url object
	* 
	* 
	* 
	*/
	function newUrl()
	{
		require_once ROOT . '/class/url.class.php';
		return new url();
	}
	
}
?>
