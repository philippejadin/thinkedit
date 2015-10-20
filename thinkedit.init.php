<?php
/*
Thinkedit INITIALIZATION file

This file must be included on every page.

It will only define one global var called $thinkedit. 
This is the single starting point of your application, because with this $thinkedit-> object, 
you have everything you need.
*/


/*
Thinkedit, Web based Content and Data Management System
Copyright (C) 2000-2006  Philippe Jadin 
philippe@123piano.com (preferred)
philippe.jadin@gmail.com
philippe@thinkedit.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Remote include vulnerability found by "r0ut3r"
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
{
	die('you cannot view this file directly in a browser');
}


// needed for ms iexplorer

if (!headers_sent())
{
	header ("Content-Type: text/html; charset=utf-8");
}






/******************* Disable magic quotes ***************/
// from http://fr.php.net/manual/en/security.magicquotes.disabling.php
/*
if (get_magic_quotes_gpc()) 
{
	//echo '<pre>';
	//echo '<h1>Before conversion</h1>';
	//print_r($_REQUEST);
	//echo '</pre>';
	
	function stripslashes_deep($value)
	{
		if (is_array($value))
		{
			$value = array_map('stripslashes_deep', $value);
		}
		else
		{
			$value = stripslashes($value);
		}
		
		return $value;
	}
	
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	// todo check if $_SESSION must be changed as well. Php doc is unclear on this subject
	
	
	//echo '<pre>';
	//echo '<h1>After conversion</h1>';
	//print_r($_REQUEST);
	//echo '</pre>';
	
}
*/

// now from : http://talks.php.net/show/php-best-practices/26  :

if (get_magic_quotes_gpc()) 
{
	$in = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($k,$v) = each($in)) 
	{
		foreach ($v as $key => $val) 
		{
			if (!is_array($val))
			{
				$in[$k][$key] = stripslashes($val);
				continue;
			}
			$in[] =& $in[$k][$key];
		}
	}
	unset($in);
}


/******************* unregister globals ***********/
// Turn register globals off 
// taken from wordpress
function unregisterGlobals() 
{
	if ( !ini_get('register_globals') )
	{
		return;
	}
	
	if ( isset($_REQUEST['GLOBALS']) )
	{
		die('GLOBALS overwrite attempt detected');
	}
	
	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
	
	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
	foreach ( $input as $k => $v ) 
	{
		if ( !in_array($k, $noUnset) && isset($GLOBALS[$k]) )
		{
			unset($GLOBALS[$k]);
		}
	}
}

unregisterGlobals();  


/******************* Profiling ***************/
//error_reporting(E_ALL);
//ini_set('display_errors', true);
if (function_exists('xdebug_start_profiling'))
{
	xdebug_start_profiling();
}


/******************* Define root constant ***************/
define ('ROOT', dirname(__FILE__));


/*********************** Required includes ******************/
require_once dirname(__FILE__) . '/class/thinkedit.class.php';



/*********************** Thinkedit object ******************/
$thinkedit = new thinkedit();



/*********************** Configuration object ******************/
$thinkedit->configuration = $thinkedit->newConfig();


require_once dirname(__FILE__) . '/lib/thinkedit/tools.inc.php';

/*********************** Timer ******************/

$thinkedit->timer = $thinkedit->getTimer();
$thinkedit->timer->marker('start init');





/*********************** ROOT, PATH, URL constants ******************/
// define ('ROOT', $thinkedit->configuration->getRootPath(dirname(__FILE__)));
define ('ROOT_PATH', $thinkedit->configuration->getRootPath(dirname(__FILE__)));
define ('ROOT_URL', $thinkedit->configuration->getRootUrl());
define ('SITE_URL', $thinkedit->configuration->getSiteUrl());
define ('TMP_PATH', $thinkedit->configuration->getTmpPath());


/*********************** Thinkedit USER ******************/
$thinkedit->user = $thinkedit->getUser();


/*********************** Thinkedit DB ******************/
$thinkedit->db = $thinkedit->getDb();


/*********************** Cache *************************/
$thinkedit->outputcache = $thinkedit->getOutputCache();
$thinkedit->cache = $thinkedit->getCache();
$thinkedit->functioncache = $thinkedit->getFunctionCache();


/*********************** Context *************************/
$thinkedit->context = $thinkedit->getContext();


/*********************** Error Reporting ******************/
// turn on error reporting
if ($thinkedit->isInProduction())
{
	error_reporting(0);
	ini_set('display_errors', false);
}
else
{
	error_reporting(E_ALL);
	ini_set('display_errors', true);
}

$thinkedit->timer->marker('end init');

$thinkedit->context->set('public');

/*********************** Session ******************/
$session = new session();


/*********************** Locales ******************/
// todo : setup user locale somewhere
require_once ROOT . '/class/interface_locale.class.php';

$interface_locale = new interface_locale();


/******************* Template helpers (aka "tags") *******************/
require_once ROOT . '/lib/thinkedit/template.lib.php';


/******************* What to do on shutdown *************************/
register_shutdown_function('te_shutdown');

function te_shutdown()
{
	// echo te_admin_toolbox();
}

?>
