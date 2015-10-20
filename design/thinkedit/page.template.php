<?php echo $content->field['body']->getFiltered(); ?>


<?php $children =  $node->getChildren(); ?>

<?php if ($children) : ?>

<br/><hr/>
		
<div class="content_text">
		<?php foreach ($children as $child): ?>
				<?php
				$sub_content = $child->getContent();
				$sub_content->load();
				?>
				
				<?php if ($sub_content->isUsedIn('navigation')): ?>
				
				<h1>
				<a href="<?php echo te_link($child);?>">
				<?php echo $sub_content->getTitle(); ?>
				</a>
				</h1>
				
				
				<div class="child_intro">
				<a href="<?php echo te_link($child);?>">
				<?php if ($sub_content->get('intro')): ?>
				<?php echo te_short($sub_content->get('intro'), 200); ?>
				<?php else: ?>
				<?php echo te_short($sub_content->get('body'), 200); ?>
				<?php endif; ?>
				</div>
				</a>
				<br/>
				
				
				
				<?php endif; ?>
				
		<?php endforeach; ?>
		</div>
<?php endif;?>



<?php
$relation_object = $thinkedit->newRelation();
$relations = $relation_object->getRelations($content);
?>

<?php if ($relations): ?>
<hr/>
<?php foreach ($relations as $relation): ?>
<li><a href="<?php echo te_link($relation)?>"><?php echo $relation->getTitle();?></a></li> 
<?php endforeach; ?>
<?php endif; ?>
