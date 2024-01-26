<?php
/*
+===========================================+
|      ArabPortal V2.2.x Copyright � 2008   |
|   -------------------------------------   |
|                     BY                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Info      |
|   -------------------------------------   |
|  Last Updated: 08/08/2008 Time: 06:00 AM  |
+===========================================+
*/

if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

require"global.php";
if(isset($apt->post['user_name']))
{
    $admin->login();
}
if(isset($apt->get['logout']))
{
   if( $admin->logout() == true)
   {
       $admin->bodymsg("�� ����� ������","index.php");
   }
}

check_if_admin();

if(isset($apt->get['cat']))
{
    $module = $apt->format_data($apt->get['cat']);
    $module =  $admin->get_Str($module);
    
   if( file_exists ($CONF['class_folder'].'/' . $module . '.php'))
   {
       require $CONF['class_folder'].'/' . $module . '.php';
   }
   else
   {
       $apt->windowmsg("���� ������� ���� ����� ������� ��� ������");
       exit;
   }

    new $module($apt->get['act']);

}
if($apt->get['action']==""){
    echo "<html><head><meta http-equiv=Content-Type content=text/html; charset=windows-1256>"
        ."<title>���� ������</title></head>"
        ."<frameset cols='80%,*' rows='*' border='0' frameborder='0'>"
        ."<frame src='index.php?action=index&".$admin->get_sess()."' name='admin'>"
        ."<frame src='index.php?action=right&".$admin->get_sess()."' name='nav'>"
        ."</frameset><noframes><body bgcolor='FFFFFF'></body>"
        ."</noframes></html>";
}
else if($apt->get['action']=="right")
{
     $admin->head();

     echo "<base target=\"admin\"><body bgcolor=\"#ffffff\"><div align=\"center\"><center>";

     $admin->trtdurl("index.php?logout=".$admin->get_sessID()."","����� ������","admin");
     echo '<br>';
     $admin->trtdurl("../","������ ��������","_blank");
     $admin->opentable("");
      echo "<table border=1 width=100% bordercolor=#000000>";

     $result = $apt->query("select * from rafia_admin_menu WHERE submenu='0' order by orderby");

     while($row=$apt->dbarray($result))
     {
         $menuid = $row["menuid"];

         echo '<tr><td width="100%" class="sec_head" align="center" bgcolor="376371" background="images/td_back.gif" onClick="block_switsh(b'.$menuid.')"><font color="#FFFFFF"><b>'.@constant($row[menutitle]).'</td></tr>';

         if($menuid == 1){$none = "";}else{$none = "none";}

         echo '<tr><td class=datacell width="100%"  class="sec_block" bgcolor="D2D4D4" align="right" id=b'.$menuid.' style="display:'.$none.';">';

         $result2 = $apt->query("select * from rafia_admin_menu  WHERE submenu='$menuid' order by orderby");

         while($sub=$apt->dbarray($result2))
         {
			echo '<img src="images/linkarrow.gif"><a target="admin" href="'.$admin->get_sess($sub[menuurl]).'">'.@constant($sub[menutitle]).'</a><hr color="D2D4D4">';
         }

         echo  '</td></tr>';
     }

   echo "</table>"; $admin->closetable();
}
else if($apt->get['action']=="index")
{
    $admin->head();  
    $admin->opentable("<p class=fontablt> ����� �� <font color=\"#FF0000\"></font>�� ���� ������</p>");
    echo"<tr><td class=datacell width=\"100%\">";
    echo "
    <font size=\"4\">
    ������ ����� ����� ���� <br>
 ������� ������� <br>
 �����  ����� ��������  ������� ������� ����� ����� �������
 <br>
 ���� ����� ������� ��� ��� �������� ����� ���������
 <a href=\"http://www.arabportal.info/docs/arabportal.chm\">
 <font color=\"#FF0000\"><strong>����� ��� ��������</strong>
 </font></a>
 <br>
  <a href=\"http://www.arabportal.info\">www.arabportal.info</a></font>";
  echo"</td></tr>";
  echo"</td></tr>";
  $admin->closetable();
  flush();

  $admin->opentable("��� �� �������");
  echo "<form name=search_form action=index.php?cat=users&act=find&".$admin->get_sess()." method=post>\n";
  echo "<tr><td  width=\"100%\" class=datacell>  <select name='searchby'>\n";
  echo "<option selected value='userid'>����� ������ �</option>\n";
  echo "<option value='userid'>��� ��������</option>\n";
  echo "<option value='username'>��� ��������</option>\n";
  echo "<option value='email'>������ ���������</option>\n";
  echo "<option value='password'>���� ������</option>\n";
  echo "</select></td></tr>\n";
  echo "<tr><td class=datacell><input type=text name=searchfor size=18></td></tr><tr>\n";
  echo "<td class=datacell align=center><input type=submit name=submit value='���'></td>\n";
  echo "</tr></form>";
  $admin->closetable();

//====================================
}
else if($apt->get['action']=="upset")
{
    while (list($key,$value)= each($HTTP_POST_VARS))
    {
        $value  = $admin->format_data($value);
        $result = $apt->query("update rafia_settings set value='$value' WHERE variable='$key'");
    }
    if ($result)
    {
        header("Refresh: 1;url=".$apt->refe);
        $admin->windowmsg("<p>&nbsp;�� ����� ��������� </p>");
    }
}
else if($apt->get['action']=="settings")
{
      $time = date ("d /m /Y  h:i a");
      $time = str_replace("pm","����",$time);
      $time = str_replace("am","�����",$time);
      $admin->head();
      $admin->openform("index.php?action=upset&".$admin->get_sess());
      $admin->opentable(_MAIN_SETTINGS,'2');
      $admin->editsetting('1');
      $admin->closetable();

      $admin->opentable(_FORSEARCH,'2');
      $admin->editsetting('7');
      $admin->closetable();
      
      $admin->opentable(_DATE_TYPE,'2');
      $admin->td_msg("����� ������� ���� ��� ��� ������� �������� �� ��� �������");
      $admin->td_msg("<b>����� ���� ��� �������  : ".$time);
      $admin->editsetting('2');
      $admin->closetable();
      $admin->opentable("������ ������� ",'2');
      $admin->editsetting('3');
      $admin->closetable();
      $admin->opentable("������� ��������",'2');
      $admin->editsetting('4');
      $admin->closetable();
      $admin->opentable("����� ������� �������",2);
      $admin->editsetting('5');
      $admin->closetable();
      
      $admin->opentable("������� ������� ��������",2);
      $admin->editsetting('6');
      $admin->closetable();

      $admin->opentable("������ �����",2);

      echo "<tr><td class=datacell width='33%' class=datacell><p class=fontablt>������� ��������<b>:</b></td><td class=datacell width='72%' class=datacell><select name='theme'>";
      $theme = $apt->getsettings("theme");
      $result= $apt->query("select theme from rafia_design where  theme='$theme'");
      while($row = $apt->dbarray($result))
      {
          extract($row);
          echo "<option value='$theme'>$theme</option>";
      }
      $result = $apt->query("select * from rafia_design where theme!='$theme' order by id");
      while($row = $apt->dbarray($result))
      {
          extract($row);
          echo "<option value='$theme'>$theme</option>";
      }
      echo "</select></td></tr>";
      $admin->closetable();
      $admin->submit("������ �������");

}
?>