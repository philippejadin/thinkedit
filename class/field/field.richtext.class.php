<?php
require_once 'field.base.class.php'; 

/*
This class needs some love

Curently, only tinymce is working well
*/
class field_richtext extends field
{
	
	function renderUI_dojo($prefix = false)
	{
		// te_ mean thinkedit, and is used to prevent global namespace collision (which is quite unlikely)
		global $te_wysiwyg_is_init;
		
		$out = '';
		if (!isset($te_wysiwyg_is_init))
		{
			$out .= '<script type="text/javascript">
			dojo.require("dojo.profile");
			dojo.require("dojo.event.*");
			dojo.require("dojo.widget.Editor");
			dojo.profile.start("init");
			dojo.hostenv.writeIncludes();
			</script>';
			$te_wysiwyg_is_init = true;
		}
		
		
		
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 80) + 20;
		if ($rows > 30) $rows = 30;
		
		$out .= sprintf('<div dojoType="Editor">%s</div>', $this->getRaw());
		
		// we can init tinymce only once for a page.
		
		
		
		
		return $out;
	}
	
	
	function renderUI($prefix = false)
	{
		if (isset($this->config['engine']))
		{
			if ($this->config['engine'] == 'tinymce')
			{
				return $this->renderUI_tinymce($prefix);
			}
			elseif ($this->config['engine'] == 'rte')
			{
				trigger_error('not yet');
				return $this->renderUI_rte($prefix);
			}
			elseif ($this->config['engine'] == 'fck')
			{
				trigger_error('not yet');
				return $this->renderUI_fck($prefix);
			}
			else
			{
				trigger_error('unknown richtext engine');
			}
			
		}
		return $this->renderUI_tinymce($prefix);
	}
	
	
	function renderUI_tinymce($prefix = false)
	{
		// te_ mean thinkedit, and is used to prevent global namespace collision (which is quite unlikely)
		global $te_wysiwyg_is_init;
		
		
		$out = '';
		
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 80) + 20;
		if ($rows > 30) $rows = 30;
		
		$out .= sprintf('<textarea name="%s" cols="80" rows="%s" mce_editable="true">%s</textarea>', $prefix . $this->getName(), $rows, $this->getRaw());
		
		// we can init tinymce only once for a page.
		if (!isset($te_wysiwyg_is_init))
		{
			$out .= '<script language="javascript" type="text/javascript" src="' . ROOT_URL . '/lib/tiny_mce/tiny_mce.js"></script>';
			$out .=  '<!-- tinyMCE -->';
			$out .= '<script language="javascript" type="text/javascript">';
			$out .= '   tinyMCE.init({';
				//$out .= '      cleanup : false, ';
				// custom css file
				//$out .= '      theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",';
				//$out .= '      content_css : "test.css",';
				$out .= '      mode : "specific_textareas", ';
				$out .= '      theme_advanced_toolbar_align : "left", ';
				$out .= '      theme_advanced_toolbar_location : "top",';
				$out .= '      plugins : "autosave"';
				
				
				
			$out .= '   });';
			$out .= '</script>';
			$out .= '<!-- /tinyMCE -->';
			
			$te_wysiwyg_is_init = true;
		}
		
		
		
		
		
		return $out;
	}
	
	
	
	function renderUI_rte($prefix = false)
	{
		// te_ mean thinkedit, and is used to prevent global namespace collision (which is quite unlikely)
		global $te_wysiwyg_is_init;
		
		
		$out = '';
		
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 80) + 20;
		if ($rows > 30) $rows = 30;
		
		$out .='
		<script language="JavaScript" type="text/javascript" src="' . ROOT_URL . '/lib/rte/richtext_compressed.js"></script>';
		
		$out .='
		<script language="JavaScript" type="text/javascript">
		<!--
		
		// using jquery to update rtes when user clicks on submit
		$("form").submit(function()
		{
			updateRTEs();
			return true;
		});
		
		//Usage:	 initRTE(imagesPath, includesPath, cssFile, genXHTML, encHTML)
		initRTE("' . ROOT_URL . '/lib/rte/images/", "' .  ROOT_URL . '/lib/rte/", "", true);
		//-->
		</script>
		<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>';
		
		$out .='
		<script language="JavaScript" type="text/javascript">
		var rte1 = new richTextEditor("rte1");';
		
		$out .= 'rte1.html = \'' . $this->rteSafe($this->get()) .'\';';
		
		$out .='
		
		rte1.build();
		</script>
		';
		
		return $out;
	}
	
	
	function renderUI_fck($prefix = false)
	{
		
		$out = '';
		
		$out = '';
		
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 80) + 20;
		if ($rows > 30) $rows = 30;
		
		$out .= sprintf('<textarea name="%s" cols="80" rows="%s" mce_editable="true">%s</textarea>', $prefix . $this->getName(), $rows, $this->getRaw());
		
		
		$out .= '<script type="text/javascript" src="' . ROOT_URL . '/lib/fckeditor/fckeditor.js"></script>';
		$out .= '<script type="text/javascript">';
		$out .= 'window.onload = function()';
		$out .= '{';
		$out .= '  var oFCKeditor = new FCKeditor( "' . $this->getName() . '" ) ;';
		$out .= '  oFCKeditor.BasePath = "' . ROOT_URL . '" ;';
		$out .= '  oFCKeditor.ReplaceTextarea() ;';
		$out .= '}';
		$out .= '</script>';
		
		return $out;
		
	}
	
	function getNice()
	{
		return strip_tags($this->get());
	}
	
	function rteSafe($strText) 
	{
		//returns safe code for preloading in the RTE
		$tmpString = $strText;
		
		//convert all types of single quotes
		$tmpString = str_replace(chr(145), chr(39), $tmpString);
		$tmpString = str_replace(chr(146), chr(39), $tmpString);
		$tmpString = str_replace("'", "&#39;", $tmpString);
		
		//convert all types of double quotes
		$tmpString = str_replace(chr(147), chr(34), $tmpString);
		$tmpString = str_replace(chr(148), chr(34), $tmpString);
		//	$tmpString = str_replace("\"", "\"", $tmpString);
		
		//replace carriage returns & line feeds
		$tmpString = str_replace(chr(10), " ", $tmpString);
		$tmpString = str_replace(chr(13), " ", $tmpString);
		
		return $tmpString;
	}

	
}
?>
