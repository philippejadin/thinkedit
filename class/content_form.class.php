<?php
require_once 'url.class.php';
require_once 'html_form.class.php';


/*
The content form takes a content type as input, and renders a form based on it. 
Validation is performed
Content can be retrieved as an array
Content can be pre-set as an array as well

it extends the html_form class

Content and content fields (validation, error messages, etc...) are defined in config files
*/

class content_form extends html_form
{
	function content_form($content_type)
	{
		parent::html_form();
		global $thinkedit;
		if (isset($thinkedit->config['content'][$content_type]))
		{
			$this->content_type = $content_type;
		}
		else
		{
			trigger_error('invalid content type');
		}
		
		// form is valid at first
		$this->valid = true;
		
		// init fields
		$config = $thinkedit->config;
		foreach ($config['content'][$this->content_type]['field'] as $field_id => $field_data)
		{
			//echo $field_id;
			
			$field = $thinkedit->newField($this->content_type, $field_id);
			
			
			
			// init field content
			if ($this->isSent())
			{
				if (isset($_REQUEST[$field_id]))
				{
					$field->set($_REQUEST[$field_id]);
				}
				$field->handleFormPost();
				if (!$field->validate())
				{
					$this->valid = false;
				}
			}
			$this->fields[$field->getId()] = $field;
		}
		
		
	}
	
	
	function isValid()
	{
		if ($this->isSent())
		{
			return $this->valid;
		}
		else
		{
			return true;
		}
	}
	
	function setArray($content)
	{
		foreach ($this->fields as $field)
		{
			if (isset ($content[$field->getId()]))
			{
				$this->fields[$field->getId()]->set($content[$field->getId()]);
			}
		}
		
		return true;
	}
	
	function getArray()
	{
		foreach ($this->fields as $field)
		{
			$out[$field->getId()] = $field->get();
		}
		if (isset ($out))
		{
			return $out;
		}
		else
		{
			return false;
		}
	}
	
	
	function clear()
	{
		foreach ($this->fields as $id=>$field)
		{
			$this->fields[$id]->set(false);
		}
	}
	
	/*
	Sets the use case of this form
	For instance, a form can be used inside the admin interface or for public participations
	This gives the possibility to show / hide some fields to the user
	*/
	function setUseCase($use)
	{
		$this->use = $use;
	}
	
	function render()
	{
		global $thinkedit;
		if (isset ($this->content_type))
		{
			$config = $thinkedit->config;
			foreach ($this->fields as $field)
			{
				//if ($field->isUsedIn($this->use) && $field->getType() <> 'id')
				if ($field->getType() <> 'id')
				{
					$this->add('<div class="te_field">');
					
					$this->add('<div class="te_field_title">');
					if ($field->isRequired() || $field->isTitle())
					{
						$this->add('<span class="te_field_required">*</span>');
					}
					$this->add($field->getTitle() . ' : ' );
					$this->add('</div>');
					
					if ($field->getHelp())
					{
						$this->add('<div class="te_field_help">');
						$this->add($field->getHelp());
						$this->add('</div>');
					}
					
					if ($this->isSent() && $field->getErrorMessage())
					{
						$this->add('<div class="te_field_error">');
						$this->add($field->getErrorMessage());
						$this->add('</div>');
					}
					
					$this->add('<div class="te_field_ui">');
					$this->add($field->renderUi());
					$this->add('</div>');
					
					$this->add('</div>');
				}
			}
		}
		else
		{
			trigger_error('you must define a content type to use this content_form');
			return false;
		}
		
		return parent::render();
		
	}
	
}

?>
