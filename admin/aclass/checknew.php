<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright � 2006   |
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
if (stristr($_SERVER['SCRIPT_NAME'], "checknew.php")) {
    die ("<center><h3>���� ��� ������� ��� ������</h3></center>");
}

class checknew
{

function checknew(){
global $apt,$admin;
$stime = time();
$admin->head();  
  flush();

  $admin->opentable("������� ��������");
  echo "<tr>"; flush();
  $admin->td_msg("&nbsp;<b>  ��� ��� �������� :  </b>",20);
  $admin->td_msg("<center><b>".$apt->getsettings('version')."</b><center>");
  echo "</tr><tr>"; flush();
  $admin->td_msg("&nbsp;<b>  ��� ��� ������ ������:  </b>",20);
  $ver = $apt->get_contents("http://www.arabportal.info/version/ver.php");
  if(!$ver)$ver = "�� ��� �������";
  $admin->td_msg("<center><b>".$ver."</b><center>");
  echo "</tr>";
  if(($apt->getsettings('version') < $ver ) and ( $ver != "�� ��� �������")){
  $msg = $apt->get_contents("http://www.arabportal.info/version/msg.php");
  echo "</tr><tr><td colspan=2 class=datacell><center><b>".$msg."</b><center></td></tr>";
  }else{echo "</tr>";}
  $admin->closetable();
  flush();

$admin->opentable("<p class=fontablt>��� ����� ������� �������</p>");
flush();

$data = $apt->get_contents("http://www.arabportal.info/version/new.php");
if(!$data)$data = "���� ... �� ��� ������� ���� ����";

echo"<tr><td class=datacell width='100%'>
$data
</td></tr></td></tr>";
$admin->closetable();
flush();

$etime = time();
$time = $etime - $stime;
echo "<center><b>������� ������� $time �����</b></center>";
}

}
?>