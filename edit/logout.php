<?php
/*
Thinkedit 2.0 by Philippe Jadin and Pierre Lecrenier
User logout

*/

include_once('common.inc.php');


$thinkedit->user->logout();


$url = $thinkedit->newUrl();

$url->redirect('login.php');

?>
