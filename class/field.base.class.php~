<?php


class field
{
		
		var $data;
		
		function field($table, $id, $data = false)
		{
				global $thinkedit;
				
				$this->table = $table;
				$this->id = $id;
				if ($data)
				{
						$this->set($data);
				}
				
				
				if (isset($thinkedit->config['table'][$table]['field'][$id]))
				{
						$this->config = $thinkedit->config['table'][$table]['field'][$id];
				}
				else
				{
						die('field::field() Field called "' . $this->id . '" not found in config, check table id spelling in config file / in code');
				}
				
				// echo '<pre>';
				// print_r ($this->config);
		}
		
		
		
		
		function get()
		{
				return $this->data;
		}
		
		function getRaw()
		{
				return $this->data;
		}
		
		function getFriendly($options = false)
		{
				return $this->data;
		}
		
		function getHtmlSafe()
		{
				return htmlspecialchars($this->get());
		}
		
		
		function set($data)
		{
				$this->data = $data;
		}
		
		function getId()
		{
				return $this->id;
		}
		
		function getName()
		{
				return $this->id;
		}
		
		
		function renderUI($prefix = false)
		{
				$out = '';
				$out .= sprintf('<input type="text" value="%s" name="%s", size="32">', $this->getHtmlSafe(), $prefix . $this->getName());
				return $out;
		}
		
		function validate()
		{
				return true;
		}
		
		function getHelp()
		{
				global $thinkedit;
				if (isset($this->config['help'][$thinkedit->user->getLocale()]))
				{
						return $this->config['help'][$thinkedit->user->getLocale()];
				}
				else
				{
						return false;
				}
				
		}
		
		
		function getTitle()
		{
				global $thinkedit;
				if (isset($this->config['title'][$thinkedit->user->getLocale()]))
				{
						return $this->config['title'][$thinkedit->user->getLocale()];
				}
				else
				{
						return ucfirst($this->getName());
				}
				
		}
		
		function isSortable()
		{
				return true;
		}
		
		function isPrimary()
		{
				// print_r ($this->config);
			
				if (isset($this->config['primary']))
				{
						if ($this->config['primary'] == 1 || $this->config['primary'] == 'true')
						{
								return true;
						}
				}
				return false;
		}
		
		function isTitle()
		{
				if (isset($this->config['is_title']))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function getType()
		{
				if (isset($this->config['type']))
				{
						return $this->config['type'];
				}
				else
				{
						return false;
				}
		}
		
		
		
		
		function isEmpty()
		{
				if ($this->data == '0')
				{
						return false;
				}
				
				
				
				if (empty($this->data))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		
		function useInView($view)
		{
				trigger_error('useInView() is deprecated, use isUsedIn() instead');
		}
		
		function isUsedIn($what)
		{
				// enable by default title columns in list view
				if ($this->isTitle())
				{
						return true;
				}
				
				
				if (isset($this->config['use'][$what]))
				{
						//print_r ( $this->config['use']);
						if ($this->config['use'][$what] == 'false')
						{
								return false;
						}
						else
						{
								return true;
						}
				}
				else
				{
						// this is the default behavior. 
						// If a particular use is not defined in config, we assume the field must be shown. 
						return true;
				}
		}
		
		
		/*
		todo : if this function is defined, it could handle posted items from a form.
		
		For instance, a password field could check if a second field is filled 
		with the same password, for validation
		
		Other use case : a date or datetime field could be rendered 
		using multiple select boxes (one for day, one for month, one for year). 
		This function would "merge" the different select box as one single "mysql understandable"
		string.
		*/
		function handleFormPost($prefix = false)
		{
				trigger_error('todo');
		}
		
}

?>
