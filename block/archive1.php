<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright © 2006   |
|   -------------------------------------   |
|                                           |
|            By Rafia AL-Otibi              |
|                    And                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Net       |
|   -------------------------------------   |
|  Last Updated: 22/10/2006 Time: 10:00 PM  |
+===========================================+
//block file Dir:block\links.php

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÎØÇÁ</h3></center>");
}
*/


$days =array(date("t"),date("d"),date("M - d-m-Y"),date("-m-Y")); 
require_once("func/Files.php");
$theader = Files::read('block/Calendar/tpl_m.html');
echo str_replace("_DATE_HERE_",$days[2],$theader);
for($i=1,$x=1;$i<=$days[0];$i++,$x++){ 
if ($i == 1) { 
$cday = date('w', mktime(0,0,0,date('m'),$i,date('Y'))); 
echo str_repeat('<td></td>', $cday); 
$x = $cday + 1; 
}
print ($days[1] == $i)? '<td><a href=archive.php?day='.$i.$days[3].'><font color=red>'.$i.'</font></a></td>'."\n" : '<td><a href=archive.php?day='.$i.$days[3].'>'.$i.'</a></td>'."\n"; 
if($x % 7 == 0) print '</tr><tr>'."\n"; 
} 
print '</table>'; 




?>