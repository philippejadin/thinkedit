<div class="content">

<?php if (is_array($out['item'])): ?>

<div class="spacer"></div>

<?php foreach ($out['item'] as $item): ?>
<div class="main_item">
<a href="<?php echo $item['action']?>">


	<?php if (isset($item['icon'])):?>
<div class="main_item_icon">
	<img src="<?php echo($item['icon']) ?>">
</div>
	<?php endif; ?>
<div class="main_item_text">
	<div class="main_item_title">
	<?php echo $item['title']?>
	</div>
	<?php echo $item['help']?>
</div>

</a>
</div>
<?php endforeach; ?>

<div class="spacer">
 &nbsp;
</div>

<?php endif; ?>

</div>