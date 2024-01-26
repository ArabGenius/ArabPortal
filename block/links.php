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
*/
//block file Dir:block\links.php

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÎØÇÁ</h3></center>");
}


$linkColcount    = 2;
if($linkColcount < 1 ) $linkColcount = 1;
$index_middle .= "<div style=\"width:90%; color=#C0C0C0; border-style: dotted; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px\">
<center><table border='0' width='100%' align='center' cellpadding='".$linkColcount."'><tr>";
$result = mysql_query("SELECT * FROM rafia_cat WHERE catType='4' and subcat='0' ORDER BY ordercat ASC");
while($row = mysql_fetch_array($result))
{
    extract($row);
    $numrows       = $countopic;
    $title         = trim(nl2br(stripslashes($title)));
    $dsc           = trim(nl2br(stripslashes($dsc)));
    $tdwidth =  100/$linkColcount;
    $index_middle .= "<td align=\"center\" width=\"".$tdwidth."%\"  valign=\"top\">";

$index_middle .=<<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td width="100%" style="padding: 3"><font class=fontht><img border="0" src="images/b1.gif" align="middle" width="8" height="8">  <a href=link.php?action=list&cat_id=$id> $title</a></font>
<span class=fontablt>           <img border="0" src="images/topics.gif" align="middle" alt="ÚÏÏ ÇáãæÇÖíÚ">
<font color="#C0C0C0">[$numrows]
</font></span></td>
</tr>
<tr>
<td width="100%" class=fontablt><font color="#808080">    $dsc</font></td>
</tr>
</table>
EOF;

     $index_middle .= "</td>";
     $count++;
     if ($count ==  $linkColcount)
     {
         $index_middle .= "</tr>";
         $count = 0;
     }
}
$index_middle .= "</tr></table></div><br>";
echo  $index_middle;
?>