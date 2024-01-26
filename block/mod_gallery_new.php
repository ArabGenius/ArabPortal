<?php
/***************************************************************************
			   ÎÇÕ áãæÏíæá ãÚÑÖ ÇáÕæÑ áãÌáÉ ÇáÈæÇÈÉ ÇáÚÑÈíÉ
			   ÈÑãÌÉ ÚÈŞÑí ÇáÚÑÈ || ãæŞÚ ÎÏãÇÊí.ßæã
			www.khadmaty.com (webmaster@khadmaty.com)
***************************************************************************/
// ßæÏ ÇÏÑÇÌ ÇáÈáæß İí ÇáÈæÇÈÉ ÇáÚÑÈíÉ
// <!--INC dir="block" file="mod_gallery_new.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>ÍÕá ÎØÃ ÚäÏ ãÍÇæáÉ ÇÓÊÏÚÇÁ ÇáÕæÑ ÇáÌÏíÏå İí ÇáÈæÇÈÉ ÇáÚÑÈíÉ</h3></center>");
}


$result = @mysql_query("SELECT imgid,imgtitle,imglink FROM rafia_gallery_image WHERE allow='1' ORDER BY `imgid` DESC LIMIT 1");
if(@mysql_num_rows($result)== 0){
echo "ÚİæÇ ... íÈÏæ Çäå áÇ ÊæÌÏ ÕæÑ ÍÇáíÇ<br>Çæ Çä ÃáÈæã ÇáÕæÑ ÛíÑ ãËÈÊ";
}else{

$row = @mysql_fetch_array($result);
$imgid	= $row[imgid];
$imgtitle   = $row[imgtitle];
$imglink	= $row[imglink];

if(file_exists("modules/gallery/thumb/$imglink")){
echo "<center><a href=mod.php?mod=gallery&modfile=picshow&imgid=$imgid><img border=0 title='$imgtitle' src=modules/gallery/thumb/$imglink></a></center>";
}else{
echo "<center><a href=mod.php?mod=gallery><img border=0 src=modules/gallery/themes/images/thumb_404.gif></a></center>";
}
}
?>