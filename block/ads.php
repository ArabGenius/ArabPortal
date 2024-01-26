<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright ╘ 2006   |
|   -------------------------------------   |
|            By Rafia AL-Otibi              |
|                    And                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Net       |
|   -------------------------------------   |
|  Last Updated: 22/10/2006 Time: 10:00 PM  |
+===========================================+
*/

// <!--INC dir="block" file="ads.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>муА ньц зДо ЦмгФАи гсйозга гАгзАгД гАЦймяъ</h3></center>");
}
	global $apt;
	echo "<center>".$apt->ads_view_in('b')."</center>";

?>