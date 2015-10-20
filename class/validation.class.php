<?php
class validation
{
		
		function isRequired($data)
		{
				if (empty($data))
				{
						return false;
				}
				else
				{
				return true;
				}
				
		}
		
		function isNumeric($data)
		{
				if (is_numeric($data))
				{
						return true;
				}
				else
				{
				return false;
				}
		}
		
		function isAlpha($data)
		{
				return ctype_alpha($data);
		}
		
		function isAlphanumeric($data)
		{
				return ctype_alnum($data); 
		}
		
		function isEmail($data)
		{
				
		}
		
		
		function isMinSize($data, $min)
		{
				if (strlen($data) >= $min)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function isMaxSize($data, $max)
		{
				if (strlen($data) <= $min)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function isMin($data, $value)
		{
				if ($data >= $value)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function isMax($data, $value)
		{
				if ($data <= $value)
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function isUrl($data)
		{
				// todo
				return false;
		}
		
		/**
		 check url form, and try to connect to server to validate url
		*/
		function isUrlAndConnect($data)
		{
				// todo
				return false;
		}
}

?>
