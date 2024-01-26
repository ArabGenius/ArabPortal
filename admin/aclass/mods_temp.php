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

if (stristr($_SERVER['SCRIPT_NAME'], "mods_temp.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

require_once"global.php";
check_if_admin();

$modtitle = array();
$result = $apt->query("SELECT id,mod_title FROM rafia_mods ORDER BY id");
while($row=$apt->dbarray($result)){
@extract($row);
$modtitle[$id] = $mod_title;
}

$themetitle = array();
$result = $apt->query("SELECT themeid,modname,theme FROM rafia_mods_templates ORDER BY id");
while($row=$apt->dbarray($result)){
@extract($row);
$themetitle[$modname][$themeid] = $theme;
}

if(!$apt->get['do']){
$admin->head();
$result = $apt->query("SELECT * FROM rafia_mods ORDER BY id");
$admin->opentable("<b>«œ«—… «·„ŸÂ— ··»—«„Ã «·«÷«›Ì… «·„À» Â</b>",4);

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
	$theme_no = $apt->dbnumquery("rafia_mods_templates"," modid='$id' and main='1'");
	$temp_no  = $apt->dbnumquery("rafia_mods_templates"," modid='$id' and main='0'");
	echo "<tr>";
                $admin->td_msg("<center>$image<br><b>$mod_title</b><center>",30);
                $admin->td_msg("<center><a href=mod.php?act=temp&do=newtheme&modid=$id&mod=$mod_name&$session><b>«÷«›…  ’„Ì„ ÃœÌœ</b></a><br>
                <a href=mod.php?act=temp&do=import&modid=$id&mod=$mod_name&$session><b>«” Ì—«œ  ’„Ì„</b></a><br>
                <a href=mod.php?act=temp&do=newtemp&modid=$id&mod=$mod_name&$session><b>«÷«›… ﬁ«·» ÃœÌœ</b></a><center>",25);
                $admin->td_msg("<center>⁄œœ «· ’«„Ì„ : $theme_no<br>⁄œœ «·ﬁÊ«·» «·ﬂ·Ì: $temp_no<center>");
                $admin->td_msg("<center><a href=mod.php?act=temp&do=viewtheme&modid=$id&mod=$mod_name&$session><b>⁄‹—÷</b></a><center>",25);
	echo "</tr>";
	unset($image);

    }
$admin->closetable();
}elseif($apt->get['do'] == 'newtheme'){
$modid = $apt->setid('modid');
$mod = $apt->get[mod];
$modtitle = $modtitle[$modid];
$admin->head();
$admin->opentable("√÷«›…  ’„Ì„ ÃœÌœ [$modtitle]");
$admin->openform("mod.php?act=temp&do=inserttheme&modid=$modid&mod=$mod&$session");
$admin->inputtext("≈”„ «·ﬁ«·»","theme",'');
$admin->closetable();
$admin->submit("√÷‹› «· ’„Ì„");
}elseif($apt->get['do'] == 'inserttheme'){

$theme    = trim($admin->format_data($apt->post[theme]));
$modid = $apt->setid('modid');
$mod = $apt->get[mod];

if($theme == ''){
	$admin->head();
	modmsg("·„ Ì „ «÷«›… «· ’„Ì„ »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰","mod.php?act=temp&$session");
 }

$result  = $apt->query("SELECT `themeid` FROM `rafia_mods_templates` where modid='$modid' ORDER BY `themeid` DESC limit 1;");
$row=$apt->dbarray($result);
@extract($row);
$themeid = $themeid + 1;

$result  = $apt->query("INSERT INTO rafia_mods_templates VALUES ('', '1', '$themeid', '', '$modid', '$mod', '$theme', '', '');");

//$themeid = $apt->insertid();

 if($result) {
	$admin->head();
		modmsg(" „ «÷«›… «· ’„Ì„ »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «· ’«„Ì„","mod.php?act=temp&do=viewtheme&modid=$modid&mod=$mod&$session");
 }

}elseif($apt->get['do'] == 'import'){
$modid = $apt->setid('modid');
$mod = $apt->get[mod];
$modtitle = $modtitle[$modid];

$admin->head(1);
$admin->opentable("«” Ì—«œ  ’„Ì„ Ã«Â“ [ $modtitle ]");
$admin->openformdata("mod.php?act=temp&do=doimport&$session");
$admin->inputtext("«”„ «· ’„Ì„","theme",'','10');
$admin->inputfile("«Œ — „·› «· ’„Ì„");
$admin->inputhidden('modid',$modid);
$admin->inputhidden('mod',$mod);
$admin->closetable();
$admin->submit("≈÷‹‹‹«›…");
}elseif($apt->get['do'] == 'doimport'){

$modid    	= $admin->format_data($apt->post[modid]);
$mod  	= $admin->format_data($apt->post[mod]);
$theme    	= $admin->format_data($apt->post[theme]);

$upload = $apt->files["name_file"];
$tmp_name =  $upload["tmp_name"];
if (is_uploaded_file($upload['tmp_name']))
{
    $path = $apt->upload_path."/".$upload['name'];
    if(@move_uploaded_file($tmp_name, $path))
    {
        $themename = $path;
    }
}
else
{
    $admin->windowmsg("·«Ì„ﬂ‰ —›⁄ «·„·›");
   exit;
}

$filesize = @filesize($themename);
$filenum  = @fopen($themename,"r");
$filetemp = @fread($filenum,$filesize);
@fclose($filenum);

$design_temp    = explode("|--|",$filetemp);

$result  = $apt->query("SELECT `themeid` FROM `rafia_mods_templates` where modid='$modid' ORDER BY `themeid` DESC limit 1;");
$row=$apt->dbarray($result);
@extract($row);
$themeid = $themeid + 1;
	$result  = $apt->query("INSERT INTO rafia_mods_templates VALUES ('', '1', '$themeid', '', '$modid', '$mod', '$theme', '', '');");

$count    = count($design_temp);
for ($i = 1; $i < $count; $i++){
       $exp     = explode("<=>",$design_temp[$i]);
       $exp[1] = $admin->format_data($exp[1]);
$result  = $apt->query("INSERT INTO rafia_mods_templates VALUES ('', '0', '0', '$themeid', '$modid', '$mod', '', '$exp[0]', '$exp[1]');");
}
 if($result)
 {
	$admin->head();
	@unlink($themename);
		modmsg(" „ «÷«›… «· ’„Ì„ »‰Ã«Õ<br>·«  ‰”Ï «‰  ﬁÊ„ »‰ﬁ· ’Ê— «· ’„Ì„ ›Ì «·„ﬂ«‰ «·„‰«”»<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «· ’«„Ì„","mod.php?act=temp&do=viewtheme&modid=$modid&mod=$mod&$session");
 }
}elseif($apt->get['do'] == 'newtemp'){
$modid = $apt->setid('modid');
$modtitle = $modtitle[$modid];
$mod = $apt->get[mod];
$getthemeid = $apt->get[themeid];
$themetitle = $themetitle[$mod][$getthemeid];
$admin->head(1);
echo "<form action=mod.php?act=temp&do=insert&$session METHOD=POST name=\"temp\">\n";
$admin->opentable("√÷«›… ﬁ«·» ÃœÌœ [$modtitle] [$themetitle]");
$admin->inputtext("≈”„ «·ﬁ«·»","temp_title",$temp_title);
$result = $apt->query("SELECT * FROM rafia_mods_templates where modid='$modid' and main='1' ORDER BY id");
echo "<tr><td class=datacell>÷⁄Â ›Ì «· ’„Ì„:</td>";
echo "<td class=datacell><select name=themeid>";
while($row=$apt->dbarray($result)){
@extract($row);
if($getthemeid == $themeid){
echo "<option value='$themeid' selected>$theme</option>";
}else{
echo "<option value='$themeid'>$theme</option>";
}
}
echo "</select></td></tr>";

$admin->textarea("ﬂÊœ «·ﬁ«·»","template",htmlspecialchars($template),'ltr',18);
$admin->inputhidden('modid',$modid);
$admin->inputhidden('modname',$modname);
$admin->closetable();
$admin->submit("√÷‹› «·ﬁ«·»");
}elseif($apt->get['do'] == 'viewtheme'){

$admin->head();
$mod = $apt->get['mod'];
$modid = $apt->get['modid'];
$modtitle = $modtitle[$modid];
$admin->opentable("<b>⁄—÷ «· ’«„Ì„ ··»—‰«„Ã [ $modtitle ]</b>",6);

$result = $apt->query("SELECT * FROM rafia_mods_templates where modid='$modid' and main='1' ORDER BY id");
while($row=$apt->dbarray($result)){
@extract($row);
$num = $apt->dbnumquery("rafia_mods_templates","modid='$modid' and sub='$themeid'");
	echo "<tr>";
                $admin->td_msg("<center><b>$theme</b><center>",30);
                $admin->td_msg("<center><b>⁄œœ «·ﬁÊ«·»=$num</b><center>",20);
                $admin->td_msg("<center><b>&nbsp;<a href=mod.php?act=temp&do=export&modid=$modid&mod=$mod&themeid=$themeid&$session> ’œÌ—&nbsp;</b><center>",12);
                $admin->td_msg("<center><b>&nbsp;<a href=mod.php?act=temp&do=newtemp&modid=$modid&mod=$mod&themeid=$themeid&$session>«÷› ﬁ«·»&nbsp;</b><center>",14);
                $admin->td_msg("<center><b>&nbsp;<a href=mod.php?act=temp&do=view&modid=$modid&mod=$mod&themeid=$themeid&$session>⁄‹—÷&nbsp;</b><center>",12);
                $admin->td_msg($admin->DelHref("mod.php?act=temp&do=deltheme&modid=$modid&mod=$mod&themeid=$themeid&tempid=$id&$session"),20);
	echo "</tr>";
}
$admin->closetable();
}elseif($apt->get['do'] == 'export'){
$modid = $apt->get['modid'];
$themeid = $apt->get['themeid'];
$mod = $apt->get['mod'];

header("Content-disposition: filename=$mod.theme");
header("Content-type: unknown/unknown");
header("Pragma: no-cache");
header("Expires: 0");
$result = $apt->query("SELECT * FROM rafia_mods_templates where modid='$modid' and sub='$themeid' ORDER BY id");
while($row=$apt->dbarray($result)){
@extract($row);
$template = str_replace("\'","\"",$template);
echo "|--|$temp_title<=>$template\n";
}
exit;
}elseif($apt->get['do'] == 'view'){
$admin->head();
$mod = $apt->get['mod'];
$modid = $apt->get['modid'];
$themeid = $apt->get['themeid'];
$modtitle = $modtitle[$modid];
$themetitle = $themetitle[$mod][$themeid];

$admin->opentable("<b>⁄—÷ «· ’„Ì„ [$themetitle]  ·»—‰«„Ã [ $modtitle ]</b>",4);
$result = $apt->query("SELECT * FROM rafia_mods_templates where modid='$modid' and sub='$themeid' ORDER BY id");
while($row=$apt->dbarray($result)){
@extract($row);
$themeid = $apt->get['themeid'];
	echo "<tr>";
                $admin->td_msg("<center><b>$temp_title</b><center>",50);
                $admin->td_msg("<center><a href='#' onclick=\"window.open('mod.php?act=temp&do=showcode&modid=$modid&mod=$mod&themeid=$themeid&tempid=$id&$session', '', 'HEIGHT=400, resizable=yes, WIDTH=550, screenX=313,screenY=259,left=190,top=90');return false;\"><b>&nbsp;&nbsp;⁄‹—÷&nbsp;&nbsp;</b></a></center>",20);
                $admin->td_msg("<center><b>&nbsp;<a href=mod.php?act=temp&do=edit&modid=$modid&mod=$mod&themeid=$themeid&tempid=$id&$session> Õ—Ì—&nbsp;</b><center>",15);
                $admin->td_msg($admin->DelHref("mod.php?act=temp&do=deltemp&modid=$modid&mod=$mod&themeid=$themeid&tempid=$id&$session"));

	echo "</tr>";
}
$admin->closetable();
}elseif($apt->get['do'] == 'showcode'){
$tempid = $apt->setid('tempid');
$result =  $apt->query("SELECT * FROM rafia_mods_templates where id='$tempid'");
$temp = $apt->dbarray($result);
extract($temp);
$template = str_replace('modules/','./../modules/',$template);
echo "<html dir=rtl>
<META http-equiv=Content-Type content=\"text/html; charset=windows-1256\">
";
$apt->theme = $apt->getsettings('theme');
$result = $apt->query("SELECT * FROM rafia_design WHERE theme='".$apt->theme."'");
$drow         = $apt->dbarray($result);
eval("\$css =\"".str_replace("\"","\\\"",stripslashes($drow['style_css'])). "\";");
//$css = str_replace('modules/','./../themes/',$css);
echo '<style>' . $css . '</style>';
echo $template;
}elseif($apt->get['do'] == 'deltheme'){
$tempid = $apt->setid('tempid');
$modid      = $admin->format_data($apt->get[modid]);
$modname    = $admin->format_data($apt->get[modname]);
$themeid    = $admin->format_data($apt->get[themeid]);

$result =  $apt->query("delete FROM rafia_mods_templates where id='$tempid'");
 if($result)
 {
	$apt->query("delete FROM rafia_mods_templates where modid='$modid' and sub='$themeid'");
	$admin->head();
		modmsg(" „ Õ–› «·ﬁ«·» »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «·ﬁÊ«·»","mod.php?act=temp&do=view&modid=$modid&mod=$modname&themeid=$themeid&$session");
 }

}elseif($apt->get['do'] == 'deltemp'){
$tempid = $apt->setid('tempid');
$modid      = $admin->format_data($apt->get[modid]);
$modname    = $admin->format_data($apt->get[modname]);
$themeid    = $admin->format_data($apt->get[themeid]);

$result =  $apt->query("delete FROM rafia_mods_templates where id='$tempid'");
 if($result)
 {
	$admin->head();
		modmsg(" „ Õ–› «·ﬁ«·» »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «·ﬁÊ«·»","mod.php?act=temp&do=view&modid=$modid&mod=$modname&themeid=$themeid&$session");
 }

}elseif($apt->get['do'] == 'edit'){
$admin->head(1);
$tempid = $apt->setid('tempid');
$result =  $apt->query("SELECT * FROM rafia_mods_templates where id='$tempid'");
$temp = $apt->dbarray($result);
extract($temp);
echo "<form action=mod.php?act=temp&do=update&$session METHOD=POST name=\"temp\">\n";
$admin->opentable("⁄—÷ «·ﬁÊ«·»");
$admin->inputtext("≈”„ «·ﬁ«·»","temp_title",$temp_title);
$admin->textarea("ﬂÊœ «·ﬁ«·»","template",htmlspecialchars($template),'ltr',18);
$admin->template_string();
$admin->inputhidden('tempid',$id);
$admin->inputhidden('modid',$modid);
$admin->inputhidden('modname',$modname);
$admin->inputhidden('sub',$sub);
$admin->closetable();
$admin->submit(" ‹‹‹Õ‹—Ì‹—");
}elseif($apt->get['do'] == 'update'){

$tempid = $admin->format_data($apt->post[tempid]);
$temp_title = $admin->format_data($apt->post[temp_title]);
$template   = $admin->format_data($apt->post[template]);
$tempid     = $admin->format_data($apt->post[tempid]);
$modid      = $admin->format_data($apt->post[modid]);
$modname    = $admin->format_data($apt->post[modname]);
$sub    = $admin->format_data($apt->post[sub]);

$result  = $apt->query("update rafia_mods_templates set temp_title='$temp_title', template='$template' where id='$tempid'");
 if($result)
 {
	$admin->head();
		modmsg(" „  ÕœÌÀ «·ﬁ«·» »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «·ﬁÊ«·»","mod.php?act=temp&do=view&modid=$modid&mod=$modname&themeid=$sub&$session");
 }


}elseif($apt->get['do'] == 'insert'){

$temp_title = $admin->format_data($apt->post[temp_title]);
$template   = $admin->format_data($apt->post[template]);
$modid      = $admin->format_data($apt->post[modid]);
$modname    = $admin->format_data($apt->post[modname]);
$themeid    = $admin->format_data($apt->post[themeid]);

$result  = $apt->query("INSERT INTO rafia_mods_templates VALUES ('', '0', '0', '$themeid', '$modid', '$modname', '', '$temp_title', '$template');");
 if($result)
 {
	$admin->head();
		modmsg(" „ «÷«›… «·ﬁ«·» »‰Ã«Õ<br>”Ê›  ‰ ﬁ· «·«‰ «·Ï ’›Õ… ⁄—÷ «·ﬁÊ«·»","mod.php?act=temp&do=view&modid=$modid&mod=$modname&themeid=$themeid&$session");
 }

}
?>