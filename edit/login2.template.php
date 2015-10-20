<html>
	<head>
		<title>Thinkedit : <?php echo translate('login_welcome_title') ?></title>
		<link href="<?php echo ROOT_URL?>/edit/ressource/css/toolbar.css" rel="stylesheet" type="text/css" media="all">
		<link type="text/css" href="<?php echo ROOT_URL?>/edit/ressource/css/login.css" rel="stylesheet" media="screen"/>
</head>

	<body>

		


<div id="global">
			<div id="container">
				<img class="logoEnter" src="<?php echo ROOT_URL?>/edit/ressource/image/general/thinkenter.gif" height="151px" width="388px">
				<form action="login.php" method="post">
					<div align="center">
						<?php echo translate('login') ?><br>
						<input type="text" name="login" size="24"><br>
						<?php echo translate('password') ?><br>
						<input type="password" name="password" size="24"><br>
						<input type="submit" class="action_button" value="<?php echo translate('sign_in') ?>">
				</form>
			</div>
		</div>
</div>

</body>
</html>
