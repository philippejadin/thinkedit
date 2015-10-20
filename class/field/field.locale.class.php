<?php

require_once 'field.base.class.php'; 

class field_locale extends field
{

	function isPrimary()
	{
		return true;
	}
	
	function get()
	{
			if (isset ($this->data))
			{
					return $this->data;
			}
			else
			{
					global $thinkedit;
					$context = $thinkedit->getContext();
					return  $context->getLocale();
			}
	}
	
	function getRaw()
	{
			return $this->get();
	}
	
	
	function validate()
	{
			
	}

}
?>
