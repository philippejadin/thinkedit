<?php
/*
Error handling class

see this : http://be.php.net/debug_backtrace
and this : http://be.php.net/register_shutdown_function

*/

class error
{
	/**
	will stop current script and display the error message imediately
	*/
	function error($message)
	{
	}
	
	/**
	Will issue a warning
	*/
	function warning($message)
	{
	}
	
	/**
	Will notice of something
	*/
	function notice($message)
	{
	}
	
	/**
	Used for logging purpose only
	This is not an error
	*/
	function info($message)
	{
	}
	
}


?>
