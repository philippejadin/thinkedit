 <?php
 echo te_admin_toolbox(); // todo  
 ?>
 <?php
 // echo te_error_log(); // todo
 ?>

			
			<div class="tools">
			
			<div id="te_debug">
	  
			
			| 
			<?php 
			$db = $thinkedit->getDb();
			echo $db->getTotalQueries() ;
			?> queries.
			| 
			<?php
			$timer = $thinkedit->getTimer();
			echo $timer->render(); 
			?>
			elapsed time
			
			</div>

			</div>
			
			<?php
			if (function_exists('xdebug_dump_function_profile') && !$thinkedit->isInProduction())
			{
					//xdebug_dump_function_profile(4);
			}
			?>
			</div>
			
			
			
			<script src="thinkedit.js" type="text/javascript"></script>
			
		
			
			
			</body>
			</html>
