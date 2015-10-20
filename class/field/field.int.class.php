<?php

require_once 'field.base.class.php'; 

class field_int extends field
{

  function get()
  {
    return (int) $this->data;
    
  }
	/*
	function validate()
	{
			// return (strval(intval($value)) == $value);
	}
	*/

}
?>
