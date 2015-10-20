<?php
require_once ROOT . '/lib/phpmailer/class.phpmailer.php';

/**
* Very thin wrapper around phpmailer http://phpmailer.sourceforge.net/extending.html
* This way, the programing style is respected.
* todo : add email validation
* todo : add throttling ?
*/
class mailer extends PHPMailer
{
		
		function setFrom($from)
		{
				$this->From = $from;
		}
		
		
		function setTo($to)
		{
				$this->AddAddress($to);
		}
		
		function setSubject($subject)
		{
				$this->Subject = $subject;
		}
		
		function setBody($body)
		{
				$this->Body = $body;
		}
		
		function isHtml($value)
		{
				return parent::IsHTML($value);
		}
		
		function send()
		{
				$this->CharSet = 'utf-8';
				return parent::Send();
		}
}

?>
