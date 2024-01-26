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

if (stristr($_SERVER['SCRIPT_NAME'], "link.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

function subcat($id)

{

    global $apt, $admin;



    $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='4' and subcat='$id' ORDER BY ordercat ASC");



    while($row = $apt->dbarray($result))

    {

        echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > ++ " . $row["title"] . "</b></td>

        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=link&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>

        <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=link&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";

    }

}





//---------------------------------------------------

//

//---------------------------------------------------

class link

{

    function link ($action)

    {

        global $apt,$admin;



        $action = $apt->format_data($action);



        if ($action=="")
        {

             $admin->head();

             $admin->openform("index.php?action=upset&".$admin->get_sess());

             $admin->opentable("«·«⁄œ«œ«  «·⁄«„…",'2');

             $admin->editsetting('0','link');

             $admin->closetable();

             $admin->submit("«⁄ „«œ «· ⁄œÌ·");

        }

//---------------------------------------------------

//

//---------------------------------------------------

        else if ($action=="cat")

        {

            $admin->head();



            $result = $apt->query("SELECT * FROM rafia_cat WHERE catType='4' and subcat='0' ORDER BY ordercat ASC");



            $admin->opentable("«ﬁ”«„ œ·Ì· «·„Ê«ﬁ⁄",4);



            echo "</tr>";



            while($row = $apt->dbarray($result))

            {

                echo "<tr><td class=datacell bgcolor=D2D4D4 width=50% > - " . $row["title"] . "</b></td>

                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=link&act=edit&id=" . $row["id"]. "&".$admin->get_sess()."\"><b> Õ—Ì—</b></a></td>

                <td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=link&act=delete&id=" . $row["id"] . "&".$admin->get_sess()."\"><b>„”Õ</b></a></td></tr>";

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

            $admin->opentable("«÷«›…  ’‰Ì› ÃœÌœ");

            $admin->openform("$PHP_SELF?cat=link&act=insert&".$admin->get_sess());



            $admin->inputhidden('catid','4');

            $admin->inputtext("«”„ «· ’‰Ì›","title","");

            $admin->selectnum(" — Ì»","ordercat","",'0','50');
            $admin->selectCat($subcat,$id,4);

            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc","",'5','5');

            $admin->textarea("Ê’› «· ’‰Ì› «·œ«Œ·Ì  (ÌŸÂ— œ«Œ· «·ﬁ”„ ›Ì ’œ— «·’›Õ… )","dscin", "",'5','5');

            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ œŒÊ· «·ﬁ”„","groupview[]");

            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ «÷«›… „Ê«÷Ì⁄ ·Â–« «·ﬁ”„","groupost[]");

            $admin->inputtext("«–« ﬂ‰   —Ìœ «‰ Ì—”· ·ﬂ  »·Ì€ ⁄‰œ «÷«›… «Ì „‘«—ﬂ… ·Â–« «·ﬁ”„ «ﬂ » «·»—Ìœ Â‰« ","cat_email",$cat_email);

            $admin->closetable();

            $admin->submit("«÷›");

        }



//---------------------------------------------------

//

//---------------------------------------------------

        else if($action=="insert")

        {

            extract($_POST);

            if (!$apt->full($title))

            {

              $admin->windowmsg("·„  ﬁ„ »ﬂ «»… «”„ «· ’‰Ì›");

               exit;

            }



            if (!isset($subcat))

            {

                $subcat = '0';

            }

    

            $groupview =  $admin->myimplode($apt->post[groupview]);

            $groupost  =  $admin->myimplode($apt->post[groupost]);

            $title      = $admin->format_data($apt->post[title]);

            $dsc        = $admin->format_data($apt->post[dsc]);

            $dscin      = $admin->format_data($apt->post[dscin]);

            $cat_email  = $admin->format_data($apt->post[cat_email]);

            

            $result = $apt->query("insert into rafia_cat

                               (ordercat,catType,subcat,title,dsc,dscin,ismine,groupview,groupost,cat_email) values

                               ('$ordercat','4','$subcat','$title','$dsc','$dscin','$ismine','$groupview','$groupost','$cat_email')");

                               

            if ($result)

            {

               // header("Refresh: 1;url=link.php&".$admin->get_sess()); // Removed by Myrosy

                   header("Refresh: 1;url=$PHP_SELF?cat=link&act=cat&".$admin->get_sess());  // Added by Myrosy

                $admin->windowmsg("<p> „  ≈÷«›… «· ’‰Ì› »‰Ã«Õ </p>");

            }

            else

            {

                $admin->windowmsg("<p>Œÿ√ ›Ì ⁄„·Ì… ≈÷«›…  ’‰Ì›</p>");

            }

        }

//---------------------------------------------------

//

//---------------------------------------------------

        else if($action=="edit")

        {

            $id     = $apt->setid('id');

            

            $admin->head();

            $admin->opentable(" Õ—Ì—  ’‰Ì›");

            $admin->openform("$PHP_SELF?cat=link&act=update&id=$id&".$admin->get_sess());

            $result=$apt->query("select * from rafia_cat where id=$id");

            $row=$apt->dbarray($result);

            extract($row);

            $admin->inputtext("«”„ «· ’‰Ì›","title",$title);

            $admin->selectnum(" — Ì»","ordercat",$ordercat,'0','50');

            $admin->selectCat($subcat,$id,4);

///////////////////////////////


/////////////////////////////////

            $admin->textarea("Ê’› «· ’‰Ì› «·Œ«—ÃÌ (ÌŸÂ—›Ì ﬁ«∆„… «·«ﬁ”«„ )","dsc",$dsc,'5','5');

            $admin->textarea("Ê’› «· ’‰Ì› «·œ«Œ·Ì  (ÌŸÂ— œ«Œ· «·ﬁ”„ ›Ì ’œ— «·’›Õ… )","dscin", $dscin,'5','5');

            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ œŒÊ· «·ﬁ”„","groupview[]",$groupview);

            $admin->group_check_box("«·„Ã„Ê⁄«  «· Ì  ” ÿÌ⁄ «÷«›… „Ê«÷Ì⁄ ·Â–« «·ﬁ”„","groupost[]",$groupost);

            $admin->inputtext("«–« ﬂ‰   —Ìœ «‰ Ì—”· ·ﬂ  »·Ì€ ⁄‰œ «÷«›… «Ì „‘«—ﬂ… ·Â–« «·ﬁ”„ «ﬂ » «·»—Ìœ Â‰« ","cat_email",$cat_email);

            $admin->closetable();

            $admin->submit(_EDIT);



        }

//---------------------------------------------------

//

//---------------------------------------------------

        else if($action=="update")

        {

            extract($_POST);

            

            $id     = $apt->setid('id');

            

            $groupview =  $admin->myimplode($apt->post[groupview]);

            $groupost  =  $admin->myimplode($apt->post[groupost]);

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

            {

               // header("Refresh: 1;url=link.php&".$admin->get_sess());   // Removed by Myrosy

                header("Refresh: 1;url=index.php?cat=link&act=cat");  // Added by Myrosy

                $admin->windowmsg("<p> „  ÕœÌÀ «· ’‰Ì› </p>");

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

                $admin->delete_cat($apt->get['id'],'4','link');

            }

            else

            {

                if(!empty($apt->post['tocatid']))

                {

                    $tocatid = $apt->post['tocatid'];

                    $delid   = $apt->post['delid'];

                    $result  = $apt->query("update rafia_links set cat_id='$tocatid' where cat_id=$delid");

                }

                else

                {   $delid   = $apt->post['delid'];

                    $result = $apt->query("delete from rafia_news where cat_id='$delid'");

                }

                if ($result)

                {

                   $result=$apt->query("delete from rafia_cat where id='$delid'");

                    if ($result)

                    {

                       // @header("Refresh: 1;url=link.php?cat=link&act=cat&".$admin->get_sess());  Removed by Myrosy

                       @header("Refresh: 1;url=$PHP_SELF?cat=link&act=cat&".$admin->get_sess()); // Added by Myrosy

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

            $admin->openform("$PHP_SELF?cat=link&act=insertmod&".$admin->get_sess());

            echo "<tr><td class=datacell width='33%'>Õœœ «·ﬁ”„ </td><td class=datacell>\n";

            echo "<select name='moderatecatid'>\n";

            echo "<option value='0'>«Œ — «·ﬁ”„</option>";

            $result = $apt->query("select * from rafia_cat WHERE catType ='4' and ismine = '0' order by id");

            while($row=$apt->dbarray($result))

            {

                $idcat   = $row["id"];

                $titleca = $row["title"];

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
                               ('$moderatecatid','5','$moderateid','$moderatename','$can_edit','$can_close','$can_sticky','$can_delete','$can_move')");

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



            $perpage = "30";



            $result = $apt->query("SELECT * FROM rafia_moderate  where modadmin=5 ORDER BY id ASC LIMIT $start,$perpage");



            $page_result = $apt->query("SELECT * FROM rafia_moderate where modadmin=5");



            $numrows    = $apt->dbnumrows($page_result);



            print $apt->pagenum($perpage,"users");



            $admin->opentable("«·„‘—›Ì‰ ›Ì »—‰«„Ã œ·Ì· «·„Ê«ﬁ⁄",4);



            while($row = $apt->dbarray($result))

            {

                extract($row);

                 $cat = $apt->dbfetch("select title from rafia_cat WHERE catType='4' and id='$moderatecatid'");

                echo "<tr><td  class=datacell width=30%><b>$moderatename</b></td>

                <td  class=datacell width=30%><b>$cat[title]</b></td>

                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=link&act=editmod&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>

                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=link&act=deletemod&id=$id&".$admin->get_sess().">„”Õ</b></a></td>";

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

            $admin->opentable(" Õ—Ì— Œ’«∆’ «·«‘—«›");

            $admin->openform("$PHP_SELF?cat=link&act=updatemod&id=$id&".$admin->get_sess());

            echo "<tr><td class=datacell width='33%'>„‘—› ›Ì «·ﬁ”„ </td><td class=datacell>\n";



            $cat = $apt->dbfetch("select title from rafia_cat WHERE catType ='4' and id='$moderatecatid'");



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





            $result = $apt->query("delete from rafia_moderate where id=$id");



           if ($result)

           {

               // header("Refresh: 1;url=link.php&".$admin->get_sess());  Removed by Myrosy

               header("Refresh: 1;url=$PHP_SELF?cat=link&act=mod&".$admin->get_sess());  // Added by Myrosy

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

             header("Refresh: 1;url=$PHP_SELF?cat=link&act=mod&".$admin->get_sess());

             $admin->windowmsg(" „  «·«÷«›… »‰Ã«Õ");

             }

        }

//---------------------------------------------------

//

//---------------------------------------------------

        else if($action=="updatemenu")

        {

            extract($_POST);



            $value =  $admin->myimplode($apt->post[menucheck]);



            $result = $apt->query("update rafia_settings set value='$value' WHERE variable='link_menuid'");



            if ($result)

            {

                //header("Refresh: 1;url=link.php?cat=link&act=downmenu&".$admin->get_sess()); // Removed by yrosy

                header("Refresh: 1;url=$PHP_SELF?cat=link&act=linkmenu&".$admin->get_sess()); // Added by yrosy

                $admin->windowmsg(" „ «· ⁄œÌ· »‰Ã«Õ");

            }

        }

//---------------------------------------------------

//

//---------------------------------------------------

        elseif ($action=="linkmenu")

        {

             $admin->head();

             $admin->modulid_menu($apt->getsettings("link_menuid"),"«Œ — «·ﬁÊ«∆„ «· Ì  —€» «‰  ŸÂ— ›Ì ﬁ”„ «·«Œ»«—",'link');

        }

    }

}



?>
