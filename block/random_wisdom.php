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
|  Last Updated: 23/10/2006 Time: 06:00 PM  |
+===========================================+
*/
/*
////////////////////////////////////////////////
//     ArabPortal Random Wisdom Block v1.0    //
//              Powered By: Myrosy            //
//            Name: Ramzi Al-Shubbar          //
//          Site Name: www.myrosy.com         //
//           E-Mail: admin@myrosy.com         //
////////////////////////////////////////////////
*/
// <!--INC dir="block" file="random_wisdom.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÍÏË ÎØÃ ÃËäÇÁ ãÍÇæáÉ ÅÓÊÏÚÇÁ ÇáÍßãÉ ÇáÚÔæÇÆíÉ</h3></center>");
}
global $apt;
$open = @fopen("block/wisdom.txt",r);
$data = @fread($open,@filesize("block/wisdom.txt"));
@fclose($open);
$quote = @explode("\n",$data);
srand((double)microtime()*1000000);
$content = "<br>".$quote[rand(0,count($quote))] ."<br>";
echo $content;

?>