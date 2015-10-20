<?php
require_once(ROOT . '/class/validation.class.php');

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
		
		
		if (isset($thinkedit->config['content'][$table]['field'][$id]))
		{
			$this->config = $thinkedit->config['content'][$table]['field'][$id];
		}
		elseif (isset($thinkedit->config['table'][$table]['field'][$id]))
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
	
	function getParsed()
	{
		trigger_error('field::getParsed() : deprecated');
		return $this->get();
	}
	
	
	/*
	Return the field content passed trough filters defined in config files / content type definitions
	*/
	function getFiltered()
	{
		trigger_error('deprecated also need to discuss api a bit more ;-) ');
		//trigger_error('field::getFiltered() : please subclass getFiltered() in your custom field class');
		return $this->get();
	}
	
	/**
	* Returns the field rendered with all the filters applied
	*/
	function render()
	{
		return $this->get();
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
		$data = $this->get();	
		
		if ($data == '0')
		{
			return false;
		}
		
		
		
		if (empty($data))
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
	
	/*
	
	// Implicit behavior is not always a good idea.
	// reimplented isUsedIn below
	
	function isUsedIn($what)
	{
		// enable by default title columns in list view
		if ($this->isTitle() && $what == 'list')
		{
			return true;
		}
		
		
		if (isset($this->config['use'][$what]))
		{
			//print_r ( $this->config['use']);
			if ($this->config['use'][$what] == 'false' || $this->config['use'][$what] == false)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		elseif($what == 'participation')
		{
			return false;
		}
		else
		{
			// this is the default behavior. 
			// If a particular use is not defined in config, we assume the field must be shown. 
			return true;
		}
	}
	*/
	
	/*
	function isUsedIn($what)
	{
		// enable by default title columns in list view
		if ($this->isTitle() && $what == 'list')
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
			
			if (!$this->config['use'][$what])
			{
				return false;
			}
			
			if ($this->config['use'][$what] == 'true')
			{
				return true;
			}
			
			if ($this->config['use'][$what])
			{
				return true;
			}
			
			
		}
		elseif($what == 'participation')
		{
			return false;
		}
		else
		{
			// this is the default behavior. 
			// If a particular use is not defined in config, we assume the field must be shown. 
			return true;
		}
	}
	*/
	
	
	// third attempt : return true always, but when false is defined :
	function isUsedIn($what)
	{
		if (isset($this->config['use'][$what]))
		{
			//print_r ( $this->config['use']);
			if ($this->config['use'][$what] == 'false')
			{
				return false;
			}
			
			if (!$this->config['use'][$what])
			{
				return false;
			}
		}		
		
		// this is the default behavior. 
		// If a particular use is not defined in config, we assume the field must be shown. 
		return true;
		
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
		//trigger_error('todo');
	}
	
	
	function isRequired()
	{
		if (isset($this->config['validation']['is_required']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function validate()
	{
		if ($this->isRequired() && $this->isEmpty())
		{
			$error['type'] = 'required';
			$error['help'] = translate('field_is_required');
			$this->errors[] = $error;
		}
		elseif ($this->isTitle() && $this->isEmpty())
		{
			$error['type'] = 'required';
			$error['help'] = translate('title_field_is_required');
			$this->errors[] = $error;
		}
		
		
		if (isset($this->errors) && is_array($this->errors))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function getErrorMessage()
	{
		$out = '';
		
		if (isset($this->errors))
		{
			foreach ($this->errors as $error)
			{
				$out .= $error['help'] . ' ';
			}
			return $out;
		}
		else
		{
			return false;
		}
		
		
		
	}
	
	
	
	
	
}

?>
