<h1><?php echo $content->getTitle() ?></h1>


<?php if ($node->hasChildren()) : ?>
		<?php foreach ($node->getChildren() as $child): ?>
		<?php
		$sub_content = $child->getContent();
		$sub_content->load();
		?>
		
		<h2><?php echo $sub_content->getTitle(); ?></h2>
		<p>
		Explication de la sous section ici...
		</p>
		
		<?php endforeach; ?>
<?php else: ?>
Il n'y a rien ici ...
<?php endif; ?>


