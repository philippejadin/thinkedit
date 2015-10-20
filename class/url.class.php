<?php

/*

Trouver un myen sûr de lier à un objet avec un uid qui est tableau de taille inconnue,
et de pouvoir mettre plusieurs objets dans le même url


on pourrait avoir

object_source comme préfix

avec un article ça donnerait :

object_source_id=1
object_source_type=article
object_source_class=record

s'il était multilingue, ça donnerait ceci en plus :
object_source_locale=en

du coup, 


url->getObject($prefix = false)
et

url->getObject('object_source_')
donnerait une instance de cet objet...

Pourquoi pas ?

Idem pour :
url->addObject($object, $prefix = false)


*/


class url
{
		var $url;
		var $param;
		var $self;
		var $anchor;
		
		
		
		/*
		we have three arrays :
		
		$this->orig_param : parameters found in the current url
		$this->keep : params user asked to keep in the current params
		$this->params : parameters defined by the user, that must be included, and overriding original parameters
		
		*/
		
		
		/*
		Constructor, will populate self filename and existing parameters
		*/
		function url()
		{
				// define a list of parameters automatically kepts across urls
				// todo : could be configurable DONE : $this->keepParam($id)
				//$keep_params = array('id', 'node', 'type', 'path', 'debug', 'module_id', 'node_id', 'module_type');
				
				foreach ($_REQUEST as $key=>$value)
				{
						$this->orig_param[$key] = $value;
				}
				
				
				foreach ($_GET as $key=>$value)
				{
						$this->orig_param[$key] = $value;
						$this->orig_get_param[$key] = $value;
				}
				
				// security todo
				/*
				from http://blog.phpdoc.info/archives/13-XSS-Woes.html
				
				Er... again without the carrots:
				replace echo $_SERVER['PHP_SELF'] with the ugly ...
				echo substr($_SERVER['PHP_SELF'], 0, (strlen($_SERVER['PHP_SELF']) - @strlen($_SERVER['PATH_INFO'])));
				*/
				//$this->self = $_SERVER['PHP_SELF'];
				// becomes :
				if (isset($_SERVER['PATH_INFO']))
				{
					$path_info_len = strlen($_SERVER['PATH_INFO']);
				}
				else
				{
					$path_info_len = 0;
				}
				
				$this->self = substr($_SERVER['PHP_SELF'], 0, (strlen($_SERVER['PHP_SELF']) - $path_info_len));
				
				
				// fix for IIS
				if (! isset($_SERVER['REQUEST_URI'])) 
				{
						$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
				}
				
				$this->url = $_SERVER['REQUEST_URI'];
				
				$this->keepParam('debug');
				$this->keepParam('locale');
				
		}
		
		
		// delete all urls
		function reset()
		{
					unset($this->param);
		}
		
		// this is an alias
		function set($id, $value)
		{
				$this->setParam($id, $value);
		}
		
		
		function get($id)
		{
				return $this->getParam($id);
		}
		
		
		function addObject($object, $prefix=false)
		{
				if ($object->getUid())
				{
						$uid = $object->getUid();
						foreach ($uid as $key=>$value)
						{
								$this->set($prefix . $key, $value);
						}
						
						return true;
				}
				return false;
		}
		
		function getObject($prefix = false)
		{
				global $thinkedit;
				$class = $this->get($prefix . 'class');
				$type = $this->get($prefix . 'type');
				$id = $this->get($prefix . 'id'); // todo : custom primary keys parameters
				
				if ($class && $type)
				{
						$uid['class'] = $class;
						$uid['type'] = $type;
						if ($id)
						{
								$uid['id'] = $id;
						}
						return $thinkedit->newObject($uid);
				}
				else
				{
						trigger_error('url::getObject() cannot instantiate object from url');
						return false;
				}
		}
		
		function setArray($params)
		{
				foreach ($params as $key=>$value)
				{
						$this->set($key, $value);
				}
		}
		
		
		function keepReferer()
		{
				$this->set('referrer', $this->url);
		}
		
		
		
		/*
		use this to add a parameter to an url
		*/
		function setParam($id, $value)
		{
				$this->param[$id] = $value;
		}
		
		/*
		use this to add a parameter to an url
		*/
		function setParamArray($values)
		{
				foreach ($values as $key=>$value)
				{
						$this->param[$key] = $value;
				}
		}
		
		/*
		retrieve existing param, looking first at user defined ones, than in the existing parameters found in the url
		*/
		function getParam($id)
		{
				/*
				if (isset($this->param[$id]))
				{
						return $this->param[$id];
				}
				*/
				//else
				// todo security
				// 
				if (isset($this->orig_param[$id]))
				{
						return $this->orig_param[$id];
				}
				else
				{
						return false;
				}
		}
		
		
		/*
		Keep an existing parameter when rendering an url
		*/
		function keepParam($param)
		{
				$this->keep[] = $param;
		}
		
		
		/*
		Alias
		Keep an existing parameter when rendering an url
		*/
		function keep($param)
		{
				$this->keepParam($param);
		}
		
		/*
		Keep all existing parameter when rendering an url
		*/
		function keepAll()
		{
				foreach ($this->orig_param as $key=>$value)
				{
						$this->keepParam($key);
				}
		}
		
		/*
		Keep all existing GET parameters when rendering an url
		*/
		function keepAllGet()
		{
				if (isset($this->orig_get_param))
				{
						foreach ($this->orig_get_param as $key=>$value)
						{
								$this->keepParam($key);
						}
				}
		}
		
		
		/*
		unset a user defined parameter
		*/
		function unsetParam($id)
		{
				if (isset($this->param[$id]))
				{
						
						unset($this->param[$id]);
				}
				if (isset($this->orig_param[$id]))
				{
						unset($this->orig_param[$id]);
				}
		}
		
		
		
		/*
		Render query string
		Private function
		*/
		function getQueryString()
		{
				$url = '';
				$final_param = '';
				// populate final_param list with original params, but only the ones we want to keep
				
				if (isset ($this->orig_param))
				{
						if (is_array ($this->orig_param))
						{
								foreach ($this->orig_param as $key=>$value)
								{
										if (is_array($this->keep))
										{
												if (in_array($key, $this->keep))
												{
														// todo : security
														$final_param[$key] = htmlentities($value);
												}
										}
								}
								
						}
				}
				
				// override with user defined ones
				if (is_array ($this->param))
				{
						foreach ($this->param as $key=>$value)
						{
								$final_param[$key] = $value;
						}
				}
				
				// render if we have params
				if (is_array($final_param))
				{
						$url.='?';
						

						$i = 1;
						foreach ($final_param as $key=>$value)
						{
								$url .= $key . '=' . $value;
								if ($i < count($final_param))
								{
									$url .='&';
								}
								$i++;
						}
						
				}
				
				
				return $url;
		}
		
		function setFilename($filename)
		{
				$this->self = $filename;
		}
		
		
		/*
		Render an url
		
		if filename is set, it is used instead of curent script filename
		*/
		function render($filename = false)
		{
				if ($filename)
				{
						$url = $filename;
				}
				else
				{
						$url = $this->self;
				}
				
				if (isset($this->anchor))
				{
						$anchor = '#' . $this->anchor;
				}
				else
				{
						$anchor = false;
				}
				return $url . $this->getQueryString() . $anchor;
		}
		
		
		function renderAbsoluteUrl($filename = false)
		{
				if ($filename)
				{
						$url = $filename;
				}
				else
				{
						$url = $this->self;
				}
				return SITE_URL . $url . $this->getQueryString();
		}
		
		
		
		function renderHref($title, $filename=false)
		{
				return '<a href="' . $this->render($filename) . '">' . $title . '</a>';
		}
		
		
		/*
		Links an object to an action
		
		The object should provide getUid()
		*/
		function linkTo($object, $filename)
		{
				if ($object->getUid())
				{
						$uid = $object->getUid();
						foreach ($uid as $key=>$value)
						{
								$this->set($key, $value);
						}
						if ($filename)
						{
								$url = $filename;
						}
						else
						{
								$url = $this->self;
						}
						return $url . $this->getQueryString();
				}
				else
				{
						return false;
				}
		}
		
		function redirect($filename = false)
		{
				// what we do : use javascript redirect with IIS (IIS real php redirect seems flacky)
				// else use real headers
				// some docs here : http://www.agora-project.net/forumphpbb/viewtopic.php?p=300&
				// and maybe here : http://bugs.php.net/bug.php?id=9852
				
				global $thinkedit;
				$context = $thinkedit->getContext();
				if (headers_sent())
				{
						echo translate('headers_sent_help');
						echo('<a href="'. $this->render($filename). '">Redirect</a>');
						die();
				}
				
				if ($context->getServerType() == 'apache')
				{
						$header = 'Location: '. $this->render($filename);
						header($header);
				}
				else
				{
						echo("<script>location.href='". $this->render($filename). "'</script>");
				}
				die();
				//$header = 'location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']). $this->render($filename);
				
				
		}
		
		function debug()
		{
				echo '<pre>';
				echo '<h1>URL DEBUG</h1>';
				echo '<h2>Orig Params</h2>';
				print_r ($this->orig_param);
				echo '<h2>New Params</h2>';
				print_r ($this->param);
				echo '<h2>Keep</h2>';
				print_r ($this->keep);
				echo '</pre>';
		}
		
		
			// --------------------------------------------------------------------
	
	/**
	 * From code igniter
	 * need to check licence before using this : 
	 * http://www.codeigniter.com/user_guide/license.html
	 *
	 * XSS Clean
	 *
	 * Sanitizes data so that Cross Site Scripting Hacks can be
	 * prevented.Ê This function does a fair amount of work but
	 * it is extremely thorough, designed to prevent even the
	 * most obscure XSS attempts.Ê Nothing is ever 100% foolproof,
	 * of course, but I haven't been able to get anything passed
	 * the filter.
	 *
	 * Note: This function should only be used to deal with data
	 * upon submission.Ê It's not something that should
	 * be used for general runtime processing.
	 *
	 * This function was based in part on some code and ideas I
	 * got from Bitflux: http://blog.bitflux.ch/wiki/XSS_Prevention
	 *
	 * To help develop this script I used this great list of
	 * vulnerabilities along with a few other hacks I've
	 * harvested from examining vulnerabilities in other programs:
	 * http://ha.ckers.org/xss.html
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function xss_clean($str, $charset = 'ISO-8859-1')
	{	
		/*
		 * Remove Null Characters
		 *
		 * This prevents sandwiching null characters
		 * between ascii characters, like Java\0script.
		 *
		 */
		$str = preg_replace('/\0+/', '', $str);
		$str = preg_replace('/(\\\\0)+/', '', $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 *
		 */
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
		
		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 *
		 */
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Normally urldecode() would be easier but it removes plus signs
		 *
		 */	
		$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
		$str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);		
				
		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 *
		 */
		
		if (preg_match_all("/<(.+?)>/si", $str, $matches))
		{		
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$str = str_replace($matches['1'][$i],
									$this->_html_entity_decode($matches['1'][$i], $charset),
									$str);
			}
		}
	
		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * Note: we deal with spaces between characters later.
		 *
		 */		
		$str = preg_replace("#\t+#", " ", $str);
	
		/*
		 * Makes PHP tags safe
		 *
		 *  Note: XML tags are inadvertently replaced too:
		 *
		 *	<?xml
		 *
		 * But it doesn't seem to pose a problem.
		 *
		 */		
		$str = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	
		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 *
		 */		
		$words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word)
		{
			$temp = '';
			for ($i = 0; $i < strlen($word); $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}
			
			$temp = substr($temp, 0, -3);
			$str = preg_replace('#'.$temp.'#s', $word, $str);
			$str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
		}
	
		/*
		 * Remove disallowed Javascript in links or img tags
		 */		
		 $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
		 $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
		 $str = preg_replace("#<(script|xss).*?\>#si", "", $str);

		/*
		 * Remove JavaScript Event Handlers
		 *
		 * Note: This code is a little blunt.  It removes
		 * the event handler and anything up to the closing >,
		 * but it's unlikely to be a problem.
		 *
		 */		
		 $str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU',"\\1>",$str);
	
		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;blink&gt;
		 *
		 */		
		$str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
		
		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed.  Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:		eval&#40;'some code'&#41;
		 *
		 */
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
						
		/*
		 * Final clean up
		 *
		 * This adds a bit of extra precaution in case
		 * something got through the above filters
		 *
		 */	
		$bad = array(
						'document.cookie'	=> '',
						'document.write'	=> '',
						'window.location'	=> '',
						"javascript\s*:"	=> '',
						"Redirect\s+302"	=> '',
						'<!--'				=> '&lt;!--',
						'-->'				=> '--&gt;'
					);
	
		foreach ($bad as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		
						
		log_message('debug', "XSS Filtering completed");
		return $str;
	} 
		
		
}

?>