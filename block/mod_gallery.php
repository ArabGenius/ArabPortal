<?php
/***************************************************************************
			   ������ ���� ����� ����� ������� �������
			   ����� ����� ����� || ���� ��� �������
			www.arabiaone.org (arabgenius@hotmail.com)
***************************************************************************/
// <!--INC dir="block" file="mod_gallery.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>��� ��� ��� ������ ������� ����� ������� ��������</h3></center>");
}

$pic=array();
$result = @mysql_query("SELECT imgid,imglink FROM rafia_gallery_image WHERE allow = 1");
while($row = @mysql_fetch_array($result)){
$imgid   = $row[imgid];
$imglink = $row[imglink];
$pic[] = array ('imgid'=>$imgid,'imglink'=>$imglink);
}

if(count($pic) == 1){
$lp = count($pic);
}else{
$lp = count($pic) - 1;
}
if($lp <= 0){echo "���� �� ���� ���<br>�� �� ������ ��� ����";}else{
srand((double)microtime()*1000000);

$photo = $pic[rand(0,$lp)];

$id = $photo[imgid];
$link = $photo[imglink];
echo "<center><a href=mod.php?mod=gallery&modfile=picshow&imgid=$id><img border=0 src=modules/gallery/thumb/$link></a></center>";
}
?>