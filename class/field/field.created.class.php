<?php

require_once 'field.base.class.php'; 

/*
Mysql <> php date conversions : 

see http://fr.php.net/manual/en/function.date.php#52522
and http://fr.php.net/manual/en/function.strtotime.php#60585 for the reverse
*/


class field_created extends field
{
		
		
		function get()
		{
				
				if (isset($this->data) && $this->data <> '')
				{
						return $this->data;
				}
				// see http://fr.php.net/manual/en/function.date.php#52522
				
				// and http://fr.php.net/manual/en/function.strtotime.php#60585 for the reverse
				else
				{
						return date('Y-m-d H:i:s');
				}
		}
		
		
		function getFriendly($options = false)
		{
				return $this->data;
		}
		
		/*
		function set($data)
		{
				$this->data = $data;
		}
		*/
		
		/*
		function renderUI($prefix = false)
		{
				
				$out = '';
				$out .= sprintf('<input type="text" value="%s" name="%s", size="32">', $this->getHtmlSafe(), $prefix . $this->getName());
				return $out;
		}
		*/
		
}

?>
