<?php if (isset($out['js'])): ?>
<?php echo $out['js'] ?>
<?php endif; ?>




<div class="content">

<div class="edit panel edit_panel">

<?php if (isset($out['error'])) :?>
<div class="error"><?php echo translate('error') ?> : <?php echo $out['error'] ?></div>
<?php endif;?>


<form name="edit_form" action="<?php echo $out['save_url']?>" method="post" onsubmit="return submitForm();">			



<?php /****************** Field rendering ***********/ ?>
<?php if (isset($out['field'])): ?>

<?php foreach ($out['field'] as $field): ?>

<div class="field">

<?php if ($field['title']): ?>

<?php if ($field['help']): ?>
<div class="field_title" title="<?php echo $field['help']; ?>" style="cursor:help">
<?php else: ?>
<div class="field_title">
<?php endif; ?>


<?php echo $field['title']; ?> :
</div>
<?php endif; ?>

<!--
<?php if ($field['help']): ?>
<div class="field_help">
<?php echo $field['help']; ?>
</div>
<?php endif; ?>
-->

<div class="field_ui">
<?php echo $field['ui']; ?>
</div>

</div>
<?php endforeach; ?>

<?php endif; ?>


<?php /****************** Node properties ***********/ ?>

<?php if (isset($out['node_field'])): ?>

<?php foreach ($out['node_field'] as $field): ?>
<div class="field">


<?php if ($field['title']): ?>
<div class="field_title">
<?php echo $field['title']; ?> :
</div>
<?php endif; ?>

<?php if ($field['help']): ?>
<div class="field_help">
<?php echo $field['help']; ?>
</div>
<?php endif; ?>

<div class="field_ui">
<?php echo $field['ui']; ?>
</div>

</div>
<?php endforeach; ?>
<?php endif; ?>


<?php /****************** Relations ***********/ ?>

<?php if (isset($out['relation'])) : ?>

<div class="field">

<div class="field_title">
<?php echo ucfirst(translate('relation'));?>
</div>

<div class="field_ui">
<iframe src="<?php echo $out['relation']['url']?>" name="relation" id="relation" width="500" height="20" frameborder="0"></iframe>
</div>


</div>

<?php endif; ?>



<?php /****************** Locations ***********/ ?>

<!--
<fieldset>
<legend><?php echo ucfirst(translate('locations'));?></legend>
blablabla
</fieldset>
-->

</table>



<?php /****************** Save buttons ***********/ ?>


<div class="save_toolbar inset panel">
<?php if (!isset($out['edit_node'])) : ?>
<input class="action_button" type="submit" value="<?php echo translate('save_button') ?>" name="save">
<input class="action_button" type="submit" value="<?php echo translate('save_and_return_to_list_button') ?>" name="save_and_return_to_list">
<?php else: ?>

<button class="edit_action_button" type="submit" value="<?php echo translate('save') ?>" name="save">
<img src="ressource/image/icon/ok.png"/>
<?php echo translate('save') ?>
</button>

<button class="edit_action_button" type="submit" value="<?php echo translate('cancel') ?>" name="cancel_and_return_to_structure">
<img src="ressource/image/icon/cancel.png"/>
<?php echo translate('cancel') ?>
</button>

<?php endif; ?>
</div>


</form>

<br/>
<br/>
<br/>

</div>

</div>

