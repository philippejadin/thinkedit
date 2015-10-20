<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>

<!--[if lt IE 7.]>
<script defer type="text/javascript" src="pngfix.js"></script>
<![endif]-->

<meta name="generator" content="Thinkedit.org">

<title>
</title>

<link type="text/css" href="<?php echo ROOT_URL?>/edit/ressource/css/style.css" rel="stylesheet" media="screen"/>

<?php echo te_jquery(); ?>
<script src="thinkedit.js" type="text/javascript"></script>



</head>
<body onLoad="if (parent.adjustIFrameSize) parent.adjustIFrameSize(window);" class="gray">
<div style="margin: 5px" class="gray">

<table class="power_table">

<tr>
<th class="table_header"><?php echo translate('icon')?></th>
<th class="table_header"><?php echo translate('title')?></th>
<th class="table_header"><?php echo translate('tools')?></th>
</tr>
<?php  $i=0 ?>



<?php if (isset($out['relation']['data'])): ?>


<?php foreach ($out['relation']['data'] as $item): ?>
<?php 
// used to alternate rows, of course)
if (($i % 2)==0)
{
		$class = "tr_off";
}
else
{
		$class = "tr_on";
}
$i++;
?>

<tr class="<?php echo $class?>">

<td class="power_cell power_cell_border"><img src="<?php echo $item['icon']?>"/></td>
<td class="power_cell power_cell_border"><?php echo te_short($item['title'], 30)?></td>
<td class="power_cell power_cell_border">
<a class="action_button" href="<?php echo $item['remove_url']?>">
<img src="ressource/image/icon/small/list-remove.png">
<?php echo translate('unrelate') ?>
</a>
</td>

</tr>

<?php endforeach; ?>



<?php else: ?>
<tr>
<td>
<?php echo translate('no_relations'); ?>
</td>
</tr>
<?php endif; ?>

</table>


<div style="margin-top:20px">
<a class="action_button" href="<?php echo $out['browse_url']?>" target="_blank" onClick="popup('<?php echo $out['browse_url']?>', 'relation_browser');return false"><?php echo translate('add_relation') ?></a>
</div>

</div>

</body>
</html>