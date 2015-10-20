<div class="login panel">

<div class="login_container">

<div class="spacer">
 &nbsp;
</div>

<div class="intro">
<h1>
<?php echo translate('login_welcome_title') ?>
</h1>
<p>
<?php echo translate('login_welcome_message') ?>
</p>

</div>


<div class="form">
<form action="login.php" method="post">
<?php echo translate('login') ?> :		
<br/>
<input type="text" name="login" value="">
<br/>
<br/>
<?php echo translate('password') ?> :
<br/>
<input type="password" name="password" value="">
<br/>
<br/>
<input type="submit" class="action_button" value="<?php echo translate('sign_in') ?>">
</form>
</div>

<div class="spacer">
 &nbsp;
</div>

</div>

</div>

