<?php
/**
A simple xml parser class (php arrays->xml) based on php's expat xml parser.

Limited feature set
(Un)suported intentionnaly :

- no attributes
- only one element of the same name at the same level
- if more than one element, use "id" attribute to differentiate them


*/

/**
* Used internally only
*/
class array2xml
{
  
		function convert($array)
		{
				return $this->parse($array);
		}
		
		function parse($array)
		{
				$i = 0;
				foreach ($array as $key=>$item)
				{
						if ($i == 0)
						{
								echo '< ' . $key . ' >' . '<br/>';
						}
						if (is_array($item))
						{
								 //. '< /' . $key . ' >' . '<br/>';
								$this->parse($item);
						}
						else
						{
								echo $item . '<br/>';
						}
						
						if ($i ==  count($array)-1)
						{
								echo '< /' . $key . ' >' . '<br/>';
						}
						
						$i++;
				}
				
		}
 
}

?>
