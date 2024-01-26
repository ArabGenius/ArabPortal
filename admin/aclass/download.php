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

if (stristr($_SERVER['SCRIPT_NAME'], "download.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

function subcat($id)
{
    global $apt, $admin;
    
    $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='3' and subcat='$id' ORDER BY ordercat ASC");

    while($row = $apt->dbarray($result))
    {//**BY ANS**//
        echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > ++ " . $row["title"] . "</b></td>
        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=download&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b>"._EDIT."</b></a></td>
        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=download&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>"._DELETE."</b></a></td></tr>";
    }
}

class download
{
    function download ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);
        if ($action=="")
        {

             $admin->head();

             $admin->openform("index.php?action=upset&".$admin->get_sess());
             $admin->opentable(_MAIN_SETTINGS,'2');//**BY ANS**//
             $admin->editsetting('0','down');
             $admin->closetable();
             $admin->submit(_DO_EDIT);//**BY ANS**//
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if ($action=="cat")
        {
            $admin->head();

            $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='3' and subcat='0' ORDER BY ordercat ASC");
            
            $admin->opentable(_DOWNLOAD_SECTIONS,4);//**BY ANS**//

            echo "</tr>";

            while($row = $apt->dbarray($result))
            {
                echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > - " . $row["title"] . "</b></td>
                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=download&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b>"._EDIT."</b></a></td>
                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=download&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>"._DELETE."</b></a></td></tr>";
                subcat($row["id"]);
            }

            $admin->closetable();
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if ($action=="addcat")
        {
            $admin->head();
            $admin->opentable(_ADD_SECTION);//**BY ANS**//
            $admin->openform("$PHP_SELF?cat=download&act=insert&".$admin->get_sess());

            $admin->inputhidden('catid','3');
            $admin->inputtext(_SECTION_NAME,"title","");//**BY ANS**//
            $admin->selectnum(_SORT,"ordercat","",'0','50');//**BY ANS**//
            $admin->selectCat($subcat,$id,3);
            $admin->textarea(_CAT_DIS_NOTE,"dsc","",'5','5');//**BY ANS**//
            $admin->textarea(_CAT_DIS_NOTE2,"dscin", "",'5','5');//**BY ANS**//
            $admin->group_check_box(_GROUP_ACCESS,"groupview[]");//**BY ANS**//
            $admin->group_check_box(_GROUP_CAN_ADD,"groupost[]");//**BY ANS**//
            $admin->inputtext(_ALERT_IF_ADD,"cat_email",$cat_email);//**BY ANS**//
            $admin->closetable();
            $admin->submit(_ADD);//**BY ANS**//
        }

//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="insert")
        {
            extract($_POST);
            if (!$apt->full($title))
            {
              $admin->windowmsg(_NO_CAT_NAME);//**BY ANS**//
               exit;
            }
            if (!isset($subcat))
            {
                $subcat = '0';
            }
            $groupview =  $admin->myimplode($groupview);
            $groupost  =  $admin->myimplode($groupost);

             $title      = $admin->format_data($apt->post[title]);
             $dsc        = $admin->format_data($apt->post[dsc]);
             $dscin      = $admin->format_data($apt->post[dscin]);
             $cat_email  = $admin->format_data($apt->post[cat_email]);
     
                $result = $apt->query("insert into rafia_cat
                               (ordercat,catType,subcat,title,dsc,dscin,ismine,groupview,groupost,cat_email) values
                               ('$ordercat','3','$subcat','$title','$dsc','$dscin','$ismine','$groupview','$groupost','$cat_email')");
                               
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=download&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p>"._CAT_IS_ADD."</p>");//**BY ANS**//
            }
            else
            {
                $admin->windowmsg("<p>"._ERROR_IN_CATS_ADD."</p>");//**BY ANS**//
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="edit")
        {
            $id     = $apt->setid('id');
            $admin->head();
            $admin->opentable(_EDIT_CAT);//**BY ANS**//
            $admin->openform("$PHP_SELF?cat=download&act=update&id=$id&".$admin->get_sess());
            $result=$apt->query("select * from rafia_cat where id=$id");
            $row=$apt->dbarray($result);
            extract($row);
            $admin->inputtext(_SECTION_NAME,"title",$title);//**BY ANS*//
            $admin->selectnum(_SORT,"ordercat",$ordercat,'0','50');//**BY ANS**//
            $admin->selectCat($subcat,$id,3);

/*
echo "<tr><td class=datacell width='33%'>"._IF_SUB."</td><td class=datacell>\n";//BY ANS//
            echo "<select name='subcat'>\n";

              if($subcat >0)
              {
                 $result = $apt->query("select id,title from rafia_cat where id='$subcat'");
                  $row=$apt->dbarray($result);
                  echo "<option value='$row[id]'>$row[title]</option>";
               }
               else
               {
                       echo "<option value='0'>"._MAIN_CAT."</option>";//BY ANS//
               }
                $result = $apt->query("select id,title from rafia_cat where id!='$subcat' order by id");
                while($row=$apt->dbarray($result))
                {
                    $idcat=$row["id"];
                   $titlecat=$row["title"];
                    echo "<option value='$idcat'>$titlecat</option>";
                }
                echo "</select></td></tr>\n";

*/
/////////////////////////////////
            $admin->textarea(_CAT_DIS_NOTE,"dsc",$dsc,'5','5');//**BY ANS**//
            $admin->textarea(_CAT_DIS_NOTE2,"dscin", $dscin,'5','5');//**BY ANS**//
            $admin->group_check_box(_GROUP_ACCESS,"groupview[]",$groupview);//**BY ANS**//
            $admin->group_check_box(_GROUP_CAN_ADD,"groupost[]",$groupost);//**BY ANS**//
            $admin->inputtext(_ALERT_IF_ADD,"cat_email",$cat_email);//**BY ANS**//
            $admin->closetable();
            $admin->submit(_EDIT);//**BY ANS**//

        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="update")
        {
           $id     = $apt->setid('id');
            extract($_POST);
            $groupview =  $admin->myimplode($groupview);
            $groupost  =  $admin->myimplode($groupost);
            $title      = $admin->format_data($apt->post[title]);
            $dsc        = $admin->format_data($apt->post[dsc]);
            $dscin      = $admin->format_data($apt->post[dscin]);
            $cat_email  = $admin->format_data($apt->post[cat_email]);
            
            $result=$apt->query("update rafia_cat set ordercat='$ordercat',
                                                 title='$title',
                                                 subcat='$subcat',
                                                 dsc='$dsc',
                                                 dscin='$dscin',
                                                 groupview='$groupview',
                                                 groupost='$groupost',
                                                 cat_email='$cat_email'
                                                 where id=$id");
            if ($result)
            {     header("Refresh: 1;url=index.php?cat=download&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p>"._CAT_UPDATE_OK."</p>");//**BY ANS**//
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="delete")
        {
            extract($_POST);
            if(isset($apt->get['id']))
            {
                $admin->delete_cat($apt->get['id'],'3','download');
            }
            else
            {
                  if(!empty($apt->post['tocatid']))
                {
                   $tocatid = $apt->post['tocatid'];
                    $delid   = $apt->post['delid'];
                    $result  = $apt->query("update rafia_download set cat_id='$tocatid' where cat_id=$delid");
                    $apt->query("update rafia_comment set cat_id='$tocatid' where down_id >'0'");
                }
                else
                {
                    $delid   = $apt->post['delid'];
                    $result = $apt->query("delete from rafia_download where cat_id='$delid'");
                    $apt->query("delete from rafia_comment where cat_id='$delid'");
                }
                if ($result)
                {
                   $result=$apt->query("delete from rafia_cat where id='$delid'");
                    if ($result)
                    {
                       header("Refresh: 1;url=index.php?cat=download&act=cat&".$admin->get_sess());
                        $admin->windowmsg("<p>"._CAT_DELETE_OK." </p>");//**BY ANS**//
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

            $admin->opentable(_ADD_MOD_TO_CAT);//**BY ANS**//
            $admin->openform("$PHP_SELF?cat=download&act=insertmod&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>"._SELECT_CAT." </td><td class=datacell>\n";//**BY ANS**//
           echo "<select name='moderatecatid'>\n";
            echo "<option value='0'>«Œ — «·ﬁ”„</option>";
            $result = $apt->query("select * from rafia_cat WHERE catType='3' and ismine = '0' order by id");
                while($row=$apt->dbarray($result))
                {
                    $idcat=$row["id"];
                    $titlecat=$row["title"];
                    echo "<option value='$idcat'>$titlecat</option>";
                }
                echo "</select></td></tr>\n";
            $admin->inputtext(_ID_NUMBER,'moderateid',$moderateid);//**BY ANS**//
            $admin->inputtext(_MOD_NAME,'moderatename',$moderatename);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_EDIT,"can_edit",$can_edit);//**BY ANS**//
            $admin->yesorno(_MOD_CLOSE_POST,"can_close",$can_close);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_STICKY,"can_sticky",$can_sticky);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_DELETE,"can_delete",$can_delete);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_MOVE,"can_move",$can_move);//**BY ANS**//
            $admin->closetable();
            $admin->submit  (_ADD);//**BY ANS**//
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
                               ('$moderatecatid','4','$moderateid','$moderatename','$can_edit','$can_close','$can_sticky','$can_delete','$can_move')");

        if ($result){$admin->windowmsg(_ADD_OK);}
	}
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="mod")
        {
            $admin->head();

            if(!isset($start))
            {
                $start = 0;
            }
            $perpage = "30";
            $result = $apt->query("SELECT * FROM rafia_moderate  where modadmin=4 ORDER BY id ASC LIMIT $start,$perpage");
            $page_result = $apt->query("SELECT * FROM rafia_moderate where modadmin=4");
            $numrows    = $apt->dbnumrows($page_result);
            print $apt->pagenum($perpage,"users");
            $admin->opentable(_MOD_IN_NEWS,4);//**BY ANS**//
           while($row = $apt->dbarray($result))
            {
                extract($row);
                 $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='3' and id='$moderatecatid'");
                        echo "<tr><td  class=datacell width=30%><b>$moderatename</b></td>
                        <td  class=datacell width=30%><b>$cat[title]</b></td>
                        <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=download&act=editmod&id=$id&".$admin->get_sess().">"._EDIT."</b></a></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=download&act=deletemod&id=$id&".$admin->get_sess().">"._DELETE."</b></a></td>";//**BY ANS**//
           }
            $admin->closetable();
            print $apt->pagenum($perpage,"users");
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="editmod")
        {

           $admin->head();
           $id     = $apt->setid('id');
            $mod = $apt->dbfetch ("SELECT * FROM rafia_moderate where id=$id");
            @extract($mod);
            $admin->opentable(_EDIT_MOD);//**BY ANS**//
            $admin->openform("$PHP_SELF?cat=download&act=updatemod&id=$id&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>"._MOD_IN_CAT." </td><td class=datacell>\n";//**BY ANS**//

            $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='3' and id='$moderatecatid'");

            echo "$cat[title]</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>"._ID_NUMBER."</td><td class=datacell>\n";//**BY ANS**//
            echo "$moderateid</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>"._USERNAME."</td><td class=datacell>\n";//**BY ANS**//
            echo "$moderatename</td></tr>\n";

            $admin->yesorno(_MOD_CAN_EDIT,"can_edit",$can_edit);//**BY ANS**//
            $admin->yesorno(_MOD_CLOSE_POST,"can_close",$can_close);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_STICKY,"can_sticky",$can_sticky);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_DELETE,"can_delete",$can_delete);//**BY ANS**//
            $admin->yesorno(_MOD_CAN_MOVE,"can_move",$can_move);//**BY ANS**//
            $admin->closetable();
            $admin->submit  (_ADD);//**BY ANS**//


        }
//---------------------------------------------------
//
//---------------------------------------------------
        elseif ($action=="deletemod")
        {
             $id     = $apt->setid('id');
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
               header("Refresh: 1;url=index.php?cat=download&".$admin->get_sess());
               $admin->windowmsg("<p>"._MOD_DELTE_OK." </p>");//**BY ANS**//
           }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="updatemod")
        {
               $id     = $apt->setid('id');
                extract($_POST);

             $result =  $apt->query("update rafia_moderate set can_edit='$can_edit',can_close='$can_close',can_sticky='$can_sticky',can_delete='$can_delete',can_move='$can_move' where id ='$id'");

            if ($result){
               header("Refresh: 1;url=download.php?cat=download&act=mod&".$admin->get_sess());
              $admin->windowmsg(_MOD_UPDATE_OK);//**BY ANS**//
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="updatemenu")
        {

            extract($_POST);
            $value =  $admin->myimplode($menucheck);

             $result = $apt->query("update rafia_settings set value='$value' WHERE variable='down_menuid'");

            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=download&act=downmenu&".$admin->get_sess());
                $admin->windowmsg(_MENU_EDIT_OK);//**BY ANS**//
            }
        }

//---------------------------------------------------
//
//---------------------------------------------------
        elseif ($action=="downmenu")
        {

             $admin->head();
             $admin->modulid_menu($apt->getsettings("down_menuid"),_CHOOSE_MENU,'download');//**BY ANS**//
        }
    }
}
?>
