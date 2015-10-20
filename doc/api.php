<?php
//die();

// todo : filename sorting

echo '<h1>Class methods</h1>';

if ($handle = opendir('../class/')) 
{
	
	
	/* This is the correct way to loop over the directory. */
	while (false !== ($file = readdir($handle))) 
	{
		if (strstr($file, '.class.'))
		{
			echo "<h2>$file</h2>";
			$content = file_get_contents('../class/' . $file);
			
			
			$functions = spliti('function', $content);
			
			
			
			foreach ($functions as $function)
			{
				$lines = split("\n",$function );
				if (strstr($lines[1], '{'))
					{
						echo '<li>';
						//echo 'function ';
						echo ($lines[0]);
					}
			}
		}
	}
	
	
}	


?>
