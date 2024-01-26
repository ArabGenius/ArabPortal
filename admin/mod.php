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
|  Last Updated: 19/05/2007 Time: 10:00 PM  |
+===========================================+
*/

require"global.php";
check_if_admin();

//*******************************************
$PHP_SELF 	= $apt->server[PHP_SELF];
$act		= $apt->get['act'];
$session	= $admin->get_sess();

function editmodsetting($modid)
     {
         global $apt,$admin;
         $result = $apt->query("select * from rafia_mods_settings where set_mod='$modid' ORDER BY set_order ASC");

         while($row = $apt->dbarray($result))
         {
             @extract($row);
             switch($set_type)
             {
                 case "1":
                 $admin->select_time($set_text,$set_val);
                 break;
                 case "2":
                 $admin->input_select($set_text,$set_var,$set_val,1);
                 break;
                 case "3":
                 $admin->selectnum($set_text,$set_var,$set_val,'1',20);
                 break;
                 case "4":
                 $admin->textarea($set_text,$set_var,$set_val, "RTL",3);
                 break;
                 case "5":
                 $admin->yesorno($set_text,$set_var,$set_val);
                 break;
                 case "6":
                 $admin->inputtext($set_text,$set_var,$set_val,20);
                 break;
                 case "7":
                 $admin->group_check_box($set_text,"$set_var"."[]",$set_val);
                 break;

             }
         }
}

function modmsg($msg,$url=''){
	global $apt;
	if(!$url) $url = $apt->refe;
	echo "<meta http-equiv=\"refresh\" content=\"2;URL=$url\">";
	echo "<body bgcolor='3A789F'><br><div align='center'>
	<center><table border='0' width='80%' bgcolor='74A9CB'>
	<tr><td width='100%' align='center'><br>$msg<br></td>
	</tr></table></center></div>";
	exit();
}
//*******************************************

if ($act=="set"){
$admin->head();
    $result = $apt->query("SELECT * FROM rafia_mods ORDER BY id");
    while($row=$apt->dbarray($result))
    {
        @extract($row);
        if($mod_sys==0)
        {
            $modsys="„⁄ÿ·";
        }else{
            $modsys="‰‘ÿ";
        }

        $list  = array($mod_title,
                       "<center>$modsys</center>",
                       $admin->ViewHref("./../mod.php?mod=$mod_name","_blank"),
                       $admin->EditHref("mod.php?act=setting&mod=$mod_name&modid=$id"),
                       $admin->DelHref("mod.php?act=del&module=$mod_name&modid=$id"),
                       );
           $admin->Rows .= $admin->TableCell($list);
     }
    $cellHeader = array("«”„ «·»—‰«„Ã","«·‰Ÿ«„","⁄—÷"," ⁄œÌ·","Õ–›");
    $admin->ColWidth = array(40,15,15,15,15);
    echo $admin->Table($cellHeader,"«·»—«„Ã «·«÷«›Ì…");

}elseif ($act=="new"){

$admin->head();
$open = @opendir("./../modules");
$admin->opentable("<b>«·»—«„Ã «·«÷«›Ì… «·€Ì— „À» Â</b>");
                echo "<tr>";
                $admin->td_msg("<center><b>«·»—‰«„Ã</b><center>");
                $admin->td_msg("<center><b>„⁄·Ê„« </b><center>","70");
                $admin->td_msg("<center><b>&nbsp; À»Ì &nbsp;</b><center>");
                echo "</tr>";

while($folder = @readdir($open)){
if (($folder != ".") && ($folder != "..") && (is_dir("./../modules/$folder")))
{
$folder = preg_replace( "/[^a-zA-Z0-9\-\_]/", "" , $folder );
$folders[] = $folder;

$modCount = $apt->dbnumquery("rafia_mods where mod_name='$folder'");
if($modCount ==0){

                echo "<tr>";
	if(file_exists("./../modules/$folder/admin/$folder.gif")){
	$image = "<img border=0 src='./../modules/$folder/admin/$folder.gif'>";
	}elseif(file_exists("./../modules/$folder/admin/$folder.jpg")){
	$image = "<img border=0 src='./../modules/$folder/admin/$folder.jpg'>";
	}elseif(file_exists("./../modules/$folder/admin/$folder.png")){
	$image = "<img border=0 src='./../modules/$folder/admin/$folder.png'>";
	}else{
	$image = "<img border=0 src='./../modules/mod.gif'>";
	}
	if(file_exists("./../modules/" . $folder. "/admin/information.php")){
		include("./../modules/" . $folder. "/admin/information.php");
	}
                $admin->td_msg("<center>$image<br>$folder</center>");
                $admin->td_msg("<b><font color=blue>«”„ «·»—‰«„Ã:</font> $mod_title<br>
                <font color=blue>«’œ«— «·»—‰«„Ã:</font> $mod_ver<br>
                <font color=blue>„»—„Ã «·»—‰«„Ã:</font> $mod_auth<br>
                <font color=blue>Ê’› «·»—‰«„Ã:</font> $mod_desc</b>");
                $admin->td_url("mod.php?act=install&module=$folder&".$admin->get_sess(), "<center> À»Ì </center>");
                echo "</tr>";
}
unset($modCount,$mod_title,$mod_ver,$mod_auth,$mod_desc);
}
}
$admin->closetable();

}elseif($act=="manag"){
$admin->head();
$result = $apt->query("SELECT * FROM rafia_mods ORDER BY id");
$admin->opentable("<b>«·»—«„Ã «·«÷«›Ì… «·„À» Â</b>");
$cols  = 4;
$colsp = 100/$cols;
echo "<center><table border='0' width='90%' align='center' cellpadding=$cols><tr>";
    while($row=$apt->dbarray($result))
    {
	@extract($row);
	if(file_exists("./../modules/$mod_name/admin/$mod_name.gif")){
	$image = "<img border=0 src='./../modules/$mod_name/admin/$mod_name.gif'>";
	}elseif(file_exists("./../modules/$mod_name/admin/$mod_name.jpg")){
	$image = "<img border=0 src='./../modules/$mod_name/admin/$mod_name.jpg'>";
	}elseif(file_exists("./../modules/$mod_name/admin/$mod_name.png")){
	$image = "<img border=0 src='./../modules/$mod_name/admin/$mod_name.png'>";
	}else{
	$image = "<img border=0 src='./../modules/mod.gif'>";
	}

      if($mod_sys==0){
          $modsys="„⁄ÿ·";
      }else{
          $modsys="‰‘ÿ";
      }
		echo "<td align=\"center\" width=\"$colsp%\" valign=\"top\" background='images/tbg_th1.png'>";
		echo "<a title='$modsys' href=mod.php?act=edit&mod=$mod_name&modid=$id&$session>$image<br>$mod_title</a>";
	unset($image);
	echo "</td>";
	$count++;
	if ($count ==  $cols)
	{
         echo "</tr>";
         $count = 0;
	}
    }
echo "</table></center>";

}elseif($act=="setting"){
$admin->head();
$mod = $apt->get[mod];
$modid = $apt->get[modid];
$result = $apt->query("SELECT * FROM rafia_mods where id='$modid' and mod_name='$mod'");
$row = $apt->dbarray($result);
@extract($row);

        $admin->opentable(" Õ—Ì— «·»—‰«„Ã [" . $mod_title . "]");
        $admin->openform("mod.php?act=updatesetting&modid=$modid&".$admin->get_sess());
        $admin->inputtext("«”„ «·»—‰«„Ã","mod_title",$mod_title);
        $admin->yesorno("«·”„«Õ ···«⁄÷«¡ «·„”Ã·Ì‰ œŒÊ· ›ﬁÿ","mod_user",$mod_user);
        $admin->yesorno("Õ«·…  ‘€Ì· «·‰Ÿ«„","mod_sys",$mod_sys);

        $modtheme = $apt->query("SELECT * FROM rafia_mods_templates where modid='$modid' and main='1' ORDER BY themeid");
        echo "<tr><td class=datacell> ’„Ì„ «·»—‰«„Ã:</td>";
        echo "<td class=datacell><select name=themeid>";
        while($rowtheme=$apt->dbarray($modtheme)){
		  if($rowtheme[themeid] == $themeid){
			 echo "<option value='$rowtheme[themeid]' selected>$rowtheme[theme]</option>";
		  }else{
			 echo "<option value='$rowtheme[themeid]'>$rowtheme[theme]</option>";
		  }
        }
        echo "</select></td></tr>";

        $admin->yesorno("≈ŸÂ«— «·⁄„Êœ «·«Ì”— Ê „« »œ«Œ·… „‰ ﬁÊ«∆„ ø","left_menu",$left_menu);
        $admin->yesorno("≈ŸÂ«— «·⁄„Êœ «·«Ì„‰ Ê „« »œ«Œ·… „‰ ﬁÊ«∆„ ø","right_menu",$right_menu);
        $admin->yesorno("≈ŸÂ«— «·⁄„Êœ «·«Ê”ÿ Ê „« »œ«Œ·… „‰ ﬁÊ«∆„ ø","middle_menu",$middle_menu);

        $admin->yesorno("≈ŸÂ«— «·«⁄·«‰ «·⁄·ÊÌ","adsH",$adsH);
        $admin->yesorno("≈ŸÂ«— «·«⁄·«‰ «·”›·Ì","adsF",$adsF);

        echo "<tr><td class=datacell valign=\"top\" width='33%'>«·ﬁÊ«∆„ «·„ «Õ…:</td><td class=datacell>\n";

        $menuarr = explode(",",$mnueid);
        $result = $apt->query("SELECT * FROM rafia_menu WHERE menushow='1'");
        while($row = $apt->dbarray($result))
        {
            extract($row);
		if($menualign == 1){$menualign = 'Ì„Ì‰';}elseif($menualign == 2){
		$menualign = 'Ì”«—';}else{$menualign = 'Ê”‹ÿ';}
            if(in_array($menuid,$menuarr))
            {
                echo "<input type=\"checkbox\" name=\"menucheck[]\" value=\"$menuid\" checked id=\"\">   $title  [$menualign]<br>";
            }
            else
            {
                echo "<input type=\"checkbox\" name=\"menucheck[]\" value=\"$menuid\" id=\"\">   $title  [$menualign]<br>";
            }
        }

        echo "</td></tr>\n";
        $admin->closetable();
        $admin->submit(_UPDATE);

}elseif($act=="updatesetting"){
$modid = $apt->get[modid];
$mod_title 	= $apt->post[mod_title];
$mod_user 	= $apt->post[mod_user];
$mod_sys 	= $apt->post[mod_sys];
$themeid 	= $apt->post[themeid];
$left_menu 	= $apt->post[left_menu];
$right_menu	= $apt->post[right_menu];
$middle_menu	= $apt->post[middle_menu];
$adsH		= $apt->post[adsH];
$adsF		= $apt->post[adsF];
$value =  $admin->myimplode($apt->post[menucheck]);

$result = $apt->query("update rafia_mods set mod_title='$mod_title',
                                               mod_user='$mod_user',
                                               mod_sys='$mod_sys',
                                               left_menu='$left_menu',
                                               right_menu='$right_menu',
                                               middle_menu='$middle_menu',
                                               mnueid='$value',
                                               themeid='$themeid',
                                               adsH='$adsH',
                                               adsF='$adsF'
                                               WHERE id='$modid'");
if ($result)
{
    header("Refresh: 1;url=$PHP_SELF?act=set&".$admin->get_sess());
    $admin->windowmsg(" „ «· ⁄œÌ· »‰Ã«Õ");
}

}elseif($act=="edit"){
$mod = $apt->get[mod];
$modid = $apt->get[modid];
$op = $apt->get[op];
    if($op){

        if(!file_exists("./../modules/" . $mod . "/admin/" . $op . ".php")){
		$admin->head();
			modmsg("⁄›Ê« ... Ì»œÊ «‰ﬂ «Œÿ√  ›Ì «”„ «·„·› «Ê «‰ «·„·› «·–Ì  ÿ·»Â €Ì— „ Ê›—<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… «·»—«„Ã «·„·Õﬁ…","mod.php?act=manag&$session");
        }else{
        	include("./../modules/" . $mod . "/admin/" . $op . ".php");
        }

    }else{

        $inc_mod ="./../modules/" . $mod . "/admin/index.php";
        if(file_exists($inc_mod)){
            include($inc_mod);
        }else{
    		header("Refresh: 1;url=$PHP_SELF?act=manag&".$admin->get_sess());
    		$admin->windowmsg("<b>⁄›Ê« .. ·« ÌÊÃœ «Ì „·› ·≈œ«—… «·»—‰«„Ã</b>");
        }

    }
}elseif($act=="install"){
$module = $apt->get[module];
	if(file_exists("./../modules/" . $module. "/admin/information.php")){
		include("./../modules/" . $module. "/admin/information.php");
		$title 	= $mod_title;
	}else{
		$title 	= $module;
		$mod_user 	= 0;
		$left_menu 	= 0;
		$right_menu = 1;
		$middle_menu = 0;
		$menuid 	= '1,2,3,4,6,7,8,9,10,11,14,15,16,17,18,19';
		$themeid	= 1;
		$adsH		= 1;
		$adsF		= 0;
	}

	if(!file_exists("./../modules/" . $module. "/admin/setup.php")){
			$result = $apt->query("INSERT INTO rafia_mods VALUES ('', '$module', '$title', '$mod_user', 1, '$left_menu', '$right_menu', '$middle_menu','$menuid','$themeid','$adsH','$adsF');");
		$admin->head();
		if($result){
			modmsg(" „ «÷«›… «·»—‰«„Ã «·„·Õﬁ »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… «·»—«„Ã «·„·Õﬁ…","mod.php?act=edit&mod=$module&".$admin->get_sess());
		}else{
			modmsg("⁄›Ê« .. Õ’· Œÿ√ ⁄‰œ «÷«›… «·»—‰«„Ã «·„·Õﬁ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… «·»—«„Ã «·„·Õﬁ…","mod.php?act=new&".$admin->get_sess());
		}
	}else{
		$admin->head();
		include("./../modules/" . $module . "/admin/setup.php");
	}

}elseif($act=="information"){
$module = $apt->get[module];
	if(file_exists("./../modules/" . $module. "/admin/information.php")){
		include("./../modules/" . $module. "/admin/information.php");
		echo "<br>[ <a href='javascript:self.close();'><b>«€·«ﬁ «·‰«›–…</b></a> ]" ;
	}else{
		$admin->head();
    		$admin->windowmsg("<b>⁄›Ê« ... ·« ÌÊÃœ «Ì „⁄·Ê„«  ⁄‰ Â–« «·»—‰«„Ã</b>");
		echo "<br>[ <a href='javascript:self.close();'><b>«€·«ﬁ «·‰«›–…</b></a> ]" ;
	}
}elseif($act=="del"){
$modid = $apt->get[modid];
$module = $apt->get[module];
	if(!file_exists("./../modules/" . $module . "/admin/uninstall.php")){
	 $result = $apt->query("DELETE from rafia_mods_settings where set_mod='$module'");
		$result = $apt->query("DELETE from rafia_mods_templates where modid='$modid' and modname='$module'");
		$result = $apt->query("DELETE from rafia_mods where id='$modid' and mod_name='$module'");
		$admin->head();
		if($result){
			modmsg(" „ «“«·… «·»—‰«„Ã «·„·Õﬁ „‰ ﬁ«⁄œ… «·»Ì«‰«  »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… «·»—«„Ã «·„·Õﬁ…","mod.php?act=set&".$admin->get_sess());
		}else{
			modmsg("⁄›Ê« .. Õ’· Œÿ√ ⁄‰œ «“«·… «·»—‰«„Ã «·„·Õﬁ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… «·»—«„Ã «·„·Õﬁ…","mod.php?act=set&".$admin->get_sess());
		}
	}else{
		$admin->head();
		include("./../modules/" . $module . "/admin/uninstall.php");
	}
}elseif($act=="temp"){
	include("aclass/mods_temp.php");
}else{
		$admin->head();
    		$admin->windowmsg("<b>⁄›Ê« ... ·„ Ì „ «Œ Ì«— «Ì ⁄„·Ì…</b>");
}
?>