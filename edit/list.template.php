<div class="content panel">

<?php /****************** Pagination ***************/ ?>

<?php if (isset($out['pagination'])): ?>
<!--
Num of pages : <?php echo $out['pagination']['num_of_pages'] ?>
/ 
Current page : <?php echo $out['pagination']['current_page'] ?>
-->
<?php foreach ($out['pagination'] as $pagination) : ?>
<?php if (isset($pagination['current'])): ?>
<div class="pagination on">
<?php echo $pagination['title'] ?>
</div>
<?php else: ?>
<div class="pagination off">
<a class="number" href="<?php echo $pagination['url'] ?>">
<?php echo $pagination['title'] ?>
</div>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>


<table class="list">
<tr>
<?php /****************** Thumbnails / icons ***************/ ?>
<?php if (isset($out['enable_thumbnails'])) : ?>
<th class="table_header">
</th>
<?php endif; ?>


<?php /****************** Table header ***************/ ?>

<?php if (isset($out['field'])): ?>
<?php foreach ($out['field'] as $key=>$field) : ?>


<?php /****************** Sorting ***************/ ?>
<?php if (isset ($out['sort_field']) && $key==$out['sort_field']): ?>
<th class="on">
<?php echo $field['title']; ?>

<?php else: ?>
<th class="off">
<?php if (isset($field['sortable'])) : ?>
<a href="<?php echo $field['sort_url']; ?>">
<?php echo $field['title']; ?>
</a>
<?php endif; ?>

<?php endif; ?>


</th>

<?php endforeach; ?>
<?php endif; ?>



<?php /****************** TOOLS ***************/ ?>

<th>
<?php echo translate('tool_row'); ?></th>


<?php /****************** Order of items ***************/ ?>
<?php if (isset($out['enable_sort'])) : ?>
<th>
<?php echo translate('sort_row'); ?>
</th>
<?php endif; ?>

</tr>

<?php  $i=0 ?>

<?php /****************** Data loop ***************/ ?>
<?php if (isset($out['data'])): ?>
<?php foreach ($out['data'] as $id=>$data) : ?>
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


<tr class="<?php echo $class?>">

<!-- thumbnails -->
<?php if (isset($out['enable_thumbnails'])) : ?>
<td>
<img src="<?php echo $data['icon']?>"></td>
<?php endif; ?>



<?php 
// little trick, we use the field list instead of the raw datas, so only needed data from the db is displayed
// also, the columns are synced with the headers.
?> 

<?php foreach ($out['field'] as $key=>$field) : ?>
<td>
<a href="<?php echo $data['edit_url']?>">
<?php echo $data['field'][$key]; ?>
</a>
</td>
<?php endforeach; ?>



<!-- tools follow : -->
<td>
<?php if (isset($out['mode']) && $out['mode'] == 'relation'): ?>
<a class="action_url" href="<?php echo $data['relate_url']?>">
<?php echo translate('make_relation'); ?>
</a>
<?php endif; ?>

<a href="<?php echo $data['edit_url']?>">
<img src="ressource/image/icon/small/accessories-text-editor.png" border="0" alt="<?php echo translate('edit'); ?>">
</a>

<a href="<?php echo $data['delete_url']?>" onClick="JavaScript:confirm_link('<?php echo translate('confirm_delete') ?>', '<?php echo $data['delete_url']?>'); return false;">
<img src="ressource/image/icon/small/edit-delete.png" border="0"></a>


<?php if (isset ($out['plugins'])) : ?>

<?php foreach($out['plugins'] as $plugin) : ?>

<?php if ($plugin['use']['tool_row'] == 'true') : ?>
<a target="_blank" href="<?php echo $plugin['plugin_file'] ?>?table=<?php echo $out['table']?>&db_locale=<?php echo get_preferred_locale() ?>">
<img src="icons/<?php echo $plugin['icon']?>"></a>
<?php endif; ?>

<?php endforeach; ?>

<?php endif; ?>

</td>



</td>


<?php if (isset($out['enable_sort'])) : ?>
<td align="center" valign="middle">

<a href="change_order.php?id=<?php echo $data[$preferred_locale]['id']?>&table=<?php echo $out['table']?>&action=move_top">
<img src="./icons/order_top.gif"></a>
<img src="./icons/pixel.gif" width="1" height="1">
<a href="change_order.php?id=<?php echo $data[$preferred_locale]['id']?>&table=<?php echo $out['table']?>&action=move_up">
<img src="./icons/order_up.gif"></a>
<img src="./icons/pixel.gif" width="1" height="1">
<a href="change_order.php?id=<?php echo $data[$preferred_locale]['id']?>&table=<?php echo $out['table']?>&action=move_down">
<img src="./icons/order_down.gif"></a>
<img src="./icons/pixel.gif" width="1" height="1">
<a href="change_order.php?id=<?php echo $data[$preferred_locale]['id']?>&table=<?php echo $out['table']?>&action=move_bottom">
<img src="./icons/order_bottom.gif"></a>
<?php // echo $data[$preferred_locale]['order_by']?>

</td>
<?php endif; ?>



</tr>
<?php endforeach; ?>

<?php /****************** if no data ***************/ ?>

<?php else: ?>
<tr>
<td>
<?php echo translate('nothing_in_list') ?></td>
</tr>
<?php endif; ?>
</tbody>
</table>

<br>

<?php /****************** global tools ***************/ ?>


<?php if (isset($out['global_action'])): ?>
<table border="0" cellspacing="0" cellpadding="0" class="power_list_tools_table">
<?php foreach ($out['global_action'] as $action): ?>
<th class="action_button">
<a class="white_link" href="<?php echo $action['url']?>"><?php echo $action['title']?></a>
</th>
<?php endforeach; ?>
</table>
<?php endif; ?>


</div>

