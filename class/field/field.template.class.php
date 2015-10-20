<?php
require_once 'field.base.class.php'; 


class field_template extends field
{
		
		function renderUI($prefix = false)
		{
				global $thinkedit;
				
				// generate a list of available templates
				// templates are stored in the /design/**design_name**/templates/ folder
				$design = $thinkedit->configuration->getDesign();
				$design_folder = ROOT . '/design/'. $design . '/templates/';
				if (file_exists($design_folder))
				{
						$ressource = opendir($design_folder);
						
						// find files in this folder
						while (($file = readdir($ressource)) !== false) 
						{
								if (is_file($design_folder . '/' . $file))
								{
										$path_parts = pathinfo($file);
										
										if ($path_parts['extension'] == 'php')
										{
												$filename = basename($file);
												$templates[] = $filename;
										}
								}
						}
				}
				else
				{
						$out = translate('template_folder_not_found');
						return $out;
				}
				
				if (isset($templates) && is_array($templates))
				{
						$out='<select name="' . $prefix . $this->getName() . '">';
						$out .= '<option value="">'. translate('automatic_template') .'</option>';
						// render dropdown
						foreach ($templates as $template)
						{
								// if template defined in db, selected=selected
								if ($this->get() == $template)
										{
												$selected = ' selected="selected" ';
										}
										else
										{
												$selected = '';
										}
										
								$out .= '<option value="' . $template . '"' . $selected . '>';
								$out .= $template;
								$out .= '</option>';
						}
						$out .= '</select>';
						
						
						// returns
				}
				else
				{
						$out = translate('custom_templates_not_found');
				}
				return $out;
		}
}
?>
