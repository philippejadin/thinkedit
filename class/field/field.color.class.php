<?php


/*
one day, there will be a color field. This is the code to generate a list of colours

a color picker could be added as well


*/

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

?>
