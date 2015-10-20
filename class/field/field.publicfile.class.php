<?php
require_once 'field.file.class.php'; 

/*
A public file allows people on a public site to upload a file
*/
class field_publicfile extends field_file
{
		
		function renderUI($prefix = false)
		{
			global $thinkedit;
			
			// if we are in the interface and the field is already filled, we show a normal thinkedit file input ui
			if (($this->get()) && ($thinkedit->context->get() == 'interface'))
			{
				return parent::renderUI();
			}
			else // else, we present a simple file upload input, usable by the public part of a website
			{
				$out = '';
				$out .= sprintf('<input type="file" value="%s" name="%s", size="16">', $this->getHtmlSafe(), $this->getName());
			}
			return $out;
			
		}
		
		
		/*
		Returns correspoding filesystem object if available
		*/
		function getFilesystem()
		{
				if ($this->getRaw() <>'')
				{
						global $thinkedit;
						$filesystem = $thinkedit->newFilesystem();
						$filesystem->setPath($this->getRaw());
						return $filesystem;
				}
				return false;
		}
		
		
		/*
		When called, the file submited by the user is uploaded and saved to the server. 
		A filesystem object containing the file is returned
		*/
		function uploadFile($prefix = false)
		{
			global $thinkedit;
			$filesystem = $thinkedit->newFilesystem();
			if (isset($this->config['upload_path']))
			{
				$filesystem->setPath($this->config['upload_path']);
			}
			else
			{
				//trigger_error('publicfile::handleFormPost() : please define an upload folder in your config file for user uploaded files, and create it in file manager');	
				$filesystem->setPath('/public_uploads/');
			}

			$added_file = $filesystem->addFileFromUpload($this->getId(), true);
			
			if ($added_file)
			{
				$this->set($added_file->getPath());
				return $added_file;
			}
			else
			{
				return false;
			}
			
		}
		
}
?>
