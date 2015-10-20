<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
		
		<title>
		<?php if (isset($out['title'])): ?>
		<?php echo $out['title']; ?>
		<?php else: ?>
    Thinkedit
		<?php endif; ?>
		</title>

    <link type="text/css" href="<?php echo ROOT_URL?>/edit/ressource/css/style.css" rel="stylesheet" media="screen"/>
		
	<!--
	<script type="text/javascript" src="<?php echo ROOT_URL?>/lib/prototype/prototype.js"></script>
	-->
	
   	<?php echo te_jquery(); ?>	

	<!--[if lt IE 7.]>
	<script defer type="text/javascript" src="pngfix.js"></script>
	<![endif]-->
</head>
	
<body>
<div class="thinkedit">

<div class="te_header_main">
<a href="main.php"><img src="ressource/image/general/thinkedit_logo.gif" alt="" border="0"/></a>	
</div>

			
<?php if (isset($out['error'])) : ?>
<div class="error panel">
<img src="ressource/image/icon/error.gif"/>
<?php echo translate('error') ?> - </b><?php echo $out['error'] ?>
</div>
<?php endif; ?>

<div  class="error panel" id="error" style="display: none">
<img src="ressource/image/icon/error.gif"/>
</div>

<div  class="info panel"id="info" style="display: none">
<img src="ressource/image/icon/info.gif">
</div>

<?php if (isset($out['info'])) : ?>
<div class="info panel">
<img src="ressource/image/icon/info.gif">
<?php echo translate('info') ?> - </b><?php echo $out['info'] ?>
</div>
<?php endif; ?>

<div class="loading panel" id="loading">
<img src="ressource/image/icon/loading_bar.gif">
<?php echo translate('loading_in_progress') ?>
</div>


<div class="breadcrumb">
<?php include ('breadcrumb.template.php') ?>
</div>