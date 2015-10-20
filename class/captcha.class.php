<?php

class captcha
{
		
		/**
		* Default captcha size (in chars)
		* 
		*/
		var $size = 3; 
		
		/**
		* Returns the url to the captcha
		* 
		*/
		function render()
		{
				// first clear text
				/*
				if (isset($_SESSION['captcha']))
				{
						unset ($_SESSION['captcha']);
				}
				*/
				// generate captcha string :
				$this->get();
				
				
				return ROOT_URL . '/lib/gotcha/captcha_image.php';
		}
		
		
		/**
		* Returns the value of the current captcha
		* 
		*/
		function get()
		{
				
				if (isset($_SESSION['captcha']))
				{
						$text = $_SESSION['captcha'];
				}
				else
				{
						$t =  md5(uniqid(rand(), 1));
						//$text = strtolower(substr($t, rand(0, (strlen($t)-6)), rand(3,6)));
						$text = strtolower(substr($t, 0, $this->size));
						$_SESSION['captcha'] = $text;
				}
				
				return $text;

		}
		
		
		/**
		* Reset the captcha text
		*
		*/
		function reset()
		{
				unset($_SESSION['captcha']);
		}
		
}

?>
