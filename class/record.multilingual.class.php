<?php

require_once 'record.class.php'; 

class record_multilingual extends record
{
		
		function record_multilingual($table) 
		{
				parent::record($table);
		}
		
		/**
		* Returns the field id describing the locale of this record
		* Usually this is simply "locale"
		*
		*/
		function getLocaleFieldId()
		{
				foreach ($this->field as $field)
				{
						if ($field->getType() == 'locale')
						{
								return $field->getId();
								
						}
				}
				trigger_error('record_multilingual::getLocaleFieldId() : very strange : you are using a multilingual record but there is no locale field defined. Check your config file'); 
				return false;
		}
		
		/**
		* Set the locale of this record
		* 
		*
		*/
		function setLocale($locale)
		{
				return $this->field[$this->getLocaleFieldId()]->set($locale);
				
		}
		
		
		/**
		* Returns the locale of this record
		* 
		*
		*/
		function getLocale()
		{
				return $this->field[$this->getLocaleFieldId()]->get();
		}
		
		/**
		* Returns true if this record is multilingual
		* of course, this record_multilingual object will allways return true
		*
		*/
		function isMultilingual()
		{
			return true;
		}
		
		/**
		* Returns a list of translations for this record
		* 
		*
		*/
		function getLocaleList()
		{
				$where['id'] = $this->getId();
				$results = $this->find($where);
				
				if ($results)
				{
						foreach ($results as $result)
						{
								$locales[] = $result->field[$result->getLocaleFieldId()]->get();
						}
						return $locales;
				}
				else
				{
						// trigger_error('record_multiligual::getTranslationsList() :  strange, no locales found for this record');
						return false;
				}				
		}
		
		function loadByArray($data)
		{
				return $this->load();
		}
		
		function getArray()
		{
				return true;
		}
		
		function load()
		{
				//this is tricky : we first try to load the record
				if (parent::load()) 
				{
						return true;
				}
				// if it fails ...
				else
				{
						// we set the locale of the record to the first found locale for this record
						$locale_list = $this->getLocaleList();
						$this->setLocale($locale_list[0]);
						return parent::load();
				}
		}
		
}

?>
