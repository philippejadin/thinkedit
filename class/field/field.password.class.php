<?php
require_once 'field.base.class.php'; 


class field_password extends field
{
		
		function renderUI($prefix = false)
		{
				// todo : don't show password at all, and show an empty input box. If the box is filled, the password is changed. Provide twice the same input box for verification
				
				$out = '';
				$out .= sprintf('<input type="password" value="%s" name="%s">', $this->getHtmlSafe(), $prefix . $this->getName());
				return $out;
		}
		
		
		function getNice()
		{
				return '*********';
		}
		
		/*
		function isPrimary()
		{
				return true;
		}
		*/
		
		
}
?>
