<?php
/**
*Based on original work by (?) (tbd, check author)
*Modified by Philippe Jadin
*
*A simple xml parser class (xml -> php arrays) based on php's expat xml parser.
*
*Limited feature set
*(Un)suported intentionnaly :
*
*- no attributes
*- only one element of the same name at the same level
*- if more than one element, use "id" attribute to differentiate them
*
*Caching support, config files are stored as "filename.xml.cache".
*It's a serialized version of the array.
*
*/

/**
* Used internally only
*/
class xml_parser
{
		
		//var $config; // stores the config array 
		var $result; // will contain the resulting array
		var $depth = 0; // current depth in the xml file
		var $data;
		
		// those two elements are an array of elements depending of the current depth :
		// var id_found; // true if an id attribute is found in the current parsed tag
		// var id_found_in_element; // name of the element where an id attribute has been found
		
		
		//var tree; // contain a breadcrumb of the xml elements currently parsed
		
		
		
		function open_element_handler($parser, $element_name, $attributes) {
				$this->depth++;
				array_push ($this->tree, $element_name);
				
				
				if (isset($attributes['id']))
				{
						// if an id attribute has been found, we consider that we have to increase the array by one level (one more depth level)
						
						$this->id_found[$this->depth] = true;
						$this->id_found_in_element[$this->depth] = $element_name;
						$this->depth++;
						array_push ($this->tree, $attributes['id']);
				}
				
				// debugging :
				/*
				echo ("element : $element_name   /   depth : $this->depth <br>");
				print_a ($this->tree);
				echo (" <br>");
				*/
		}
		
		function close_element_handler($parser, $element_name) {
				$this->depth--;
				array_pop ($this->tree);
				
				// when closing an element, if we close the element with a specific id (id_found)
				// and we check if this element is the element whose id belongs to it (id_found_in_element)
				// -> we close one more depth in the array
				
				if (isset($this->id_found[$this->depth]) and $this->id_found_in_element[$this->depth] == $element_name)
				{
						$this->depth--;
						array_pop ($this->tree);
						$this->id_found[$this->depth] = false;
						
						/*
						echo '<hr>' . $this->id_found_in_element[$this->depth];
						print_a($this->tree);
						echo '<hr>';
						*/
						
						$this->id_found_in_element[$this->depth] = '';
				}
				
				// echo ("close element : $element_name   /   depth : $this->depth <br>");
				// echo (" <br>");
				
				$this->data = '';
		}
		
		// this function "eval" an array
		function character_data_handler($parser, $data) 
		{
				
				// $this->data .= htmlentities(trim($data));
				$this->data .= trim($data);
				//$this->data .= $data;
				if ($this->data != '')
				{
						$evalme = '$this->result';
						foreach ($this->tree as $tree)
						{
								$evalme .="['" . $tree . "']";
						}
						$evalme.= '=' . "'" . addslashes($this->data) . "'";
						$evalme.=';';
						/*
						echo 'eval : ' . $evalme . '<br>';
						print_a($this->tree);
						*/
						
						// todo security !
						eval($evalme);
				}
				
		}
		
		
		/*
		function default_handler($parser, $data) {
				if( trim($data) ) {
						preg_match_all('/ (\w+=".+")/U', $data, $matches);
						foreach($matches[1] as $match) {
								list($attribute_name, $attribute_value) = (explode('=',$match));
								$attribute_value = str_replace('"','',$attribute_value);
								$this->x2a_array[0]['attributes'][$attribute_name] = $attribute_value;
						}
				}
		}
		*/
		
		
		function xml2array($xml) {
				$this->parser = xml_parser_create();
				$this->tree = array();
				
				xml_set_object($this->parser, $this);
				xml_set_element_handler($this->parser, 'open_element_handler', 'close_element_handler');
				xml_set_character_data_handler($this->parser, 'character_data_handler');
				//xml_set_default_handler($this->parser, 'default_handler');
				xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
				
				foreach($xml as $line) {
						if (!xml_parse($this->parser, $line)) {
								die(sprintf('XML error: %s at line %d', xml_error_string(xml_get_error_code($this->parser)), xml_get_current_line_number($this->parser)));
						}
				}
				
				xml_parser_free($this->parser);
				
				return $this->result;
		}
		
		
		function load($filename) 
		{
				if (file_exists($filename))
				{
						$datafile = file($filename);
						$result = $this->xml2array($datafile);
						return $result;
						
				}
				else
				{
						trigger_error('XML Config file not found', E_USER_ERROR);
						return false;
				}
				
		}
		
		
		
		function parse_folder($folder)
		{
				$configs = '';
				
				// test if folder is found
				if (file_exists($folder))
				{
						$ressource = opendir($folder);
						
						// find files in this folder
						while (($file = readdir($ressource)) !== false) 
						{
								// debug($file, 'xml_parser::parse_folder files');
								if (is_file($folder . '/' . $file))
								{
										$path_parts = pathinfo($file);
										
										// if it's an xml file, parse it and store thge results in an array
										if ($path_parts['extension'] == 'xml')
										{
												$cfg = $this->parse_file($folder. '/' . $file);
												if (is_array($cfg))
												{
														$configs[] = $cfg;
												}
										}
								}
						}
						
						if (is_array($configs))
						{
								$final = $configs[0];
								foreach ($configs as $config)
								{
										$final = array_merge($final, $config);
								}
								//debug ($final, 'config final');
								return $final;
						}
						else
						{
								trigger_error("xml_parser::parse_folder() no config found");
								return false;
						}
				}
				else
				{
						trigger_error("xml_parser::parse_folder() : $folder is not found"); 
						return false;
				}
		}
		
		
}

?>