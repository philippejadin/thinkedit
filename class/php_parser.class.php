<?php
/**
Converts an array to php file
and a php file to an array

This is tricky, but fast !

*/

class php_parser
{
		
		
		/*
		Quick and simple way to convert a php array to php syntax
		*/
		function toPhp($array, $variable_name = 'data')
		{
				/*
				ob_start();
				print_r($array);
				$content = ob_get_contents();
				ob_end_clean();
				*/
				
				// var_export is only PHP 4 >= 4.2.0, PHP 5 (is it a problem ?)
				
				$content = var_export($array, true);
				$result = '$' . $variable_name . ' = ' . $content;
				return $result;
		}
		
		/*
		Home made conversion of php array to a human readable (and writable) php representation
		
		*/
		function toPhpHumanFriendly($data, $variable_name = 'data', $path = false)
		{
				if(!$path)
				{
						$this->result = '';
				}
				
				if (is_array($data))
				{
						foreach ($data as $key=>$value)
						{
								$path[] = $key;
								//print_r ($path);
								if (is_array($value))
								{
										
										$this->toPhpHumanFriendly($value, $variable_name, $path);
								}
								else
								{
										$this->result .=  '$' . $variable_name;
										foreach ($path as $item)
										{
												$this->result .= "['" . $item . "']";
										}
										
										$this->result .= '=' .  "'" . addslashes($value) . "'" . ';' . "\n";
										
								}
								
								array_pop($path);
						}
						return $this->result;
						
				}
				else
				{
						//$path = array_pop ($path);
						return $this->result;
				}
				return $this->result;
		}
		
		
		/*
		function toArray($php)
		{
		}
		*/
		
		
		
		function load($filename, $variable_name = 'data')
		{
				include $filename;
				if (isset($$variable_name))
				{
						return $$variable_name;
				}
				else
				{
						return false;
				}
		}
		
		function save($filename, $data, $variable_name = 'data')
		{
				$out = '';
				$out .= '<' . '?php' . "\n";
				$out .= $this->toPhpHumanFriendly($data, $variable_name);
				$out .= "\n" . '?' . '>' . "\n";
				
				/*
				if (!is_writable($filename))
				{
						trigger_error("$filename is not writable, please change permissions on this file, else I cannot continue", E_USER_WARNING);
						return false;
				}
				*/
				
				$handler = @fopen($filename, 'w+');
				
				if (!$handler)
				{
						trigger_error("$filename cannot be written, please change permissions on this file or on the config folder, else I cannot continue", E_USER_WARNING);
						return false;
				}
				
				fwrite($handler, $out);
				fclose($handler);
				
				return true;
				
				
				
		}
		
		
}

?>
