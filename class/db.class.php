<?php
/**
* A lightweight MySql access class.
* An adapter could be written for pear or wathever.


What a database user needs : 

select and query

select() returns results as an array of associative arrays
query() returns the number of rows affected

see also http://www.justinvincent.com/home/articles.php?articleId=1&page=4



*
*/
class db
{
		function db ($host, $login, $password, $database)
		{
				if (empty ($host)) trigger_error('db::db() host definition empty', E_USER_WARNING);
				if (empty ($login)) trigger_error('db::db() login argument is empty', E_USER_WARNING);
				if (empty ($password)) trigger_error('db::db() no password given', E_USER_WARNING);
				if (empty ($database)) trigger_error('db::db() no database name given, cannot select db', E_USER_WARNING);
				$this->host=$host;
				$this->login=$login;
				$this->password=$password;
				$this->database=$database;
				//$this->connect();
				//global $total_queries;
				//$total_queries = 0;
		}
		
		/**
		* Establishes connection to MySQL and selects a database
		* @return true if it works, false if not, trigger warning on fail
		*/
		function connect()
		{
				
				if (isset($this->is_connected))
				{
						return true;
				}
				else
				{
						// Make connection to MySQL server
						if (!$this->connection = @mysql_pconnect($this->host, $this->login, $this->password))
						{
								trigger_error('Could not connect to server', E_USER_WARNING);
								$this->connectError=true;
								// Select database
						}
						else if ( !@mysql_select_db($this->database,$this->connection) )
						{
								trigger_error('Could not select database, maybe this database doesn\'t exist ?', E_USER_WARNING);
								$this->connectError=true;
								return false;
						}
						$this->is_connected = true;
						return true;
				}
				
		}
		
		
		/*
		Return true if db connected successfully or false if not
		usefull for intstaller
		
		Doens't trigger error if fail
		*/
		function canConnect()
		{
				if (!$this->connection = mysql_pconnect($this->host, $this->login, $this->password))
				{
						return false;
				}
				elseif ( !mysql_select_db($this->database,$this->connection) )
				{
						return false;
				}
				else
				{
						return true;
				}
				
		}
		
		
		/**
		* Checks for MySQL errors
		* @return boolean
		* @access public
		*/
		function isError ()
		{
				if (isset($this->connectError))
				{
						return true;
				}
				if (isset($this->connection))
				{
						$error=mysql_error($this->connection);
						$error_number=mysql_errno($this->connection);
						
						if ( empty ($error) )
						{
								return false;
						}
						else
						{
								global $thinkedit;
								if ($thinkedit->isInProduction())
								{
										trigger_error ($error_number . ' : (sql not shown)' , E_USER_WARNING);
								}
								else
								{
										trigger_error ($error_number . ' : ' . $error, E_USER_WARNING);		
										
								}
								return true;
						}
				}
		}
		
		/**
		* Returns true if succesfful or false if sql failure
		* @param $sql string the database query to run
		* @return MySQLResult
		* @access public
		*/
		function query($sql)
		{
				//echo $sql . '<br/>';
				$this->connect();
				global $total_queries;
				$total_queries++;
				if (strstr($sql, 'select'))
				{
						trigger_error('don\'t use query() when selecting, you won\'t have any results back');
				}
				
				global $db_debug;
				$db_debug[] = $sql;
				
				// one line debugging tool :-)
				debug($sql, 'db:query()');
				
				$this->sql = $sql;
				$result = mysql_query($sql, $this->connection);
				
				if (!$result)
				{
						trigger_error ('Query failed: '.mysql_error($this->connection).     ' <br>SQL: '.$sql, E_USER_WARNING);
						return false;
				}
				else
				{
						return true;
				}
				
				
				// if not rows affected, it wil return 0, and the caller will think it failed, but an update can succeed and change 0 rows
				//return mysql_affected_rows($this->connection);
				
				/*
				if (mysql_affected_rows($this->connection) == -1)
				{
						return false;
				}
				else
				{
						return mysql_affected_rows($this->connection);
				}
				*/	
		}
		
		function select($sql)
		{
				global $db_cache;
				
				$hash =  sha1($sql);
				
				if (isset($db_cache) && isset($db_cache[$hash]))
				{
						// one line debugging tool :-)
						debug($sql . '[cached]', 'db:select()');
						return ($db_cache[$hash]);
				}
				else
				{
						global $db_debug;
						$db_debug[] = $sql;
						
						
						// ultra lazy connect : it means that we can still use a website if a query is in the cache and if the db is down
						$this->connect();
						
						// echo $sql . '<br/>';
						
						
						
						global $total_queries;
						$total_queries++;	
						// one line debugging tool :-)
						// debug($sql . '[select]', 'db:select()');
						//echo ($sql. "[select]" . "db:select()");
						
						$this->sql = $sql;
						if (!$this->query = mysql_query($sql,$this->connection))
						{
								trigger_error ('Query failed: '.mysql_error($this->connection).     ' <br>SQL: '.$sql, E_USER_WARNING);
								return false;
						}
						
						
						$result = '';
						
						while ($row = mysql_fetch_assoc($this->query))
						{
								$result[] = $row;
						}
						
						$db_cache[$hash] = $result;
						
						
						if (count($result > 0))
						{
								return $result;
						}
						else
						{
								//trigger_error($sql . ' nothing found');
								return false;
						}
						
				}
		}
		
		
		function size()
		{
				$this->connect();
				return mysql_num_rows($this->query);
		}
		
		function affectedRows()
		{
				$this->connect();
				return mysql_affected_rows($this->query);
		}
		
		function insertID()
		{
				$this->connect();
				return mysql_insert_id($this->connection);
		}
		
		
		function escape($string)
		{
				$this->connect();
				if (get_magic_quotes_gpc())
				{
						// trigger_error('get_magic_quotes_gpc() php setting is ON, I don\'t like this');
						$string = mysql_real_escape_string($string);
						return $string;
				}
				else
				{
						$string = mysql_real_escape_string($string);
						return $string;
						
				}
		}
		
		
		function quote($string)
		{
				trigger_error('deprecated', E_USER_WARNING);
				if (get_magic_quotes_gpc())
				{
						trigger_error('gpc on');
						return $string;
				}
				else
				{
						// trigger_error('gpc off');
						if (!is_numeric($string)) 
						{
								$string = "'" . mysql_real_escape_string($string) . "'";
						}
						return $string;
						
				}
		}
		
		/*
		Returns true if table $table is found in the DB
		*/
		function hasTable($table_id)
		{
				$this->connect();
				$sql = 'show tables';
				// the following code is not very nice, but it's the only case
				// we need to return rows as simple indexd array 
				// and I'm not gonna change my db class just for this to work :-)
				$result = mysql_query($sql);
				
				if (!$result) 
				{
						trigger_error ("DB Error, could not list tables");
						trigger_error ('MySQL Error: ' . mysql_error());
						exit;
				}
				
				while ($row = mysql_fetch_row($result)) 
				{
						if ($row[0] == $table_id)
						{
								return true;
						}
				}
				return false;
				
				
		}
		
		
		
		/*
		Will create a table in this DB. This table will have an autoincrement primary kay called ID
		This is recommended behavior in most cases
		*/
		function createTable($table)
		{
				$this->connect();
				/* 
				SQL is :
				CREATE TABLE `test` (
				`id` INT NOT NULL AUTO_INCREMENT ,
				PRIMARY KEY ( `id` )
				)
				*/
				
				$sql = "create table " . $table . ' (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) )';
				$results = $this->query($sql);
				if ($results)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function getTotalQueries()
		{
				global $total_queries;
				return $total_queries;
		}
		
		
		function clearCache()
		{
				debug('cache cleared', 'DB');
				global $db_cache;
				//$db_ca
				// global can only be unsetted this way from inside functions:
				unset($GLOBALS['db_cache']);
				//unset($db_cache);
		}
		
		
		function debug()
		{
			$out = '<h1>DB debug</h1>';
			global $db_debug;
			if (isset($db_debug))
			{
				if (is_array($db_debug))
				{
					
					foreach ($db_debug as $debug)
					{
						$out.='<li>'.$debug.'</li>';
					}
				}
			}
			return $out;
					
		}
		
}

?>