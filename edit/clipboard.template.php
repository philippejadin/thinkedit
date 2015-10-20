<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF8" />
    <meta name="generator" content="Thinkedit">
    <link type="text/css" href="style.css" rel="stylesheet" media="screen">	
		<script src="thinkedit.js" type="text/javascript"></script>

		<!--[if lt IE 7.]>
		<script defer type="text/javascript" src="pngfix.js"></script>
		<![endif]-->
</head>
<body>

<?php if (isset($out['info'])): ?>
<div class="clipboard_info">
<?php echo $out['info'];?>
</div>
<?php endif; ?>


<?php if (isset($out['reload'])): ?>
<script>
window.parent.location.reload();
</script>
<?php endif; ?>


<?php if (isset($out['change_url'])): ?>
<script>
location.href = '<?php echo $out['change_url']?>';
</script>
<?php endif; ?>



</body>
</html>
