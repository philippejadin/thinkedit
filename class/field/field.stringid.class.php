<?php

require_once 'field.base.class.php'; 

class field_stringid extends field
{
		
		/*
		
		function get()
		{
				return (int) $this->data;
				
		}
		*/
		
		
		function isPrimary()
		{
				return true;
		}
		
		
}
?>
