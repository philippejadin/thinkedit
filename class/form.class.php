<?php

die('deprecated, see html_form');

require_once 'url.class.php';

class form
{
		
		/**
		* When given a record instance, it will build an edit form, and handle form validation
		* And save.
		* Quite similar to pear quickform (humhum)
		**/
		function form(&$record)
		{
				$this->record = &$record;
		}
		
		
		function isSent()
		{
				if (isset($_REQUEST['save']))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		function isCancel()
		{
				if (isset($_REQUEST['cancel']))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		
		
		
		/**
		* Validate the form using element validate functions
		*
		**/
		function validate()
		{
				// check if form has been submitted
				// todo : move to save function the check of REQUEST save
				// assign data
				
				// note that foreach works on a copy, which is a *very* bad thing if you don"t know it
				// todo : we access class variables directly, this is not too beautifull
				foreach ($this->record->field as $field_id => $field)
				{
						
						if (isset($_REQUEST[$field->getName()] ))
						{
								$this->record->field[$field_id]->set($_REQUEST[$field->getName()] );
						}
						
				}
				
				// validate using element validation function
				
				foreach ($this->record->field as $field_id => $field)
				{
						if (!$this->record->field[$field_id]->validate())
						{
								trigger_error('form doesn\'t validate');
								return false;
						}
				}
				return true;
    }
		
		
		function render()
		{
				$out = '';
				$url = new url();
				$url->keepAll();
				$out .= sprintf('<form action="%s" method="post">', $url->render());
				
				if (is_array($this->record->field ))
				{
						foreach ($this->record->field as $field)
						{
								$out .= '<div class="input">';
								$out .= $field->getHelp();
								$out .= ' : ';
								$out .= '<br/>';
								$out .= $field->renderUI();
								$out .= '</div>';
						}
				}
				else
				{
						trigger_error('form::render() no fields found in the record, cannot render form');
				}
				$out .= sprintf('<input type="submit" value="%s" name="save"> ', translate('save_button'));
				$out .= sprintf('<input type="submit" value="%s" name="cancel">', translate('cancel_button'));
				$out .= '</form>';
				return $out;
				
		}
		
		
		
}

?>