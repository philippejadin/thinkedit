<?php
require_once 'field.text.class.php'; 


/*
Wiki text area

use field->getParsed() instead of field->get() in order to have htmlified content

Uses the function from blogotext.com simple blog software. Need to check licence with the author
*/
class field_wiki extends field_text
{
	
	
	function getParsed()
	{
		return $this->convertToWiki($this->get());
	}
	
	
	function renderUI($prefix = false)
	{
		// adaptive textarea rows lenght
		$rows = round(strlen($this->get()) / 60) + 8;
		if ($rows > 30) $rows = 30;
		$out = '';
		$out .= sprintf('<textarea name="%s" cols="80" rows="%s">%s</textarea>', $prefix . $this->getName(), $rows, $this->getHtmlSafe());
		return $out;
	}
	
	
	function convertToWiki($text) 
	{
		$text = preg_replace("/(\r\n|\r\n\r|\n|\r)/", "\r", "\r".htmlspecialchars($text)."\r"); 
		$tofind= array(
		'`@@(.*?)@@`',																	// code
		'`\r!!!!!(.*?)\r+`',														// h5
		'`\r!!!!(.*?)\r+`',															// h4
		'`\r!!!(.*?)\r+`',															// h3
		'`\r!!(.*?)\r+`',																// h2
		'`\r!(.*?)\r+`',																// h1
		'`\(\((.*?)\|(.*?)\)\)`',												// img
		'`\[\(\(([^[]+)\|([^[]+)\)\)\|([^[]+)\]`',			// img + a href
		'`(.*?)\r+`',																		// p
		'`\[([^[]+)\|([^[]+)\]`',												// a href
		'`\[(http://)([^[]+)\]`',												// url
		'`\_\_(.*?)\_\_`',															// strong
		'`{(.*?)}`',																		// italic
		'`--(.*?)--`',																	// del
		'`\+\+(.*?)\+\+`',															// ins
		'`%%`',																					// br
		'`<p></p>`'																			// vide
		);
		$toreplace= array(
		'<code><pre>$1</pre></code>',										// code
		'<h5>$1</h5>'."\n",															// h5
		'<h4>$1</h4>'."\n",															// h4
		'<h3>$1</h3>'."\n",															// h3
		'<h2>$1</h2>'."\n",															// h2
		'<h1>$1</h1>'."\n",															// h1
		'<img src="$1" alt="$2" />',										// img
		'<a href="$3"><img src="$1" alt="$2" /></a>',		// img + a href
		'<p>$1</p>'."\n",																// p
		'<a href="$2">$1</a>',													// a href
		'<a href="$1$2">$2</a>',												// url
		'<strong>$1</strong>',													// strong
		'<em>$1</em>',																	// italic
		'<del>$1</del>',																// del
		'<ins>$1</ins>',																// ins
		'<br />',																				// br
		''																							// vide
		);
		$converted_text = preg_replace($tofind, $toreplace, $text);
		return $converted_text;
	}
}
?>
