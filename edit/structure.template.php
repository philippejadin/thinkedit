<script type="text/javascript" src="structure.js"></script>	



<div class="content" id="list">

<?php /*************************** List *****************************/?>


<?php if (isset($out['nodes'])) : ?>

<table class="list">
<tr>
<th><?php echo translate('title');?></th>
<th width="30">Manage</th>
<th width="70">Add</th>
<th width="30">Translate</th>
<th width="10"></th>
<th width="100">Move</th>
</tr>


<?php /*************************** List *****************************/?>


<?php  $i=0 ?>
<?php foreach ($out['nodes'] as $node): ?>

<?php 
// used to alternate rows, of course)
if (($i % 2)==0)
{
		$class = "off";
}
else
{
		$class = "on";
}


$i++;
?>



<tr class="<?php echo $class?>" id="node_<?php echo $node['id']?>" oncontextmenu="showContextMenu('context_menu_node_<?php echo $node['id']?>', event);return false;">

<!--<td <?php if (isset($node['visit_url'])): ?>style="cursor:pointer" onClick="document.location.href='<?php echo $node['visit_url']?>';"<?php endif;?>>-->

<td>

<?php if (isset($node['visit_url'])): ?>
<a href="<?php echo $node['visit_url']?>" title="<?php echo translate('click_to_open_close'); ?> <?php echo  $node['full_title'] ?>">
<?php endif;?>

<?php echo str_repeat('<div class="tree_spacer">&nbsp;</div>', $node['level']); ?>

<?php if ($node['status'] == 'closed') : ?>
<img src="ressource/image/general/node_closed.gif" style="vertical-align: middle;">
<?php elseif ($node['status'] == 'opened') : ?>
<img src="ressource/image/general/node_opened.gif" style="vertical-align: middle;">
<?php else: ?>
<img src="ressource/image/general/node_empty.gif" style="vertical-align: middle;">
<?php endif; ?>


<?php if (isset($node['visit_url'])): ?>
</a>
<?php endif;?>


<?php if (isset($node['helper_icon'])): ?>
<img src="<?php echo $node['helper_icon']; ?>" style="vertical-align: middle;">
<?php endif; ?>

<img src="<?php echo $node['icon']; ?>" style="vertical-align: middle;">

<?php if (isset($node['edit_url'])): ?>
<a class="node_title" href="<?php echo $node['edit_url']?>" title="<?php echo translate('click_to_edit') ?>">
<?php endif; ?>

<?php echo  $node['title'] ?>

<?php if (isset($node['edit_url'])): ?>
</a>
<?php endif; ?>


</td>


<?php /*************************** Edit button *****************************/?>

<!--
<td>
<?php if (isset($node['edit_url'])): ?>

<div class="menu">
<a class="menu_button" href="<?php echo $node['edit_url']?>">
-->
<!--
<img src="ressource/image/icon/small/accessories-text-editor.png" border="0" alt="<?php echo translate('node_edit'); ?>">
-->
<!--
<?php echo translate('edit'); ?>
</a>
</div>
<?php endif; ?>
</td>
-->

<?php /*************************** Manage... *****************************/?>

<td>
<div class="menu">
<div class="menu_button"><?php echo translate('manage'); ?></div>
<div class="menu_items">

<div class="menu_item">
<img src="ressource/image/icon/editcut.png"/>
<a href="<?php echo $node['clipboard']['cut_link']?>">
<?php echo translate('cut');?>
</a>
</div>

<div class="menu_item">
<img src="ressource/image/icon/editcopy.png"/>
<a href="<?php echo $node['clipboard']['copy_link']?>">
<?php echo translate('copy');?>
</a>
</div>

<div class="menu_item">
<a href="<?php echo $node['clipboard']['paste_link']?>">
<img src="ressource/image/icon/editpaste.png"/>
<?php echo translate('paste');?>
</a>
</div>



<?php if (isset($node['delete_url'])): ?>
<div class="menu_item">
<a href="<?php echo $node['delete_url']?>" onClick="JavaScript:confirm_link('<?php echo translate('confirm_node_delete') ?>', '<?php echo $node['delete_url']?>'); return false;">
<img src="ressource/image/icon/small/user-trash-full.png" title="<?php echo translate('delete'); ?>">
<?php echo translate('delete')?>
</a>
</div>
<?php endif; ?>


<?php if (isset($node['preview_url'])): ?>
<div class="menu_item">
<a href="<?php echo $node['preview_url']?>" target="thinkedit_preview">
<img src="ressource/image/icon/system-search.png"/>
<?php echo  $node['preview_title'];?>
</a>
</div>
<?php endif; ?>
</div>
</div>

</td>


<?php /*************************** Add... *****************************/?>

<td>
<?php if (isset($node['allowed_items'])) : ?>

<div class="menu">

<div class="menu_button"><?php echo translate('node_add_new') ?></div>

<div class="menu_items">

<?php foreach ($node['allowed_items'] as $item): ?>
<div class="menu_item">
<a href="<?php echo $item['direct_add_action'] ?>" onClick="ask_title2('<?php echo translate('please_enter_title');?>', '<?php echo $item['direct_add_action'] ?>');return false">
<img src="<?php echo $item['icon'] ?>"/>
<?php echo ucfirst($item['title']) ?>
</a>
</div>
<?php endforeach; ?>
</div>
</div>

<?php endif; ?>
</td>


<td>
<?php if (isset($node['locale'])) : ?>
<?php /******************* Translate *******************/ ?>
<div class="menu">
<div class="menu_button"><?php echo translate('translate'); ?></div>
<div class="menu_items">
<?php foreach ($node['locale'] as $locale_info): ?>
<div class="context_menu_item">
<a href="<?php echo $locale_info['edit_url']?>"><?php echo $locale_info['locale']?></a>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</td>



<td style="text-align: center">
<?php /*************************** Publish... *****************************/?>

<?php if (isset($node['publish_url'])): ?>
<a href="<?php echo $node['publish_url']?>" class="structure_publish">
<?php if ($node['published']): ?>
<img src="ressource/image/icon/lamp.png" title="<?php echo  $node['publish_title'];?>">
<?php else: ?>
<img src="ressource/image/icon/lamp.png" title="<?php echo  $node['publish_title'];?>" class="disabled">
<?php endif; ?>
</a>
<?php endif; ?>
</td>


<td style="text-align: center">
<?php /*************************** Move... *****************************/?>
<?php if (isset($node['movetop_url'])): ?>
<a href="<?php echo $node['movetop_url']?>">
<img src="ressource/image/icon/small/go-top.png">
</a>
<?php endif; ?>

<?php if (isset($node['moveup_url'])): ?>
<a href="<?php echo $node['moveup_url']?>">
<img src="ressource/image/icon/small/go-up.png">
</a>
<?php endif; ?>

<?php if (isset($node['movedown_url'])): ?>
<a href="<?php echo $node['movedown_url']?>">
<img src="ressource/image/icon/small/go-down.png">
</a>
<?php endif; ?>

<?php if (isset($node['movebottom_url'])): ?>
<a href="<?php echo $node['movebottom_url']?>">
<img src="ressource/image/icon/small/go-bottom.png">
</a>
<?php endif; ?>

</td>


</tr>
<?php endforeach; ?>
</table>

<?php else: ?>
<div class="panel">
<?php echo translate('node_empty')?>
</div>
<?php endif; ?>


<?php /*************************** Bottom toolbar *****************************/?>


<div class="toolbar">

<?php if (isset($out['allowed_items'])) : ?>
<select size="1" onChange="ask_title('parent',this,1, '<?php echo translate('please_enter_title');?>')">
<option value=""><?php echo translate('node_add_new') ?></option>
<?php foreach ($out['allowed_items'] as $item): ?>
<option style="background-image: url('<?php echo $item['icon'] ?>'); background-repeat: no-repeat; padding-left: 20px; margin: 2px" value="<?php echo $item['direct_add_action'] ?>"><?php echo ucfirst($item['title']) ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>


<!--
<?php if (isset($out['allowed_items'])) : ?>
<select size="1" onChange="jump('parent',this,0)">
<option value=""><?php echo translate('node_add_new') ?></option>
<?php foreach ($out['allowed_items'] as $item): ?>
<option value="<?php echo $item['action'] ?>"><?php echo ucfirst($item['title']) ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>
-->

<?php if (isset($out['clipboard']['cut_link'])) : ?>
<a href="<?php echo $out['clipboard']['cut_link']?>" target="status" class="action_button">
<?php echo translate('cut');?>
</a>
<?php endif; ?>

<?php if (isset($out['clipboard']['paste_link'])) : ?>
<a href="<?php echo $out['clipboard']['paste_link']?>" target="status" class="action_button">
<?php echo translate('paste');?>
</a>
<?php endif; ?>



</div>


</div>


