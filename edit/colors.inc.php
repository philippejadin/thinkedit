<?php

/*
List of colors used by the color picker
*/

//require_once('debuglib.inc.php');
//require_once('debug.inc.php');

for ($r = 0; $r<16; $r = $r + 6)
{
  for ($g = 0; $g<16; $g = $g + 6)
  {
    for ($b = 0; $b<16; $b = $b + 6)
    {

      $colors[] = dechex($r) . dechex($r) . dechex($g) . dechex($g) . dechex($b) . dechex($b);
    }
  }
}



//print_a($colors);


/*
echo "<table>";
echo "<tr>";

foreach ($colors as $color)
{
echo "<td bgcolor=\"#$color\">&nbsp;</td>";
}
echo "</tr>";
echo "</table>";
*/



?>
