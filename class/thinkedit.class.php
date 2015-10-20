<?php
/**
* Thinkedit Base class
* This is the thinkedit most important class. It is a factory class for important thinkedit objects
* 
* See the api for the respective objects to understand how they work
*/
class thinkedit
{
	
	var $timer;
	var $db;
	var $config;
	var $configuration;
	
	
	/**
	* Passing it a config folder, and it will use it for the whole application. Else it will use the default /config folder
	**/
	function thinkedit($config_folder = './config/')
	{
		global $thinkedit;
		
		if (isset($thinkedit))
		{
			trigger_error('thinkedit::thinkedit() : Thinkedit has already been instantiated, I found an existing $thinkedit global var', E_USER_ERROR);
		}
		
		// responsabilities : find and load a correct config folder.
		
		// 1. find a config folder
		// it is not required to check if the config folder is within doc root or not, because config files are stored in php files not readable from ext
		if (is_dir(dirname(__FILE__) . '/config/'))
		{
			// todo security : check if the config folder is really out of the server doc root
			//trigger_error('config folder is still within the doc root, move it outside docroot', E_USER_WARNING);
			$this->config_folder = realpath(dirname(__FILE__) . '/config/');
		}
		elseif (is_dir(dirname(__FILE__) . '/../config/'))
		{
			$this->config_folder = realpath(dirname(__FILE__) . '/../config/');
		}
		elseif (is_dir(dirname(__FILE__) . '/../../config/'))
		{
			$this->config_folder = realpath(dirname(__FILE__) . '/../../config/');
		}
		
		else
		{
			die('thinkedit::thinkedit() config folder not found. This is a fatal error, aborting');
		}
		
		// 2. parse this folder
		$config = $this->parsePhpFolder($this->config_folder);
		
		
		// 3. Parse plugin folders for additional config items :
		$plugin_config = $this->parsePhpPluginFolder();
		
		if (is_array($plugin_config))
		{
			array_merge_2($config, $plugin_config);
		}
		
		// 4. init this->config[] array and $this->configuration object
		$this->config = $config;
		
		/*
		echo '<pre>';
		print_r($config);
		*/
		
	}
	
	/************************ Initialisation methods *************************/
	
	function parseXmlFolder($folder)
	{
		//die($folder);
		$complete_config = array();
		
		require_once 'xml_parser.class.php';
		$parser = new xml_parser();
		
		// test if folder is found
		if (file_exists($folder))
		{
			$ressource = opendir($folder);
			
			// find files in this folder
			while (($file = readdir($ressource)) !== false) 
			{
				// debug($file, 'xml_parser::parse_folder files');
				if (is_file($folder . '/' . $file))
				{
					$path_parts = pathinfo($file);
					
					// if it's an yaml file, parse it and store thge results in an array
					if ($path_parts['extension'] == 'xml')
					{
						$we_have_config_files = true;
						$config = $parser->load($folder. '/' . $file);
						if (!$config)
						{
							trigger_error("we have a parsing error with $file");
						}
						if (is_array($config))
						{
							$complete_config = array_merge($complete_config, $config);
						}
					}
				}
			}
			
			if (isset($we_have_config_files))
			{
				//echo '<pre>';
				//print_r($complete_config);
				return $complete_config['config'];
			}
			else
			{
				trigger_error("thinkedit::parseXmlFolder() no config files found - aborting");
				die();
				return false;
			}
		}
		else
		{
			trigger_error("thinkedit::parseXmlFolder() : $folder is not found - aborting");
			die();
			return false;
		}
		
	}
	
	
	function parsePhpFolder($folder)
	{
		//die($folder);
		$complete_config = array();
		require_once 'php_parser.class.php';
		$parser = new php_parser();
		
		// test if folder is found
		if (file_exists($folder))
		{
			$ressource = opendir($folder);
			
			// find files in this folder
			while (($file = readdir($ressource)) !== false) 
			{
				// debug($file, 'xml_parser::parse_folder files');
				if (is_file($folder . '/' . $file))
				{
					$path_parts = pathinfo($file);
					
					// if it's an yaml file, parse it and store thge results in an array
					if ($path_parts['extension'] == 'php')
					{
						$we_have_config_files = true;
						$config = $parser->load($folder. '/' . $file);
						if (!$config)
						{
							trigger_error("we have a parsing error with $file");
						}
						if (is_array($config))
						{
							array_merge_2($complete_config, $config);
						}
					}
				}
			}
			
			if (isset($we_have_config_files))
			{
				//print_r($complete_config);
				return $complete_config;
			}
			else
			{
				trigger_error("thinkedit::parsePhpFolder() no config files found - aborting");
				die();
				return false;
			}
		}
		else
		{
			trigger_error("thinkedit::parsePhpFolder() : config folder $folder is not found - aborting");
			die();
			return false;
		}
		
	}
	
	
	/**
	Will parse all subfoders of the plugin folder to find config.php config files
	They are merged with the main config file 
	*/
	function parsePhpPluginFolder($folder = "./plugin/")
	{
		
		$complete_config = array();
		require_once 'php_parser.class.php';
		$parser = new php_parser();
		
		// test if folder is found
		if (file_exists($folder))
		{
			$ressource = opendir($folder);
			
			// find files in this folder
			while (($file = readdir($ressource)) !== false) 
			{
				
				$plugin_folder = $folder . '/' . $file;
				
				if (is_dir($plugin_folder))
				{
					$plugin_config_file = $plugin_folder . '/config.php';
					if (file_exists($plugin_config_file))
					{
						$config = $parser->load($plugin_config_file);
						if ($config)
						{
							array_merge_2($complete_config, $config);
						}
					}
					if (!isset($config))
					{
						//trigger_error("we have a parsing error with $file");
					}
				}
			}
		}
		return $complete_config;
	}
	
	
	
	/************************* DB factory methods **************************/
	
	function connectToDb($id)
	{
		if (isset($this->config['site']['database'][$id]))
		{		
			require_once 'db.class.php';
			$login = $this->config['site']['database'][$id]['login'];
			$password = $this->config['site']['database'][$id]['password'];
			$host = $this->config['site']['database'][$id]['host'];
			$database = $this->config['site']['database'][$id]['database'];
			$this->db_instance[$id] = new db($host, $login, $password, $database);
			return true;
		}
		else
		{
			trigger_error("no connect info found in config for db called '$id'");
			return false;
		}
		
		
	}
	
	
	/*********************** Simplified factory system **************************/
	// work in progress, maybe not a good idea. The idea is to minify the $thinkedit api
	/*
	Returns an $object_type. It is instanciated only once  
	*/
	function getObject($object_type)
	{
		
	}
	
	/*
	
	function newObject($object_type)
	{
		if ($object_type=='url')
		{
		}
	}
	*/
	
	/************************* Factory methods for single instance objects **************************/
	
	
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
		$file = ROOT . '/class/field/field.' . $type . '.class.php';
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
	
	
	
	/************************* TOOLS **************************/
	
	/**
	* Should be moved to user prefenrences and context for the site (editing) interface locale
	*
	*
	**/
	function getInterfaceLocale()
	{
		//trigger_error('deprecated');
		return 'fr';
	}
	
	
	
	
	function getHelp()
	{
		$help = $this->config['site']['help'][$this->getInterfaceLocale()];
		
		if (isset($help))
		{
			return $help;
		}
		else
		{
			trigger_error(translate('no_help_in_config'));
			return false;
		}
	}
	
	
	function getTitle()
	{
		$title = $this->config['site']['title'][$this->get_interface_locale()];
		if (isset($title))
		{
			return $title;
		}
		else
		{
			trigger_error(translate('no_title_in_config'));
			return false;
		}
	}
	
	
	function getRelationTable()
	{
		trigger_error('deprecated');
		return 'relation';
	}
	
	
	function getTable($table)
	{
		trigger_error('deprecated');
		// todo take it from config, currently returning what is asked raw
		return $table;
	}
	
	
	// returns root node id, could be made configurable to have more than one site in the same DB, with different roots
	function getRootNodeId()
	{
		trigger_error('deprecated');
		return 1;
	}
	
	/**
	* Returns the run mode of thinkedit. Usefull for developpers (for instance, to avoid showing debug info on a production site ;-) )
	* 
	* @return string 'development' or 'production'
	* 
	*/
	function getRunMode()
	{
		if (isset($this->config['site']['run_mode']))
		{
			if ($this->config['site']['run_mode'] == 'development')
			{
				return 'development';
			}
			if ($this->config['site']['run_mode'] == 'production')
			{
				return 'production';
			}
		}
		return 'production';
		
	}
	
	/**
	* Return true if we are in a live, production system.
	* This is defined in config file.
	* 
	* If true, we should not report any error to the user!
	* 
	* @param boolean true if we are in production
	*/
	function isInProduction()
	{
		if ($this->getRunMode() == 'production')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
	/**
	* Return true if Thinkedit is installed
	* Installed currently means : there is a db.php file in /config
	* 
	*/
	function isInstalled()
	{
		if (file_exists(ROOT . '/config/db.php'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
	
}



/**
* Helper function used by this class only
* 
* taken from : http://php.belnet.be/manual/en/function.array-merge-recursive.php#38387
* todo : optimize !!!!
*/
function array_merge_2(&$array, &$array_i) 
{
	// For each element of the array (key => value):
	foreach ($array_i as $k => $v) {
		// If the value itself is an array, the process repeats recursively:
		if (is_array($v)) {
			if (!isset($array[$k])) {
				$array[$k] = array();
			}
			array_merge_2($array[$k], $v);
			
			// Else, the value is assigned to the current element of the resulting array:
		} else {
			if (isset($array[$k]) && is_array($array[$k])) {
				$array[$k][0] = $v;
			} else {
				if (isset($array) && !is_array($array)) {
					$temp = $array;
					$array = array();
					$array[0] = $temp;
				}
				$array[$k] = $v;
			}
		}
	}
}


?>