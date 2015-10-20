<iframe name="status" width="500" height="30" frameborder="0" scrolling="no"></iframe>

<div class="content" id="list">

<div class="white bigbox">

<?php echo translate('you_are_here') ?> : 

<?php $x=1 ?>

<?php foreach ($out['structure_breadcrumb'] as $breadcrumb) : ?>

<a class="structure_breadcrumb" href="<?php echo $breadcrumb['url']  ?>"><?php echo $breadcrumb['title']  ?>  </a>

<?php if ($x < count($out['structure_breadcrumb'])): ?>
 > 
<?php endif;?>

<?php $x++ ?>
<?php endforeach; ?>


</div>




<div class="power_margin">



<?php //if (!$thinkedit->outputcache->start('interface_node_' . $current_node->getId())): ?>

<?php if (isset($out['go_up_url'])): ?>
<a class="" style="margin-bottom: 30px;" href="<?php echo $out['go_up_url'] ?>"><?php echo translate('go_up')?></a>
<?php endif;?>

<?php if (isset($out['nodes'])) : ?>

<table class="power_table" border="0" cellspacing="0" cellpadding="0">
<?php  $i=0 ?>

<?php foreach ($out['nodes'] as $node): ?>


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

if (isset($node['is_current']))
{
		$class = "tr_hilight";
}

$i++;
?>

<tr class="<?php echo $class?>" id="node_<?php echo $node['id']?>">


<td class="power_cell power_cell_border" style="cursor:pointer" onClick="document.location.href='<?php echo $node['url']?>';">
<a href="<?php echo $node['url']?>" title="<?php echo  $node['full_title'] ?>">
<div style="float: left; width: <?php echo ($node['level'] * 20)?>px; border: 0px solid black">&nbsp;</div>

<?php if (isset($node['helper_icon'])): ?>
<img src="<?php echo $node['helper_icon']; ?>" style="vertical-align: middle;">
<?php endif; ?>

<img src="<?php echo $node['icon']; ?>" style="vertical-align: middle;">
<?php echo  $node['title'] ?>
</a>
</td>


<td class="power_cell power_cell_border">

<a class="action_button" onclick="toggle_and_move('context_menu_node_<?php echo $node['id']?>', event)">
Menu
</a>
 


<?php if (isset($node['edit_url'])): ?>
<a class="action_button" href="<?php echo $node['edit_url']?>">
<img src="ressource/image/icon/small/accessories-text-editor.png" border="0" alt="<?php echo translate('node_edit'); ?>">
<?php echo translate('edit'); ?>
</a>
<?php endif; ?>



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


<?php if (isset($node['delete_url'])): ?>
<a href="<?php echo $node['delete_url']?>" onClick="JavaScript:confirm_link('<?php echo translate('confirm_node_delete') ?>', '<?php echo $node['delete_url']?>'); return false;">
<img src="ressource/image/icon/small/user-trash-full.png" title="<?php echo translate('delete'); ?>">
</a>
<?php endif; ?>


<?php if (isset($node['publish_url'])): ?>
<a href="<?php echo $node['publish_url']?>">
<?php if ($node['published']): ?>
<img src="ressource/image/icon/small/lightbulb.png" title="<?php echo  $node['publish_title'];?>">
<?php else: ?>
<img src="ressource/image/icon/small/lightbulb_off.png" title="<?php echo  $node['publish_title'];?>">
<?php endif; ?>
</a>
<?php endif; ?>


<?php if (isset($node['preview_url'])): ?>
<a class="action_button" href="<?php echo $node['preview_url']?>" target="_blank">
<?php echo  $node['preview_title'];?>
</a>
<?php endif; ?>


<?php if (isset($node['is_current'])): ?>
<?php if (isset($out['allowed_items'])) : ?>
<select size="1" onChange="jump('parent',this,0)">
<option value=""><?php echo translate('node_add_new') ?></option>
<?php foreach ($out['allowed_items'] as $item): ?>
<option value="<?php echo $item['action'] ?>"><?php echo ucfirst($item['title']) ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>
<?php endif; ?>


<?php if (isset($node['context_menu'])): ?>
<?php endif; ?>

<div class="context_menu" id="context_menu_node_<?php echo $node['id']?>" style="display:none">

<div class="context_menu_item">
<a href="" target="status">Cut</a>
</div>

<div class="context_menu_item">
<a href="" target="status">Copy</a>
</div>

<div class="context_menu_item">
<a href="" target="status">Paste</a>
</div>

<hr/>

<div class="context_menu_title">Translate</div>

<div class="context_menu_item">
<a href="" target="status">Français</a>
</div>

<div class="context_menu_item">
<a href="" target="status">English</a>
</div>


<hr/>

<div class="context_menu_title">Add</div>

<div class="context_menu_item">
<a href="" target="status">Page</a>
</div>

<div class="context_menu_item">
<a href="" target="status">Article</a>
</div>


</div>



</td>

</tr>
<?php endforeach; ?>

</table>
<?php else: ?>
<div class="white bigbox">
<?php echo translate('node_empty')?>
</div>
<?php endif; ?>


<?php //$thinkedit->outputcache->end();?>

<?php //endif; ?>

</div>




</div>
