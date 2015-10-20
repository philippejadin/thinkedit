<?php


// Remote include vulnerability found by "r0ut3r"
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
{
	die('you cannot view this file directly in a browser');
}


// include header
include(ROOT . '/design/'. $design .'/header.template.php');

// include template
include($template_file);

// include footer
include(ROOT . '/design/'. $design .'/footer.template.php');

?>
