<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright ╘ 2006   |
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

if (stristr($_SERVER['SCRIPT_NAME'], "counter.php")) {
    die ("<center><h3>зщФг ЕпЕ гАзЦАМи шМя ЦтяФзи</h3></center>");
}

class stat
{
    function stat($action)
    {
    global $apt,$admin;

    $action = $apt->format_data($action);
        
        
    if ($action==""){
    $admin->head();
$session = $admin->get_sess();
echo "
<table border=0 width=95%>
	<tr>
		<td align=center class=datacell><a href=index.php?cat=stat&$session><b>ймоМк чгфЦи гАзогогй</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=ncat&$session><b>йзого гчсгЦ гАгнхгя</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=fcat&$session><b>йзого гчсгЦ гАЦДйоЛ</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=dcat&$session><b>йзого гчсгЦ гАймЦМА</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=lcat&$session><b>йзого гчсгЦ гАоАМА</b></a></td>
	</tr>
	<tr>
		<td colspan=5 align=center class=datacell>
<table border=0 width=100%>
	<tr>
		<td align=center class=datacell><a href=index.php?cat=stat&act=news&$session><b>йзого йзАМчгй гАгнхгя</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=forum&$session><b>йзого йзАМчгй гАЦДйоЛ</b></a></td>
		<td align=center class=datacell><a href=index.php?cat=stat&act=down&$session><b>йзого йзАМчгй гАймЦМА</b></a></td>
	</tr>
</table>		
		</td>
	</tr>
</table>
<br>";

    @set_time_limit(300);

    $Counter = new Counter();
    $admin->openform("$PHP_SELF?cat=stat&act=update&".$admin->get_sess());
    $list        = array("гАгнхгя",$apt->dbnumquery("rafia_news","allow='yes'",'id'),$Counter->getValue('newsCount'));
    $admin->Rows = $admin->TableCell($list);
    $list  = array("Цяър гАймЦМА",$apt->dbnumquery("rafia_download","allow='yes'",'id'),$Counter->getValue('downCount'));
    $admin->Rows .= $admin->TableCell($list);
    $list        = array("гАЦДйоЛ",$apt->dbnumquery("rafia_forum","allow='yes'",'id'),$Counter->getValue('forumCount'));
    $admin->Rows .= $admin->TableCell($list);
    $list        = array("слА гАрФгя",$apt->dbnumquery("rafia_guestbook","allow='yes'",'id'),$Counter->getValue('gbCount'));
    $admin->Rows .= $admin->TableCell($list);
    $list        = array("оАМА гАЦФгчз",$apt->dbnumquery("rafia_links","allow='yes'",'id'),$Counter->getValue('linksCount'));
    $admin->Rows .= $admin->TableCell($list);
    $list        = array("гАЦсйноЦМД",$apt->dbnumquery("rafia_users","usergroup !='5' and allowpost='yes'",'userid'),$Counter->getValue('usersCount'));
    $admin->Rows .= $admin->TableCell($list);
    $list        = array("гАяоФо",$apt->dbnumquery("rafia_comment","allow='yes'",'id'),$Counter->getValue('commentCount'));
    $admin->Rows .= $admin->TableCell($list);
    $cellHeader  = array("гАхяДгЦл" ,"гАзоо гАмчМчМ","гАзоо гАЦзяФж");
    $admin->ColWidth = array(40,20,20);
    echo $admin->Table($cellHeader,"ймоМк чгфЦи гАзогогй");
    $admin->submit("ймоМк",'SubmitCount');

    }else if ($action=="update"){
    $Counter = new Counter();
    $newsCount = $apt->dbnumquery("rafia_news","allow='yes'",'id');
    $Counter->SetCount('newsCount',$newsCount); //
    $downCount = $apt->dbnumquery("rafia_download","allow='yes'",'id');
    $Counter->SetCount('downCount',$downCount); //
    $forumCount = $apt->dbnumquery("rafia_forum","allow='yes'",'id');
    $Counter->SetCount('forumCount',$forumCount); //
    $gbCount = $apt->dbnumquery("rafia_guestbook","allow='yes'",'id');
    $Counter->SetCount('gbCount',$gbCount); //
    $linksCount = $apt->dbnumquery("rafia_links","allow='yes'",'id');
    $Counter->SetCount('linksCount',$linksCount); //
    $usersCount = $apt->dbnumquery("rafia_users","usergroup !='5' and allowpost='yes'",'userid');
    $Counter->SetCount('usersCount',$usersCount); //
    $commentCount = $apt->dbnumquery("rafia_comment","allow='yes'",'id');
    $Counter->SetCount('commentCount',$commentCount); 
    $Counter->FinalCount();
    header("Refresh: 1;url=index.php?cat=stat&".$admin->get_sess());
    $admin->windowmsg("<p>&nbsp;йЦ гАймоМк</p>");

    }else if ($action=="ncat"){
    $result = $apt->query("SELECT id FROM rafia_cat WHERE catType='1'");
    while($row = $apt->dbarray($result)){
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_news","allow='yes' and cat_id='$id'");
	$cnumrows  =  $apt->dbnumquery("rafia_comment","news_id !='0' and allow='yes' and cat_id='$id'");
	$apt->query("update rafia_cat set countopic='$numrows', countcomm='$cnumrows' where id='$id';");
	unset($numrows,$cnumrows);
    }
    header("Refresh: 1;url=index.php?cat=stat&".$admin->get_sess());
    $admin->windowmsg("йЦ ймоМк зогой гчсгЦ гАгнхгя");

    }else if ($action=="fcat"){
    $result = $apt->query("SELECT id FROM rafia_cat WHERE catType='2'");
    while($row = $apt->dbarray($result)){
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_forum","allow='yes' and cat_id='$id'");
	$cnumrows  =  $apt->dbnumquery("rafia_comment","thread_id !='0' and allow='yes' and cat_id='$id'");
	$apt->query("update rafia_cat set countopic='$numrows', countcomm='$cnumrows' where id='$id';");
	unset($numrows,$cnumrows);
    }
    header("Refresh: 1;url=index.php?cat=stat&".$admin->get_sess());
    $admin->windowmsg("йЦ ймоМк зогой гчсгЦ гАЦДйоЛ");

    }else if ($action=="dcat"){
    $result = $apt->query("SELECT id FROM rafia_cat WHERE catType='3'");
    while($row = $apt->dbarray($result)){
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_download","allow='yes' and cat_id='$id'");
	$cnumrows  =  $apt->dbnumquery("rafia_comment","down_id !='0' and allow='yes' and cat_id='$id'");
	$apt->query("update rafia_cat set countopic='$numrows', countcomm='$cnumrows' where id='$id';");
	unset($numrows,$cnumrows);
    }
    header("Refresh: 1;url=index.php?cat=stat&".$admin->get_sess());
    $admin->windowmsg("йЦ ймоМк зогой гчсгЦ Цяър гАймЦМА");

    }else if ($action=="lcat"){
    $result = $apt->query("SELECT id FROM rafia_cat WHERE catType='4'");
    while($row = $apt->dbarray($result)){
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_links","allow='yes' and cat_id='$id'");
	$apt->query("update rafia_cat set countopic='$numrows' where id='$id';");
	unset($numrows);
    }
    header("Refresh: 1;url=index.php?cat=stat&".$admin->get_sess());
    $admin->windowmsg("йЦ ймоМк зогой гчсгЦ оАМА гАЦФгчз");

    }else if ($action=="news"){
    $admin->head();
    print("зщФг .. МялЛ гАгДйыгя<br>"); flush();
    $result = $apt->query("SELECT id FROM rafia_news where allow='yes'");
    $i=0;
    while($row = $apt->dbarray($result)){ $i++;
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_comment","news_id='$id' and allow='yes'");
	$apt->query("update rafia_news set c_comment='$numrows' where id='$id';");
	unset($numrows);
	if($i == 100){$i =0; echo "... ..."; flush(); @sleep(2);}
    }
    $admin->windowmsg("йЦ ймоМк зогогй йзАМчгй гАгнхгя");

    }else if ($action=="forum"){
    $admin->head();
    print("зщФг .. МялЛ гАгДйыгя<br>"); flush();
    $result = $apt->query("SELECT id,cat_id FROM rafia_forum where allow='yes'");
    $i=0;
    while($row = $apt->dbarray($result)){ $i++;
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_comment","thread_id='$id' and allow='yes'");
	$apt->query("update rafia_forum set c_comment='$numrows',cat_id='$cat_id' where id='$id'");
	unset($numrows);
	if($i == 300){$i =0; echo "... ..."; flush(); @sleep(2);}
    }
    $admin->windowmsg("йЦ ймоМк зогогй йзАМчгй гАЦДйоЛ");

    }else if ($action=="down"){
    $admin->head();
    print("зщФг .. МялЛ гАгДйыгя<br>"); flush();
    $result = $apt->query("SELECT id,cat_id FROM rafia_download where allow='yes'");
    $i=0;
    while($row = $apt->dbarray($result)){ $i++;
	@extract($row);
	$numrows  =  $apt->dbnumquery("rafia_comment","down_id='$id' and allow='yes'");
	$apt->query("update rafia_download set c_comment='$numrows',cat_id='$cat_id' where id='$id'");
	unset($numrows);
	if($i == 100){$i =0; echo "... ..."; flush(); @sleep(2);}
    }
    $admin->windowmsg("йЦ ймоМк зогогй йзАМчгй Цяър гАймЦМА");

    }
    }
}
?>