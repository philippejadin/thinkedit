<?php
include_once('common.inc.php');

//check_user
check_user();

// todo security : we should not whow config to anyone !

echo '<pre>';
print_r($thinkedit->config);

?>
