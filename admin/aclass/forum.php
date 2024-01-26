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

if (stristr($_SERVER['SCRIPT_NAME'], "forum.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

function subcat($id,$tit)
{
    global $apt, $admin;
    $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='2' and subcat='$id' ORDER BY ordercat ASC");
    $admin->opentable($tit,4);
    echo "<tr><td width=10% class=datacell align=center colspan=3><a href=\"" . $PHP_SELF . "?cat=forum&act=editmine&id=" . $id . "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a> || <a href=\"" . $PHP_SELF . "?cat=forum&act=delete&id=" . $id . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";
    while($row = $apt->dbarray($result))
    {
        echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > - " . $row["title"] . "</b></td>
              <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=forum&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>
              <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=forum&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";
        subcat2($row["id"]);
    }
    $admin->closetable();
}
//---------------------------------------------------
//
//---------------------------------------------------

function subcat2($id)
{
    global $apt, $admin;

    $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='2' and subcat='$id' ORDER BY ordercat ASC");

    while($row = $apt->dbarray($result))
    {
        echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > - - " . $row["title"] . "</b></td>
             <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=forum&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>
             <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=forum&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";
    }
}

class forum
{
    function forum ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

        if ($action=="")
        {
            $admin->head();
            $admin->openform("index.php?action=upset&".$admin->get_sess());
            $admin->opentable(_MAIN_SETTINGS,'2');
            $admin->editsetting('0','forum');
            $admin->closetable();
            $admin->submit(_DO_EDIT);
        }
        else if ($action=="cat")
        {
            $admin->head();
    
            $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='2' and subcat=0 ORDER BY ordercat ASC");


    
            while($row = $apt->dbarray($result))
            {
                echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% colspan=2>" .  subcat($row["id"],$row["title"]) . "</b></td></tr>";
            }
        }

//---------------------------------------------------
//
//---------------------------------------------------
        else if ($action=="addmine")
        {
           $admin->head();

            $admin->opentable("«÷«›…  ’‰Ì› —∆Ì”Ì");
            $admin->openform("$PHP_SELF?cat=forum&act=insert&".$admin->get_sess());
            $admin->inputtext("«”„ «· ’‰Ì›","title","");
            $admin->selectnum(" — Ì»","ordercat","",'0','50');
            $admin->inputhidden('ismine','1');
            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc","",'5','5');
            $admin->closetable();
            $admin->submit("«÷›");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="editmine")
        {
            $admin->head();
             $id     = $apt->setid('id');
            $admin->opentable(" Õ—Ì—  ’‰Ì›");
            $admin->openform("$PHP_SELF?cat=forum&act=update&id=$id&".$admin->get_sess());
            $result=$apt->query("select * from rafia_cat where id=$id");
            $row=$apt->dbarray($result);
            extract($row);
            $admin->inputtext("«”„ «· ’‰Ì›","title",$title);
            $admin->selectnum(" — Ì»","ordercat",$ordercat,'0','50');
            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc",$dsc,'5','5');
            $admin->closetable();
            $admin->submit(" ‹‹Õ—Ì‹—");
        }

//---------------------------------------------------
//
//---------------------------------------------------

        else if ($action=="addcat")
        {
            $admin->head();

            $admin->opentable("«÷«›…  ’‰Ì› ÃœÌœ");
            $admin->openform("$PHP_SELF?cat=forum&act=insert&".$admin->get_sess());
            $admin->inputhidden('catid','2');
            $admin->inputtext("«”„ «· ’‰Ì›","title","");
            $admin->selectnum(" — Ì»","ordercat","",'0','50');
            $admin->selectCat($subcat,$id,2);
            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc","",'5','5');
            $admin->textarea("Ê’› «· ’‰Ì› «·œ«Œ·Ì  (ÌŸÂ— œ«Œ· «·ﬁ”„ ›Ì ’œ— «·’›Õ… )","dscin", "",'5','5');
            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ œŒÊ· «·ﬁ”„","groupview[]");
            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ «÷«›… „Ê«÷Ì⁄ ·Â–« «·ﬁ”„","groupost[]");
            $admin->inputtext("«–« ﬂ‰   —€» » »·Ì€ ⁄‰ «Ì «÷«›… ›Ì Â–« «·ﬁ”„ , ÷⁄ «·»—Ìœ Â‰«","cat_email",$cat_email);
            $admin->yesorno("≈⁄·ﬁ «·ﬁ”„ «Ê Ã⁄·Â «—‘Ì›","catClose",0);
            $admin->closetable();
            $admin->submit("«÷›");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="edit")
        {
            $admin->head();
            $id     = $apt->setid('id');
            $admin->opentable(" Õ—Ì—  ’‰Ì›");
            $admin->openform("$PHP_SELF?cat=forum&act=update&id=$id&".$admin->get_sess());
            $result=$apt->query("select * from rafia_cat where id=$id");
            $row=$apt->dbarray($result);
            extract($row);
            $admin->inputtext("«”„ «· ’‰Ì›","title",$title);
            $admin->selectnum(" — Ì»","ordercat",$ordercat,'0','50');
            $admin->selectCat($subcat,$id,2);

            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc",$dsc,'5','5');
            $admin->textarea("Ê’› «· ’‰Ì› «·œ«Œ·Ì  (ÌŸÂ— œ«Œ· «·ﬁ”„ ›Ì ’œ— «·’›Õ… )","dscin", $dscin,'5','5');
            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ œŒÊ· «·ﬁ”„","groupview[]",$groupview);
            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ «÷«›… „Ê«÷Ì⁄ ·Â–« «·ﬁ”„","groupost[]",$groupost);
            $admin->inputtext("«–« ﬂ‰   —€» » »·Ì€ ⁄‰ «Ì «÷«›… ›Ì Â–« «·ﬁ”„ , ÷⁄ «·»—Ìœ Â‰«","cat_email",$cat_email);
            $admin->yesorno("≈⁄·ﬁ «·ﬁ”„ «Ê Ã⁄·Â «—‘Ì›","catClose",$catClose);
            $admin->closetable();
            $admin->submit(" ‹‹Õ—Ì‹—");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="insert")
        {
            extract($_POST);
            if (!$apt->full($title)){
                $admin->windowmsg("·„  ﬁ„ »ﬂ «»… «”„ «· ’‰Ì›");
                exit;
            }
            if (!isset($subcat)){
                $subcat = '0';
            }

            if($groupview !=0)$groupview[]=1;
            if($groupost !=0)$groupost[]=1;

            $groupview =  $admin->myimplode($groupview);
            $groupost  =  $admin->myimplode($groupost);
            $title      = $admin->format_data($apt->post[title]);
            $dsc        = $admin->format_data($apt->post[dsc]);
            $dscin      = $admin->format_data($apt->post[dscin]);
            $cat_email  = $admin->format_data($apt->post[cat_email]);

             $result = $apt->query("insert into rafia_cat
                            (ordercat,catType,subcat,title,dsc,dscin,ismine,groupview,groupost,cat_email,catClose) values
                            ('$ordercat','2','$subcat','$title','$dsc','$dscin','$ismine','$groupview','$groupost','$cat_email','$catClose')");

            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=forum&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p> „  ≈÷«›… «· ’‰Ì› »‰Ã«Õ </p>");
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="update")
        {
            $id     = $apt->setid('id');
            @extract($_POST);

            if($groupview !=0)$groupview[]=1;
            if($groupost !=0)$groupost[]=1;

            $groupview =  $admin->myimplode($groupview);
            $groupost  =  $admin->myimplode($groupost);
            $title      = $admin->format_data($apt->post[title]);
            $dsc        = $admin->format_data($apt->post[dsc]);
            $dscin      = $admin->format_data($apt->post[dscin]);
            $cat_email  = $admin->format_data($apt->post[cat_email]);

            $result = $apt->query("update rafia_cat set ordercat='$ordercat',
                                                  title='$title',
                                                  subcat='$subcat',
                                                  dsc='$dsc',
                                                  dscin='$dscin',
                                                  groupview='$groupview',
                                                  groupost='$groupost',
                                                  cat_email='$cat_email',
                                                  catClose ='$catClose'
                                                  where id=$id");
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=forum&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p> „  ÕœÌÀ «· ’‰Ì› </p>");
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="delete")
        {
            @extract($_POST);
            if(isset($apt->get['id'])){
            $id = $apt->get['id'];
		if($apt->dbnumquery("rafia_cat","subcat='$id'")>=1){
		$admin->windowmsg("<p>⁄›Ê« ·« Ì„ﬂ‰ﬂ Õ–› «·ﬁ”„ Ê »œ«Œ·Â ﬁ”„ ›—⁄Ì</p>");
		die;}
                $admin->delete_cat($apt->get['id'],'2','forum');
            }
            else
            {
                if(!empty($apt->post['tocatid']))
                {
                    $tocatid = $apt->post['tocatid'];
                    $delid   = $apt->post['delid'];
                    $result1 = $apt->query("update rafia_forum set cat_id='$tocatid' where cat_id=$delid");
                    $apt->query("update rafia_comment set cat_id='$tocatid' where thread_id >'0'");
                 }
                 else
                 {
                     $delid   = $apt->post['delid'];
                     $result1 = $apt->query("delete from rafia_forum where cat_id=$delid");
                     $apt->query("delete from rafia_comment where cat_id=$delid");
                 }

                 if ($result1)
                 {
                     $result=$apt->query("delete from rafia_cat where id=$delid");
                     if ($result)
                     {
                         header("Refresh: 1;url=index.php?cat=forum&act=cat&".$admin->get_sess());
                         $admin->windowmsg("<p> „ Õ–› «· ’‰Ì› </p>");
                     }
                 }
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="addmod")
        {

            $admin->head();

            $admin->opentable("«÷«›… „‘—› ⁄·Ï «Õœ «·«ﬁ”«„");
            $admin->openform("$PHP_SELF?cat=forum&act=insertmod&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>Õœœ «·ﬁ”„ </td><td class=datacell>\n";
            echo "<select name='moderatecatid'>\n";
            echo "<option value='0'>«Œ — «·ﬁ”„</option>";
            $result = $apt->query("select * from rafia_cat WHERE catType='2' and ismine = '0' order by id");
            while($row=$apt->dbarray($result))
            {
                $idcat=$row["id"];
                $titlecat=$row["title"];
                echo "<option value='$idcat'>$titlecat</option>";
            }
            echo "</select></td></tr>\n";
            $admin->inputtext('—ﬁ„ «·⁄÷ÊÌ…','moderateid',$moderateid);
            $admin->inputtext('«”„ «·„—«ﬁ»','moderatename',$moderatename);
            $admin->yesorno(" Õ—Ì— «·„Ê÷Ê⁄ Ê«·—œÊœ","can_edit",$can_edit);
            $admin->yesorno("«€·«ﬁ «·„Ê÷Ê⁄","can_close",$can_close);
            $admin->yesorno(" À»Ì  «·„Ê÷Ê⁄","can_sticky",$can_sticky);
            $admin->yesorno("Õ–› «·„Ê÷Ê⁄ Ê«·—œÊœ","can_delete",$can_delete);
            $admin->yesorno("‰ﬁ· «·„Ê÷Ê⁄","can_move",$can_move);
            $admin->closetable();
            $admin->submit  ("«÷«›…");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="insertmod")
        {
            extract($_POST);
            if (!$apt->full($moderatecatid))
            {
              $admin->windowmsg(_NO_CAT_SELECTED);
               exit;
            }
             $result = $apt->query("SELECT * FROM rafia_users WHERE username='$moderatename' and userid='$moderateid'");

             if ( $apt->dbnumrows($result) == "0" )
             {
                 $admin->windowmsg(_NO_USER);
                 exit;
             }
             else
             {
             $row = $apt->dbarray($result);
             if( ($row[useradmin] == 1) || ($row[useradmin] == 2)){
             $admin->windowmsg('⁄›Ê« .. ·« Ì„ﬂ‰  ⁄ÌÌ‰ „‘—› ⁄«„ «Ê „—«ﬁ» ⁄«„ ·ÌﬂÊ‰ „‘—› ›ﬁÿ'); exit;
             }	
            $usergroup = $apt->m_g;
            $apt->query("update rafia_users set
                                        useradmin = '3',
                                        usergroup = '$usergroup'
                                        WHERE username='$moderatename' and userid='$moderateid'");
                                        
           $result =  $apt->query("insert into rafia_moderate
                               (moderatecatid,modadmin,moderateid,moderatename,can_edit,can_close,can_sticky,can_delete,can_move) values
                               ('$moderatecatid','3','$moderateid','$moderatename','$can_edit','$can_close','$can_sticky','$can_delete','$can_move')");

        if ($result){$admin->windowmsg(_ADD_OK);}
	}
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="mod")
        {
            $admin->head();
            $start = $apt->get[start];
            if(!isset($start)){$start = 0;}
            $perpage = "50";
            $result = $apt->query("SELECT * FROM rafia_moderate  where modadmin=3 ORDER BY id ASC LIMIT $start,$perpage");
            $page_result = $apt->query("SELECT * FROM rafia_moderate where modadmin=3");
            $numrows    = $apt->dbnumrows($page_result);
            print $apt->pagenum($perpage,"users");
            $admin->opentable("«·„‘—›Ì‰ ›Ì «·„‰ œÌ« ",4);
            while($row = $apt->dbarray($result))
            {
                extract($row);
                 $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='2' and id='$moderatecatid'");
                echo "<tr><td  class=datacell width=30%><b>$moderatename</b></td>
                <td  class=datacell width=30%><b>$cat[title]</b></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=forum&act=editmod&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=forum&act=deletemod&id=$id&".$admin->get_sess()."  onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\">„”Õ</b></a></td>";
            }
           $admin->closetable();
            print $apt->pagenum($perpage,"users");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        else if($action=="editmod")
        {
            $id     = $apt->setid('id');
            $admin->head();
            $mod = $apt->dbfetch ("SELECT * FROM rafia_moderate where id='$id'");
            @extract($mod);
            $admin->opentable(" Õ—Ì— Œ’«∆’ «·«‘—«›");
            $admin->openform("$PHP_SELF?cat=forum&act=updatemod&id=$id&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>„‘—› ›Ì «·ﬁ”„ </td><td class=datacell>\n";

            $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='2' and id='$moderatecatid'");

            echo "$cat[title]</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>—ﬁ„ «·⁄÷ÊÌ…</td><td class=datacell>\n";
           echo "$moderateid</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>«”„ «·„” Œœ„</td><td class=datacell>\n";
            echo "$moderatename</td></tr>\n";

            $admin->yesorno(" Õ—Ì— «·„Ê÷Ê⁄ Ê«·—œÊœ","can_edit",$can_edit);
            $admin->yesorno("«€·«ﬁ «·„Ê÷Ê⁄","can_close",$can_close);
            $admin->yesorno(" À»Ì  «·„Ê÷Ê⁄","can_sticky",$can_sticky);
            $admin->yesorno("Õ–› «·„Ê÷Ê⁄ Ê«·—œÊœ","can_delete",$can_delete);
            $admin->yesorno("‰ﬁ· «·„Ê÷Ê⁄","can_move",$can_move);
            $admin->closetable();
            $admin->submit  ("«÷«›…");
        }
//---------------------------------------------------
//
//---------------------------------------------------

        elseif ($action=="deletemod")
        {
             $id  = $apt->setid('id');
             $mod = $apt->dbfetch ("SELECT * FROM rafia_moderate where id=$id");
             @extract($mod);
             $moderatenum = $apt->dbnumquery("rafia_moderate","moderateid='$moderateid'");
            if($moderatenum == 0)
            {
                $apt->query("update rafia_users set
                       useradmin = '0',
                       usergroup = '2'
                       WHERE username='$moderatename' and userid='$moderateid'");
            }


            $result=$apt->query("delete from rafia_moderate where id=$id");

           if ($result)
           {
               header("Refresh: 1;url=index.php?cat=forum&act=mod&".$admin->get_sess());
               $admin->windowmsg("<p> „ Õ–› «·„‘—› </p>");
           }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="updatemod")
        {
            extract($_POST);
            $id     = $apt->setid('id');
            $result =  $apt->query("update rafia_moderate set can_edit='$can_edit',can_close='$can_close',can_sticky='$can_sticky',can_delete='$can_delete',can_move='$can_move' where id ='$id'");
            if ($result){
                header("Refresh: 1;url=index.php?cat=forum&act=mod&".$admin->get_sess());
                $admin->windowmsg(" „  «·«÷«›… »‰Ã«Õ");
            }
        }
//---------------------------------------------------

        else if ($action=="counter")
        {
           $admin->head();


	echo "<br>Ã«—Ì Õ”«» ⁄œœ «·„Ê«÷Ì⁄ ›Ì ﬂ· ﬁ”„<br>";

	$result=mysql_query("SELECT * FROM rafia_cat WHERE catType='2' and subcat != 0 ORDER BY id ASC"); 
	while($row=mysql_fetch_array($result)){ 
	$id = $row['id'];
	$title = $row['title'];
	$rt=mysql_query("SELECT * FROM rafia_forum WHERE cat_id='$id'",$n_con); 
	$ct=mysql_num_rows($rt); 

	echo "⁄œœ «·„Ê«÷Ì⁄ ›Ì «·ﬁ”„ $title=($ct)<br>";
	$rett=mysql_query("update rafia_cat set countopic='$ct' WHERE id='$id'",$n_con); 
	}

	}
//---------------------------------------------------
  }
}

?>
