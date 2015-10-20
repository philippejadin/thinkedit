<?php
require_once 'field.base.class.php'; 

class field_id extends field
{
		
		function renderUI($prefix = false)
		{
				
				$out = '';
				
				//$out .= sprintf('<input type="text" value="%s" name="%s">', $this->getRaw(), $this->getName());
				$out .= '<!--';
				$out .= $this->getName() . ' : ' . $this->get();
				
				$out .= ' (' . translate('edit_id_is_not_editable') .')';
				$out .= '-->';
				$out .='<input type="hidden" name="' . $prefix . $this->getName() . '" value="' . $this->getHtmlSafe() . '">';
				return $out;
		}
		
		
		function isPrimary()
		{
				// handle very special case when an ID field is not primary (it is the case in the relation table)
				// this can be configured in the config using :
				/*
				<field id="id">
				<type>id</type>
				<primary>false</primary>
				</field>
				*/
				//print_r ($this->config);
				
				if (isset($this->config['primary']))
				{
						if ($this->config['primary'] == 'false')
						{
							
								return false;
						}
				}
				
				return true;
		}
		
		function get()
		{
				
				if (!empty($this->data))
				{
						return $this->data;
						/*
						if (is_numeric($this->data))
						{
								return $this->data;
						}
						else
						{
								//trigger_error('field_id::get() :
								//trigger_error(__METHOD__ . ' id is not numeric');
								return $this->data;
						}
						*/
				}
				else
				{
						return false;
				}
				
		}
		
		
		function isUsedIn($what)
		{
				return true; 
		}
	
		/*
		function validate()
		{
				return true;
		}
		*/
		
		
}
?>
