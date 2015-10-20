<?php if (isset ($out['nodes']) && is_array ($out['nodes'])): ?>
<?php foreach ($out['nodes'] as $node): ?>

<?php if ($node['haschildren']) : ?>
<li class="node closed" id="node_<?php echo $node['id']?>" haschildren="<?php echo $node['haschildren'] ?>">
<?php else: ?>
<li class="node" id="node_<?php echo $node['id']?>" haschildren="<?php echo $node['haschildren'] ?>">
<?php endif; ?>

<img src="<?php echo $node['icon']?>" class="icon">
<span class="node_title"><?php echo $node['title']?></span>


<?php /******************* Tools *******************/ ?>

<span class="tools">

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
<?php if ($node['published']): ?>
<a href="<?php echo $node['publish_url']?>" class="published">
<img src="ressource/image/icon/small/lightbulb.png" title="<?php echo  $node['publish_title'];?>">
<?php else: ?>
<a href="<?php echo $node['publish_url']?>" class="unpublished">
<img src="ressource/image/icon/small/lightbulb_off.png" title="<?php echo  $node['publish_title'];?>">
<?php endif; ?>
</a>
<?php endif; ?>


<!--
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
-->


</span>



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
<a class="cut_button">
<?php echo translate('cut');?>
</a>
</div>

<div class="context_menu_item">
<a class="copy_button">
<?php echo translate('copy');?>
</a>
</div>

<div class="context_menu_item">
<a class="paste_button">
<?php echo translate('paste');?>
</a>
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


</li>


<?php endforeach;?>
<?php endif; ?>

