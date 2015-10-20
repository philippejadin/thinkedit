<?php

/*

Publish field : this is a simple radio button menu with two options : 

o published
o unpublished

*/

require_once 'field.base.class.php'; 

class field_publish extends field
{
function renderUI($prefix = false)
		{
				$out='';
				
				
				if ($this->get() == 1)
				{
						$out .= '<label><input type="radio" name="' . $prefix . $this->getName() . '" value="1" checked="checked">' . translate('published') . '</label><br/>';
						$out .= '<label><input type="radio" name="' . $prefix . $this->getName() . '" value="0">' . translate('not_published') . '</label><br/>';
				}
				else
				{
						$out .= '<label><input type="radio" name="' . $prefix . $this->getName() . '" value="1" >' . translate('published') . '</label><br/>';
						$out .= '<label><input type="radio" name="' . $prefix . $this->getName() . '" value="0" checked="checked">' . translate('not_published') . '</label><br/>';
				}
				return $out;
				
		}

}
?>
