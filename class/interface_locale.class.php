<?php
/*
Temp class for locale support in interface
*/
class interface_locale
{
		function interface_locale($locale_file = false)
		{
				if (!$locale_file)
				{
						global $thinkedit;
						$user = $thinkedit->getUser();
						
						
						$locale_file = ROOT . '/edit/ressource/locale/' . $user->getLocale() . '.php';
				}
				
				if (!file_exists($locale_file))
				{
						trigger_error('interface_locale::interface_locale() : locale file not found!');
				}
				
				$this->locale_file = $locale_file;
				
				require_once 'php_parser.class.php';
				$this->parser = new php_parser();
				
				$this->data = $this->parser->load($locale_file);
				
		}
		
		function translate($id)
		{
				if (isset($this->data[$id]))
				{
					//	echo "we have $id";
						
						// if we have it : 
						return $this->data[$id];
				}
				else
				{
						$this->data[$id] = '!' . $id . '!';
						$this->parser->save($this->locale_file, $this->data);
						return $id;
				}
				
		}
		
}


?>
