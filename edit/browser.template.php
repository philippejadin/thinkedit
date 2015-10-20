<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
  <head>

    <meta http-equiv="content-type" content="text/html;charset=UTF8" />

    <meta name="generator" content="Thinkedit">

    <title>
      <?php echo translate('image_browser_title') ?>
    </title>

		<link type="text/css" href="style.css" rel="stylesheet"
    media="screen">	
    <link type="text/css" href="style2.css" rel="stylesheet"
    media="screen">	
		
<script src="thinkedit.js" type="text/javascript"></script>


<!--[if lt IE 7.]>
<script defer type="text/javascript" src="pngfix.js"></script>
<![endif]-->

		
</head>



<body>


	
<div class="image_browser_banner">
<table cellpadding="0" border="0" cellspacing="0">
<tr>
<td>
<H2><?php echo translate('image_browser_title') ?></H2>
<p><H3><?php echo translate('image_browser_help_select') ?></p></H3>
</td>
<td>
<img class="module_image" src="ressource/icon_browser.jpg">
</td>
</tr>
</table>
</div>

<div class="image_browser_content">

<?php if (isset($out['info'])): ?>
<?php echo $out['info'] ?>
<?php endif; ?>


<br/>
	
	
	
	
	
<div>
<?php if (isset($out['dropdown'])) : ?>
		<?php foreach ($out['dropdown'] as $dropdown): ?>
				<select size="1" onChange="jump('document',this,0)">
				<option value=""><?php echo translate('drop_down_choose')?>...</option>		
				<?php foreach ($dropdown['data'] as $data): ?>
						
						<?php 
						if (isset ($data['selected']))
						{
								$selected="selected";
						}
						else
						{
								$selected="";
						} 
						?>
						
						<option value="<?php echo $data['url'] ?>" <?php echo $selected ?> ><?php echo $data['title'] ?></option>
						
						<?php endforeach; ?>
				</select>
		<?php endforeach; ?>

<?php endif; ?>
</div>



<?php if (isset($out['parent_url'])): ?>
<div style="margin-top: 20px; margin-bottom: 20px">
<a href="<?php echo $out['parent_url']; ?>" class="action_button"><?php echo translate('go_up'); ?></a>
</div>
<?php endif; ?>



<div class="image_browser_margin">


<table class="image_browser_table" cellspacing="0" cellpadding="0">

<?php if (isset($out['items'])): ?>
<?php $i=0; ?>
<?php foreach ($out['items'] as $item) : ?>

<?php
// used to alternate rows, of course)
if (($i % 2)==0)
{
$class = "tr_off_browser";
}
else
{
$class = "tr_on_browser";
}

$i++;
?>

<tr class="<?php echo $class?>">

<td class="td_browser">

<img src="<?php echo $item['icon'] ?>">

</td>

<td>
<?php if (isset($item['visit_url'])): ?>
	<a href="<?php echo $item['visit_url'] ?>"><?php echo $item['title'] ?></a>
<?php else: ?>
	<?php echo $item['title'] ?>
<?php endif; ?>
</td>

<td>
<?php if (isset($out['mode']) && $out['mode'] == 'relation'): ?>
<a class="action_button" href="javascript:to_opener('<?php echo $item['url'] ?>')">
<img src="ressource/image/icon/small/list-add.png">
<?php echo translate('relate')?>
</a>
<?php endif; ?>

<?php if (isset($out['mode']) && $out['mode'] == 'field'): ?>
<a class="action_button" href="javascript:to_opener_field('<?php echo $item['field'] ?>', '<?php echo $item['value'] ?>')"><?php echo translate('choose')?></a>
<?php endif; ?>

</td>


</tr>
<?php endforeach; ?>
<?php else: ?>
<?php echo translate('nothing_in_list') ?>
<?php endif; ?>

</table>
</div>

<?php if (isset ($out['enable_upload'])) : ?>

<div class="toolbar">
<div class="panel">
<form action="<?php echo $out['upload_file_url']?>" enctype="multipart/form-data" method="post">
<input type="file" name="uploaded_file" class="action_button" size="30">
<button class="action_button" type="submit"><?php echo translate('upload_file_button') ?></button>
</form>
</div>

<div class="panel">
<form action="<?php echo $out['add_folder_url']?>" method="post">
<input type="text" name="folder_name"  size="30">
<button class="action_button" type="submit"><?php echo translate('create_folder_button') ?></button>
</form>
</div>

</div>
<?php endif; ?>

</div>
</html>
