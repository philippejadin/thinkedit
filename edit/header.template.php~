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

    <link type="text/css" href="style2.css" rel="stylesheet" media="screen"/>
		
		
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="pngfix.js"></script>
<![endif]-->
</head>
	
<body onLoad="page_loaded()">
<div class="thinkedit">

<div class="header panel">
<a href="main.php"><img src="ressource/image/general/thinkedit_logo.gif" alt="" border="0"/></a>	
</div>

			
<?php if (isset($out['error'])) : ?>
<div class="error panel">
<img src="ressource/image/icon/error.gif"/>
<?php echo translate('error') ?> - </b><?php echo $out['error'] ?>
</div>
<?php endif; ?>


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

<!--
<?php if (isset($out['banner']['needed'])) : ?>
<div class="banner panel">
<img class="banner_image" src="<?php echo $out['banner']['image'] ?>"/>
<div class="banner_text">
<h1><?php echo $out['banner']['title'] ?></h1>
<?php echo $out['banner']['message'] ?>
</div>
</div>
<?php endif; ?>
-->



<div class="breadcrumb panel">
<?php include ('breadcrumb.template.php') ?>
</div>


