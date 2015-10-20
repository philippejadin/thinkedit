<html>
<head>
<link type="text/css" href="../edit/ressource/css/style.css" rel="stylesheet" media="screen"/>
<style>

body
{
		font-family: verdana, arial, helvetica, sans-serif;
		font-size : 10px;
		background-color: #F6F6F6; 
}

.content a
{
		text-decoration: underline;
		font-weight: bold;
}

h1, h2
{
		font-family: verdana, arial, sans;
		font-weight: normal;
}

h1
{
		font-size: 2em;
}

h2
{
		font-size: 1em;
		font-weight: bold;
		
}

.content
{
	width: 60em;
	padding: 15px;
}

.title
{
	padding: 15px;
	background-color: #aaa;
}

</style>
</head>
<body>
<div class="thinkedit">

<div class="header panel">
<a href="index.php"><img src="../edit/ressource/image/general/thinkedit_logo.gif" alt="" border="0"/></a>	
</div>

<div class="title panel">
<h1>
Thinkedit installation wizard
</h1>
</div>


<?php if (isset($out['info'])): ?>
<div class="info panel"><?php echo $out['info']?></div>
<?php endif; ?>



<div class="content panel">







<?php if (isset($out['title'])): ?>
<h2><?php echo $out['title']?></h2>
<?php endif; ?>

<?php if (isset($out['help'])): ?>
<p><?php echo $out['help']?></p>
<?php endif; ?>

<?php if (isset($out['content'])): ?>
<p><?php echo $out['content']?></p>
<?php else: ?>
<a href="">Go to next step</a>
<?php endif; ?>
</div>


<div class="footer">
			<a href="http://www.thinkedit.org">&copy; THINKEDIT.ORG open source CMS</a>
</div>


</div>

</body>

</html>
