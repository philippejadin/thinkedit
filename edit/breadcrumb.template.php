<?php if (isset($out['breadcrumb']) && is_array($out['breadcrumb'])) : ?>


	<table width="*" border="0" cellspacing="0" cellpadding="0"><tr><td>
			
	<div class="breadcrumb_inside">
	

					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td bgcolor="white" width="1"></td>
						<td onClick="document.location.href='main.php'" style="cursor:pointer"><a class="breadcrumb" href="main.php"><img src="ressource/image/icon/small/home.gif" alt="" border="0"></a></td>
						<td><img src="ressource/image/general/bread_arrow.gif" alt="" width="10" height="15" border="0"></td>

<?php $x=1 ?>

<?php foreach ($out['breadcrumb'] as $breadcrumb) : ?>


<td style="cursor:pointer" onClick="document.location.href='<?php echo $breadcrumb['url']  ?>'">
<a class="breadcrumb" href="<?php echo $breadcrumb['url']  ?>" class="breadcrumb"><?php echo $breadcrumb['title']  ?>  </a>
</td>


<?php if ($x < count($out['breadcrumb'])): ?>
<td><img src="ressource/image/general/bread_arrow.gif" alt="" width="10" height="15" border="0"></td>
<?php endif;?>



<?php $x++ ?>

<?php endforeach; ?>

<td bgcolor="#b0afaf" width="1"></td>
</tr>

						<tr height="1">
							<td width="1" height="1"></td>
							<td colspan="6" bgcolor="#b0afaf" height="1"></td>
						</tr>
						
					</table>
			</div>
			
			</tr></td></table>
			


<?php endif; ?>