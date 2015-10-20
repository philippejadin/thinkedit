<?php
require_once 'field.base.class.php'; 

class field_text extends field
{
	
	function renderUI($prefix = false)
	{
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 60) + 8;
		if ($rows > 30) $rows = 30;
		$out = '';
		$out .= sprintf('<textarea name="%s" cols="40" rows="%s">%s</textarea>', $prefix . $this->getName(), $rows, $this->getHtmlSafe());
		return $out;
	}
	
}
?>
