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

if (stristr($_SERVER['SCRIPT_NAME'], "news.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

function subcat($id)
{
    global $apt, $admin;
    
    $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='1' and subcat='$id' ORDER BY ordercat ASC");

    while($row = $apt->dbarray($result))
    {
        echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > ++ " . $row["title"] . "</b></td>
        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=news&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>
        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=news&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";
        subcat($row["id"]);
    }
}

class news
{
    function news ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

//---------------------------------------------------
//
//---------------------------------------------------
        if ($action=="")
        {
             $admin->head();

             $admin->openform("index.php?action=upset&".$admin->get_sess());
             $admin->opentable(_MAIN_SETTINGS,'2');

              $ImageCreate = '';
              if (!function_exists (ImageCreateFromGIF))
                     $ImageCreate .= "gif,";
              if (!function_exists (imagecreatefromjpeg))
                     $ImageCreate .= "jpg,";
              if (!function_exists (ImageCreateFromPNG))
                     $ImageCreate .= "png,";
             if(!empty($ImageCreate))
             {
                 $ImageCreate .= "<br> Ì›÷· ⁄œ„ «” Œœ«„Â« ";
                 $admin->td_msgh("‰Ÿ«„  ’€Ì— «·’Ê—… ·«Ì⁄„· ⁄·Ï «·«‰Ê«⁄ ",30);
                 $admin->td_msgh($ImageCreate,70);
             }

             $admin->editsetting('0','news');
             $admin->closetable();
             $admin->submit(_DO_EDIT);
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if ($action=="cat")
        {
            $admin->head();

            $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='1' and subcat='0' ORDER BY ordercat ASC");



            $admin->opentable("«ﬁ”«„ «·«Œ»«—",4);

            echo "</tr>";

            while($row = $apt->dbarray($result))
            {
                echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > - " . $row["title"] . "</b></td>
                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=news&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>
                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=news&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";
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
            $admin->opentable(_ADD_SECTION);
            $admin->openformdata("$PHP_SELF?cat=news&act=insert&".$admin->get_sess());

            $admin->inputhidden('catid','1');
            $admin->inputtext(_SECTION_NAME,"title","");
            $admin->selectnum(_SORT,"ordercat","",'0','50');
            $admin->selectCat($subcat,$id,1);
            $admin->yesorno(_SHOW_HOMEPAGE,"ismine",'0');
            $admin->selectnum(_SHOW_HOMEPAGE_COUNT,"homeshow",'','1','20');
            $admin->textarea(_CAT_DIS_NOTE,"dsc","",'5','5');
            $admin->textarea(_CAT_DIS_NOTE2,"dscin", "",'5','5');
            $admin->group_check_box(_GROUP_ACCESS,"groupview[]");
            $admin->group_check_box(_GROUP_CAN_ADD,"groupost[]");
            $admin->inputtext(_ALERT_IF_ADD,"cat_email",$cat_email);//**BY ANS**//
            $admin->inputfile("«—›«ﬁ ’Ê—… ··ﬁ”„");
           $admin->closetable();
           $admin->submit(_ADD);
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="insert")
        {

            extract($_POST);
            if (!$apt->full($title))
            {
              $admin->windowmsg(_NO_CAT_NAME);
               exit;
            }

            if (!isset($subcat))
            {
                $subcat = '0';
            }
           
            $groupview  =  $admin->myimplode($apt->post[groupview]);
            $groupost   =  $admin->myimplode($apt->post[groupost]);
            $title      =  $admin->format_data($apt->post[title]);
            $dsc        =  $admin->format_data($apt->post[dsc]);
            $dscin      =  $admin->format_data($apt->post[dscin]);
            $cat_email  =  $admin->format_data($apt->post[cat_email]);

            $result = $apt->query("insert into rafia_cat
                               (ordercat,catType,subcat,title,dsc,dscin,ismine,groupview,groupost,cat_email,homeshow) values
                               ('$ordercat','1','$subcat','$title','$dsc','$dscin','$ismine','$groupview','$groupost','$cat_email','$homeshow')");
                               

        

        if (is_uploaded_file($_FILES["name_file"]['tmp_name']))
        {
            $id = $apt->insertid();
            $admin->uploadfile($id);
        }
        
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=news&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p>"._CAT_IS_ADD."</p>");
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
            $admin->opentable(_EDIT_CAT);
            $admin->openformdata("$PHP_SELF?cat=news&act=update&id=$id&".$admin->get_sess());
            $result=$apt->query("select * from rafia_cat where id=$id");
            $row=$apt->dbarray($result);
            extract($row);
            $admin->inputtext(_SECTION_NAME,"title",$title);
            $admin->selectnum(_SORT,"ordercat",$ordercat,'0','50');
            $admin->selectCat($subcat,$id,1);
            $admin->yesorno(_SHOW_HOMEPAGE,"ismine","$ismine");
            $admin->selectnum(_SHOW_HOMEPAGE_COUNT,"homeshow","$homeshow",'1','20');
            $admin->textarea(_CAT_DIS_NOTE,"dsc",$dsc,'5','5');
            $admin->textarea(_CAT_DIS_NOTE2,"dscin", $dscin,'5','5');
            $admin->group_check_box(_GROUP_ACCESS,"groupview[]",$groupview);
            $admin->group_check_box(_GROUP_CAN_ADD,"groupost[]",$groupost);
            $admin->inputtext(_ALERT_IF_ADD,"cat_email",$cat_email);//**BY ANS**//
            $admin->td_msg("«·’Ê—… «·Œ«’… »«·ﬁ”„");

            $cat_ima = $apt->upload_path."/".$id.".catid";

             if( file_exists ($cat_ima))
             {
                 $catUrlami = $apt->conf['site_url'];
                 if(ereg ("/$", $catUrlami))
                 $catima = $catUrlami."upload/".$id.".catid";
                 else
                 $catima = $catUrlami."/upload/".$id.".catid";
                 //"";
                 
                 $image = "<img src=\"$PHP_SELF?cat=news&act=image&id=$id&".$admin->get_sess()."\">";
                 //$image = "<img src=\"$catima\">";
                 $admin->td_msg($image);
             }
             else
             {
                  $admin->td_msg("·«ÌÊÃœ");
             }
            $admin->inputfile("«” »œ«· «·’Ê—… «·Œ«’… »«·ﬁ”„");
            $admin->closetable();
            $admin->submit(_EDIT);

        }
        else if($action=="image")
        {
            $id     = $apt->setid('id');
            $pathfile = $apt->upload_path."/".$id.".catid";
            if( file_exists ($pathfile))
            {
                header ("Content-type: image/gif");
                $readfile = readfile($pathfile, "r");
                print($readfile);
            }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="update")
        {
            $id     = $apt->setid('id');
            
            extract($_POST);

            require_once($apt->conf['site_path'].'/func/upload.php');

           if (is_uploaded_file($apt->files["name_file"]['tmp_name']))
           {
               $up  = new RaFiaUpload;
               $up->cat ="catid";
               $up->uploads = $apt->files["name_file"];
               $cat_ima = $apt->upload_path."/".$id.".catid";
               if(file_exists ($cat_ima))
               {
                   @unlink($cat_ima);
                   $up->uploadfile(1);
               }
               else
               {
                   $up->uploadfile();
               }
            }
            
            $groupview  = $admin->myimplode($apt->post[groupview]);
            $groupost   = $admin->myimplode($apt->post[groupost]);
            $title      = $admin->format_data($apt->post[title]);
            $dsc        = $admin->format_data($apt->post[dsc]);
            $dscin      = $admin->format_data($apt->post[dscin]);
            $cat_email  = $admin->format_data($apt->post[cat_email]);

            $result=$apt->query("update rafia_cat set ordercat='$ordercat',
                                                 title='$title',
                                                 subcat='$subcat',
                                                 dsc='$dsc',
                                                 dscin='$dscin',
                                                 ismine='$ismine',
                                                 homeshow='$homeshow',
                                                 groupview='$groupview',
                                                 cat_email='$cat_email',
                                                 groupost='$groupost'
                                                 where id=$id");
            if ($result)
            {
                @header("Refresh: 1;url=index.php?cat=news&act=cat&".$admin->get_sess());
                $admin->windowmsg("<p>"._CAT_UPDATE_OK." </p>");
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
               $admin->delete_cat($apt->get['id'],'1','news');
            }
            else
            {
                if(!empty($apt->post['tocatid']))
                {
                    $tocatid = $apt->post['tocatid'];
                    $delid   = $apt->post['delid'];
                    $result  = $apt->query("update rafia_news set cat_id='$tocatid' where cat_id='$delid'");
                    $apt->query("update rafia_comment set cat_id='$tocatid' where news_id >'0'");
                }
                else
                {
                    $delid   = $apt->post['delid'];
                    $result  = $apt->query("delete from rafia_news where cat_id='$delid'");
                    $apt->query("delete from rafia_comment where cat_id='$delid'");
                }
                if ($result)
                {
                    $result=$apt->query("delete from rafia_cat where id='$delid'");
                    if ($result)
                    {
                        header("Refresh: 1;url=$PHP_SELF?cat=news&act=cat&".$admin->get_sess());
                        $admin->windowmsg("<p>"._CAT_DELETE_OK." </p>");
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
            $admin->opentable(_ADD_MOD_TO_CAT);
            $admin->openform("$PHP_SELF?cat=news&act=insertmod&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>"._SELECT_CAT."</td><td class=datacell>\n";
            echo "<select name='moderatecatid'>\n";
            echo "<option value='0'>«Œ — «·ﬁ”„</option>";
            $result = $apt->query("select * from rafia_cat WHERE catType='1' order by id");
            while($row=$apt->dbarray($result))
            {
                $idcat=$row["id"];
                $titlecat=$row["title"];
                echo "<option value='$idcat'>$titlecat</option>";
            }
            echo "</select></td></tr>\n";
            $admin->inputtext(_ID_NUMBER,'moderateid',$moderateid);
            $admin->inputtext(_MOD_NAME,'moderatename',$moderatename);
            $admin->yesorno(_MOD_CAN_EDIT,"can_edit",$can_edit);
            $admin->yesorno(_MOD_CLOSE_POST,"can_close",$can_close);
            $admin->yesorno(_MOD_CAN_STICKY,"can_sticky",$can_sticky);
            $admin->yesorno(_MOD_CAN_DELETE,"can_delete",$can_delete);
            $admin->yesorno(_MOD_CAN_MOVE,"can_move",$can_move);
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
                               ('$moderatecatid','2','$moderateid','$moderatename','$can_edit','$can_close','$can_sticky','$can_delete','$can_move')");

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
            $result = $apt->query("SELECT * FROM rafia_moderate  where modadmin=2 ORDER BY id ASC LIMIT $start,$perpage");
            $page_result = $apt->query("SELECT * FROM rafia_moderate where modadmin=2");
            $numrows    = $apt->dbnumrows($page_result);
            print $apt->pagenum($perpage,"users");
            $admin->opentable(_MOD_IN_NEWS,4);
            while($row = $apt->dbarray($result))
            {
                extract($row);
                 $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='1' and id='$moderatecatid'");
                echo "<tr><td  class=datacell width=30%><b>$moderatename</b></td>
                <td  class=datacell width=30%><b>$cat[title]</b></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=news&act=editmod&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=news&act=deletemod&id=$id&".$admin->get_sess()."  onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\">„”Õ</b></a></td>";
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
            $mod = $apt->dbfetch ("SELECT * FROM rafia_moderate where id=$id");
            @extract($mod);
            $admin->opentable(_EDIT_MOD);
            $admin->openform("$PHP_SELF?cat=news&act=updatemod&id=$id&".$admin->get_sess());
            echo "<tr><td class=datacell width='33%'>"._MOD_IN_CAT." </td><td class=datacell>\n";

            $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='1' and id='$moderatecatid'");

            echo "$cat[title]</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>"._ID_NUMBER."</td><td class=datacell>\n";
            echo "$moderateid</td></tr>\n";
            echo "<tr><td class=datacell width='33%'>"._USERNAME."</td><td class=datacell>\n";
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
             $mod = $apt->dbfetch ("SELECT * FROM rafia_moderate where id=$id");  // Edited By Myrosy
             @extract($mod);

            $moderatenum = $apt->dbnumquery("rafia_moderate","moderateid='$moderateid'");
            if($moderatenum ==0)
            {
                $apt->query("update rafia_users set
                               useradmin = '0',
                               usergroup = '2'
                               WHERE username='$moderatename' and userid='$moderateid'");
            }

            $result=$apt->query("delete from rafia_moderate where id=$id");

           if ($result)
           {
           header("Refresh: 1;url=index.php?cat=news&".$admin->get_sess());
                $admin->windowmsg("<p>"._MOD_DELTE_OK." </p>");
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
             header("Refresh: 1;url=index.php?cat=news&act=mod&".$admin->get_sess());
            $admin->windowmsg(_MOD_UPDATE_OK);
        }
        }
//---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="updatemenu")
        {
            extract($_POST);
            
            $value =  $admin->myimplode($menucheck);

            $result = $apt->query("update rafia_settings set value='$value' WHERE variable='news_menuid'");

            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=news&act=newsmenu&".$admin->get_sess());
                $admin->windowmsg(_MENU_EDIT_OK);
            }
        }

//---------------------------------------------------
//
//---------------------------------------------------
        elseif ($action=="newsmenu")
        {
            $admin->head();

            $admin->modulid_menu($apt->getsettings("news_menuid"),_NEWS_MENUS,'news');
        }
    }
}
?>
