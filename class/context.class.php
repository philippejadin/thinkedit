<?php

/*
see 
http://64.233.179.104/search?q=cache:WlUMZ5mKe9cJ:koivi.com/apache-iis-php-server-array.php+&hl=fr&gl=be&ct=clnk&cd=1&client=firefox-a
for more info
*/

class context
{
		
		function getServerType()
		{
				//return strtolower($_SERVER['SERVER_SOFTWARE']);
				//return strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache');
				if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache') == 0)
				{
						return 'apache';
				}
				else
				{
						return 'iis';
				}
				return $_SERVER['SERVER_SOFTWARE'];
				return 'apache';
				return 'iis';
		}
		
		
		function fixServerVars()
		{
				// see http://fr.php.net/reserved.variables
				// need testing
				if(!isset($_SERVER["DOCUMENT_ROOT"]))
				{
						$_SERVER["DOCUMENT_ROOT"]=substr($_SERVER['SCRIPT_FILENAME'] , 0 , -strlen($_SERVER['PHP_SELF'])+1 );
				}
		}
		
		
		function enablePreview()
		{
				// todo security add user is logged check
				global $thinkedit;
				$url = $thinkedit->newUrl();
				if ($url->get('preview') || $this->get() == 'interface')
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function set($context)
		{
				$this->context = $context;
		}
		
		function get()
		{
				if (isset($this->context))
				{
						return $this->context;
				}
				else
				{
						return false;
				}
		}
		
		function getLocale()
		{
				if (isset ($this->locale))
				{
						return $this->locale;
				}
				else
				{
						global $thinkedit;
						$url = $thinkedit->newUrl();
						// todo: use the user class and browser to know which locale is default
						if ($url->get('locale')) // todo security : check agains known list of locales
						{
								return $url->get('locale');
						}
						else
						{
								global $thinkedit;
								return $thinkedit->configuration->getMainLocale();
								//return 'fr';
						}
				}
		}
		
		function setLocale($locale)
		{
				$this->locale = $locale;
		}
		
		
}
?>
