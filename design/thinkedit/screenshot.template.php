<?php $screen = $content->field['cover']->getFilesystem(); ?>

<?php if ($screen): ?>
<a href="<?php echo $screen->getUrl(); ?>">
<img src="<?php echo $screen->getThumbnail(array('w'=>300)); ?>"/>
</a>
<?php endif; ?>
