<?php
/**
Converts an array to php file
and a php file to an array

This is tricky, but fast !

*/

class array2php
{
		function toPhp($array, $variable_name)
		{
				ob_start();
				print_r($array);
				$content = ob_get_contents();
				ob_end_clean();
				
				$result = $name . ' = ' . $content;
				return $result;
		}
		
		
		function toArray($php)
		{
		}
		
		function load($filename, $variable)
		{
				include $filename;
				return $$variable;
		}
		
		function save($filename, $data, $variable_name)
		{
		}
		
		
}

?>
