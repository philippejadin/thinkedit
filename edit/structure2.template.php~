<script type="text/javascript" src="<?php echo ROOT_URL?>/lib/jquery/jquery.js"></script>				
<script type="text/javascript" src="structure.js"></script>


<div id="loader">Loading ...</div>


<div class="node" id="1">Title</div>















<div class="content panel" id="list">

<iframe name="status" width="500" height="20" frameborder="0" scrolling="no"></iframe>


<?php /*************************** Top toolbar *****************************/?>

<div class="toolbar">

<!--
<?php if (isset($out['structure_breadcrumb'])) : ?>
<select size="1" onChange="jump('parent',this,0)">
<?php foreach ($out['structure_breadcrumb'] as $i=>$bread): ?>
<option value="<?php echo $bread['url'] ?>" <?php if (isset($bread['current'])): ?>selected="selected"<?php endif; ?>>
<?php //echo str_repeat('&nbsp;&nbsp;', $i); ?>
<?php echo ucfirst($bread['title']) ?>
</option>
<?php endforeach; ?>
</select>
<?php endif; ?>
-->


<?php if (isset($out['go_up_url'])): ?>
<a class="action_button" style="margin-bottom: 30px;" href="<?php echo $out['go_up_url'] ?>"><?php echo translate('go_up')?></a>
<?php endif;?>
</div>

<?php if (isset($out['structure_breadcrumb'])) : ?>
<?php $x=1 ?>

<?php foreach ($out['structure_breadcrumb'] as $i=>$bread): ?>
<a href="<?php echo $bread['url'] ?>">
<?php echo ucfirst($bread['title']) ?>

<?php if ($x < count($out['structure_breadcrumb'])): ?>
 > 
<?php endif; ?>

<?php $x++; ?>

<?php endforeach; ?>
<?php endif; ?>



<?php /*************************** List *****************************/?>

<?php if (isset($out['nodes'])) : ?>

<table class="list">
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

if (isset($node['is_current']))
{
		$class = "tr_hilight";
}

$i++;
?>

<tr class="<?php echo $class?>" id="node_<?php echo $node['id']?>" oncontextmenu="showContextMenu('context_menu_node_<?php echo $node['id']?>', event);return false;">


<td <?php if (isset($node['visit_url'])): ?>style="cursor:pointer" onClick="document.location.href='<?php echo $node['visit_url']?>';"<?php endif;?>>

<?php if (isset($node['visit_url'])): ?>
<a href="<?php echo $node['visit_url']?>" title="<?php echo  $node['full_title'] ?>">
<?php endif;?>


<?php if (isset($node['helper_icon'])): ?>
<img src="<?php echo $node['helper_icon']; ?>" style="vertical-align: middle;">
<?php endif; ?>

<img src="<?php echo $node['icon']; ?>" style="vertical-align: middle;">
<?php echo  $node['title'] ?>

<?php if (isset($node['visit_url'])): ?>
</a>
<?php endif;?>

</td>


<td>


<?php if (isset($node['edit_url'])): ?>
<a class="action_button" href="<?php echo $node['edit_url']?>" onclick="custompopup('<?php echo $node['edit_url']?>', 'editor' , 80);return false">
<!--
<img src="ressource/image/icon/small/accessories-text-editor.png" border="0" alt="<?php echo translate('node_edit'); ?>">
-->
<?php echo translate('edit'); ?>
</a>
<?php endif; ?>

<!--
<a class="action_button" onclick="showContextMenu('context_menu_node_<?php echo $node['id']?>', event);return false;">
<?php echo translate('menu');?>
</a>
-->

<!--
<a class="action_button" onclick="toggle_and_move('add_menu_node_<?php echo $node['id']?>', event)">
<?php echo translate('add');?>
</a>
-->


<?php if (isset($node['publish_url'])): ?>
<a href="<?php echo $node['publish_url']?>">
<?php if ($node['published']): ?>
<img src="ressource/image/icon/small/lightbulb.png" title="<?php echo  $node['publish_title'];?>">
<?php else: ?>
<img src="ressource/image/icon/small/lightbulb_off.png" title="<?php echo  $node['publish_title'];?>">
<?php endif; ?>
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


<?php /******************* Context menu *******************/ ?>

<div class="context_menu" id="context_menu_node_<?php echo $node['id']?>" style="display:none">

<?php /******************* Actions *******************/ ?>
<div class="context_menu_title"><?php echo translate('actions');?></div>

<?php if (isset($node['edit_url'])): ?>
<div class="context_menu_item">
<a href="<?php echo $node['edit_url']?>" onclick="custompopup('<?php echo $node['edit_url']?>', 'editor' , 80);return false">
<img src="ressource/image/icon/small/accessories-text-editor.png" border="0" alt="<?php echo translate('node_edit'); ?>">
<?php echo translate('edit'); ?>
</a>
</div>
<?php endif; ?>

<?php if (isset($node['delete_url'])): ?>
<div class="context_menu_item">
<a href="<?php echo $node['delete_url']?>" onClick="JavaScript:confirm_link('<?php echo translate('confirm_node_delete') ?>', '<?php echo $node['delete_url']?>'); return false;">
<img src="ressource/image/icon/small/user-trash-full.png" title="<?php echo translate('delete'); ?>">
<?php echo translate('delete')?>
</a>
</div>
<?php endif; ?>


<?php if (isset($node['preview_url'])): ?>
<div class="context_menu_item">
<a href="<?php echo $node['preview_url']?>" target="thinkedit_preview">
<?php echo  $node['preview_title'];?>
</a>
</div>
<?php endif; ?>



<hr/>

<?php /******************* Clipboard *******************/ ?>

<div class="context_menu_title"><?php echo translate('clipboard');?></div>
<div class="context_menu_item">
<a href="<?php echo $node['clipboard']['cut_link']?>" target="status" onclick="hide_menus()">
<?php echo translate('cut');?>
</a>
</div>
<!--
<div class="context_menu_item">
<a href="clipboard.php" target="status"><?php echo translate('copy');?></a>
</div>
-->

<div class="context_menu_item">
<a href="<?php echo $node['clipboard']['paste_link']?>" target="status" onclick="hide_menus()"><?php echo translate('paste');?></a>
</div>


<?php if (isset($node['locale'])) : ?>

<hr/>

<?php /******************* Translate *******************/ ?>

<div class="context_menu_title"><?php echo translate('translate');?></div>
<?php foreach ($node['locale'] as $locale_info): ?>
<div class="context_menu_item">
<a href="<?php echo $locale_info['edit_url']?>"><?php echo $locale_info['locale']?></a>
</div>
<?php endforeach; ?>
<?php endif; ?>


</div>




<!--
<?php /******************* Add subitems *******************/ ?>
<div class="context_menu" id="add_menu_node_<?php echo $node['id']?>" style="display:none">

<div class="context_menu_title"><?php echo translate('node_add_new');?></div>

<?php if (isset($node['allowed_items'])) : ?>
<?php foreach ($node['allowed_items'] as $item): ?>
<div class="context_menu_item">
<a href="<?php echo $item['action'] ?>">
<?php echo ucfirst($item['title']) ?>
</a>
</div>
<?php endforeach; ?>
<?php else: ?>
<?php echo translate('cannot_add_here');?>
<?php endif; ?>
</div>
-->


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


