<?php
/***************************************************************************
			   ����� ����� ����� || ���� ����� �����
					www.service4ar.com
***************************************************************************/
// ��� ����� ������ �� ������� �������
// <!--INC dir="block" file="most_seen_news.php" -->

if (TURN_BLOCK_ON !== true){
    die ("<center><h3>��� ��� ��� ������ ������� ������� ���� ������ �� ������� �������</h3></center>");
}

// ������� = 0
// ����� = 7
// ��� = 30

$since = 0;

//--------------------
$day = 60*60*24;
if($since == 0)$time = 0;
else $time = time() - ($since*$day);

$index_middle .= "<div align=\"center\">
<center><table border='0' width='100%' align='center' cellpadding='".$linkColcount."'><tr>";
$result = mysql_query("SELECT id,title,reader FROM rafia_news where allow='yes' and date_time>='$time' ORDER BY reader DESC limit 10");
while($row = mysql_fetch_array($result)){
extract($row);
$index_middle .= "<li><a title='��� ���� ������� $reader' href=news.php?action=view&id=$id><b>$title</b></a>";
}

$index_middle .= "</tr></table></div>";
echo  $index_middle;
?>