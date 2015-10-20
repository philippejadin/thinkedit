<div class="content">

<h1><?php echo $content->getTitle() ?></h1>

<p>
This is the default template for showing content.
</p>


<?php foreach ($content->field as $field): ?>
<p>
<strong><?php echo $field->getTitle() ?> :</strong>
<br/>
<?php echo $field->get() ?>
</p>
<?php endforeach; ?>

</div>
