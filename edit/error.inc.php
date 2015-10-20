<?php

function error($error_message)
{
	
	include('header.template.php');
	include('error.template.php');
  include('footer.template.php');
	die();
}

?>