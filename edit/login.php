<?php
/*
Thinkedit 2.0 by Philippe Jadin and Pierre Lecrenier


User validation

*/

include_once('common.inc.php');


$out['title'] = 'Thinkedit Login';




//print_r($_REQUEST);

// check if we have a login and a password to validate user class

if ($url->get('login') && $url->get('password'))
{
		$login = $url->get('login');
		$password = $url->get('password');
		debug($login, 'login');
		debug($password, 'password');
		
		
		if ($thinkedit->user->login($login, $password))
		{
				// now we redirect to the correct page
				// first case, we know where to send the user
				if ($url->get('original_url'))
				{
						//echo 'original url';
						$url->redirect($url->get('original_url'));
				}
				
				// second case, we don't, so we redirect to main
				else
				{
						//echo 'main';
						$url->redirect('main.php');
				}
		}
		// if invalid user, reload login page with error message
		else
		{
				//echo 'failed';
				$url->set('authentification', 'failed');
				$url->redirect();
		}
}




if ($url->get('authentification') == 'failed')
{
		$out['error'] = translate('login_failed');
}


// if an email is found in the request, try to send an email to this user :

if ($url->get('email'))
{
		die('this feature needs work');
		$email = $db->escape($url->get('email'));
		$query = "select * from users where email='$email'";
		$user = $db->get_row($query);
		//$db->debug();
		if ($db->num_rows > 0)
		{
				$msg = translate('forgotten_paswd_email_intro', false);
				$msg.= "\n";
				$msg.= "\n";
				$msg.= "\n";
				$msg.= translate('forgotten_paswd_email_your_login', false);
				$msg.= $user->login;
				$msg.= "\n";
				$msg.= "\n";
				$msg.= translate('forgotten_paswd_email_your_password', false);
				$msg.= $user->password;
				$msg.= "\n";
				$msg.= "\n";
				$msg.= "\n";
				$msg.= translate('forgotten_paswd_email_outro', false);
				
				//echo $msg;
				
				if ( mail($user->email, translate('forgotten_paswd_email_subject', false) , $msg ) )
				
				{
						$out['info'] = translate('login_mail_sent');
				}
				else
				{
						$out['error'] = translate('login_mail_not_sent');
				}
				
				
		}
		else
		{
				$out['error'] = translate('login_mail_not_found');
		}
		
}



$out['banner_needed'] = false;

// no user or password in the request, we need to display a login page, which is done by default anyway

include('header.template.php');
include('login.template.php');
include('footer.template.php');

?>
