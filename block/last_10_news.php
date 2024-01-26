<?php
/*
+===========================================+
|      ArabPortal V2.2.x Copyright © 2008   |
|   -------------------------------------   |
|                     BY                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Info      |
|   -------------------------------------   |
|  Last Updated: 05/08/2008 Time: 03:55 PM  |
+===========================================+
*/

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÎØÇÁ</h3></center>");
}
$browser = strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6");
if ($browser == false)$limg = "<img src='images/topics.gif' valign=bottom>";
else $limg = "";

$result = $block->query("SELECT id,title FROM rafia_news where allow='yes' ORDER BY id DESC limit 10");
$index_middle .= "<marquee class='news_title' BEHAVIOR='scroll' direction='right' scrollAmount='2' scrollDelay='1' onmouseover='this.stop()' onmouseout='this.start()' width='100%' border='0'>";
while($row = mysql_fetch_array($result)){
extract($row);
$title        =  str_replace('$','&#36',$title);
$title        =  $block->format_data_out($title);
$index_middle .= "$limg&nbsp;&nbsp; <a href=news.php?action=view&id=$id><b>$title</b></a>&nbsp;&nbsp;&nbsp;&nbsp;";
}
$index_middle .= "</marquee>";

echo  $index_middle;

?>