<?php
require_once 'field.base.class.php'; 


class field_lookup extends field
{
	
	
	function renderUI($prefix = false)
	{
		if ($this->config['source']['type'] == 'table' && isset($this->config['source']['name']))
		{
			global $thinkedit;
			$source = $thinkedit->newRecord($this->config['source']['name']);
			$records = $source->find();
						
			if ($records)
			{
				$out='<select name="' . $prefix . $this->getName() . '">';
				foreach ($records as $record)
				{
					if ($this->get() == $record->getId())
					{
						$selected = ' selected="selected" ';
					}
					else
					{
							$selected = '';
					}
					$out .= '<option value="' . $record->getId() . '"' . $selected . '>';
					
					$out .= $record->getTitle() . ' ';
					$out .= '</option>';
				}
				
				$out .='</select>';
				return $out;
				
			}
			else
			{
				return translate('source_table_is_empty');
			}
			
		}
		else
		{
			trigger_error('field_lookup::renderUI() source type not defined in config or unknown source type, must be a valid table for now');
			return false;
		}
		
	}
	
	
	
	
	
	// todo : build a nice view of this, to show in list view for instance.
	
	// Instead of showing raw data, present the data (title) queried from the source
	
	function getNice()
	{
		if ($this->config['source']['type'] == 'table' && isset($this->config['source']['name']))
		{
			global $thinkedit;
			$config = new config();
			//$table = $thinkedit->newTable($this->config['source']['name']);
			//
			//$source->filter('id', '=', $this->getRaw());
			$source_table = $this->config['source']['name'];
			
			
			$title_fields = $config->getTitleFields($source_table);
			
			require_once 'query.class.php';
			$query = new query();
			$query->addTable($this->config['source']['name']);
			$query->addWhere('id', '=', $this->getRaw());
			
			$results = $query->select();
			
			if (count($results) > 0)
			{
				foreach ($config->getTitleFields($source_table) as $field)
				{
					$out.=$results[0][$field] . ' ';
				}
				return $out;
			}
			else 
			{
				return $this->getRaw();
			}
			
		}
		
		else 
		{
			return $this->getRaw();
		}
		
		
	}
	
	
	
}
?>
