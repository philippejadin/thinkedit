<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $content->getTitle(); ?></title>
<meta name="generator" content="Thinkedit" />

<link href="<?php echo te_design() ?>/style.css" rel="stylesheet" type="text/css" media="all">


<link rel="stylesheet" href="<?php echo ROOT_URL ?>/lib/star-light/star-light.css" type="text/css"/>


</head>

<body>

<div id="container">
	<div id="header">
		<div id="logo"><a href="<?php echo te_root_link() ?>"><img src="<?php echo te_design() ?>/sources/logo.gif" alt="" width="167" height="55" border="0"></a></div>
		<div id="menu">
		<?php if ($main_menu = te_main_menu()) : ?>
				<?php foreach ($main_menu as $main_menu_item): ?>
				<a href="<?php echo te_link($main_menu_item->node);?>"><?php echo $main_menu_item->getTitle(); ?></a> 
				<?php if (!$main_menu_item->isEnd()):?><span class="menu_separator"></span><?php endif; ?>
				<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
<div id="submenu">
		<?php if ($context_menu = te_context_menu()) : ?>
				<?php foreach ($context_menu as $context_menu_item): ?>
					
					<?php if ($context_menu_item->node->getLevel() == 3): ?>
					<div style="margin-left: 15px">
					<?php endif; ?>
					
					<?php if ($context_menu_item->isCurrent()): ?>
					<span class="submenu_current">
					<?php endif; ?>
					
				<a href="<?php echo te_link($context_menu_item->node);?>"><?php echo te_short($context_menu_item->getTitle(), 25); ?></a><br/>
					
					<?php if ($context_menu_item->isCurrent()): ?>
					</span>
					<?php endif; ?>
				
					<?php if ($context_menu_item->node->getLevel() == 3): ?>
					</div>
					<?php endif; ?>
					
				<?php endforeach; ?>
		<?php endif; ?>
	
</div>
<div id="content">

<?php if ($content->isMultilingual()): ?>
<div class="locale">
	<?php echo te_locale_chooser(); ?>
</div>
<?php endif; ?>

<div class="title">
	<?php echo $content->getTitle(); ?>
</div>



	<div id="breadcrumb">
		<?php if ($breadcrumb_menu = te_breadcrumb_menu()) : ?>
				<?php foreach ($breadcrumb_menu as $breadcrumb_menu_item): ?>
				<a href="<?php echo te_link($breadcrumb_menu_item->node);?>"><?php echo $breadcrumb_menu_item->getTitle(); ?></a> 
				<?php if (!$breadcrumb_menu_item->isEnd()):?> > <?php endif; ?>
				<?php endforeach; ?>
		<?php endif; ?>
		</div>




