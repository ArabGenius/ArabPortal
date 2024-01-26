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

if (stristr($_SERVER['SCRIPT_NAME'], "groups.php")) {
    die ("<center><h3>ÚİæÇ åĞå ÇáÚãáíÉ ÛíÑ ãÔÑæÚÉ</h3></center>");
}
class groups
{    

    function groups ($action)
    {
        global $apt,$admin;
        
        $action = $apt->format_data($action);
        
        if($action=="add")
        {
            $admin->head();

            $admin->openform("$PHP_SELF?cat=groups&act=insert&".$admin->get_sess());
            $admin->opentable("ÇÖÇİÉ ãÌãæÚÉ");
            $admin->inputtext("ÇÓã ÇáãÌãæÚÉ","grouptitle",$grouptitle);
            $admin->closetable();
            $admin->submit("ÇÚÊãÇÏ ÇáÊÚÏíá");
        }
        else if ( $action == "insert" )
        {
            @extract($_POST);

            if (!$apt->full($apt->post[grouptitle]))
            {
                $admin->windowmsg("íÌÈ Çä ÊÖÚ ÇÓã ááãÌãæÚÉ");
                exit;
            }

           $result=$apt->query("insert into rafia_groups(grouptitle,editble,deletble) VALUES ('$grouptitle','1','1')");
           if ($result){
		$ngid = $apt->insertid();
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'view_site', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_news', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'stat_news', 'yes');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_news_c', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'download_news_file', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_news_img', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'view_news_img', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_news', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_news_own', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'delete_news', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'print_news', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'ues_editor', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'view_forum', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_forum_post', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'stat_forum', 'yes');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_forum_c', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'upload_forum_file', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'download_forum_file', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_forum', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_forum_own', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'delete_forum', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'print_forum', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'view_download', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'stat_download', 'yes');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_download', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_download_c', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'upload_download_file', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_download', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_download_own', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_download_alert', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_download_rate', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'use_search', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_comment', 'yes');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_comment', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'edit_comment_own', '0');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'upload_comment_file', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'view_link', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'add_link', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'stat_link', 'yes');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_link_alert', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_link_rate', '1');");
                $apt->query("INSERT INTO rafia_privilege VALUES ('', $ngid, '', 'allow_members_list', '1');");
                header("Refresh: 1;url=$PHP_SELF?cat=groups&act=edit&id=$ngid&".$admin->get_sess());
                $admin->windowmsg("<p>ÊãÊ ÇáÇÖÇİÉ ÈäÌÇÍ </p>");

            }

        }

        else if($action=="edit")
        {
            $id = $apt->setid('id');
            $admin->head();
            $result = $apt->query ("SELECT * FROM rafia_groups where groupid=$id");
            $row = $apt->dbarray($result);
            extract($row);

            $results = $apt->query("SELECT variable,value FROM rafia_privilege WHERE groupid='$id';");
            if($apt->dbnumrows($results)==0){$admin->windowmsg("<p>áíÓ åäÇß Çí ÕáÇÍíÇÊ áåĞå ÇáãÌãæÚÉ</p>");exit;}
            while($rows=$apt->dbarray($results)){
               extract($rows);
               $$variable = $value ? $value : 0;
            }

            $admin->openform("$PHP_SELF?cat=groups&act=update&id=$id&".$admin->get_sess());
            $admin->opentable("ÎÕÇÆÕ ÚÇãÉ");
            $admin->inputtext("ÇÓã ÇáãÌãæÚÉ","grouptitle",$grouptitle);
            $admin->inputhidden("pregrouptitle",$grouptitle);
            $admin->yesorno("ÇáÇØáÇÚ Úáì ÇáãæŞÚ ","view_site",$view_site);
            $admin->yesorno("ÇáÅØáÇÚ Úáì ŞÇÆãÉ ÇáÃÚÖÇÁ ","allow_members_list",$allow_members_list);  // Added By Myrosy 20/10/2006            
            $admin->yesorno("ÇÓÊÎÏÇã ãÍÑß ÇáÈÍË","use_search",$use_search);
            $admin->closetable();

            $admin->opentable("ÎÕÇÆÕ ÇáÊÚáíŞÇÊ");
            $admin->selectstat("ÍÇáÉ ÇáÇÖÇİÉ","allow_comment",$allow_comment);
            $admin->yesorno("ÊÍÑíÑ ÇáÊÚáíŞÇÊ ÇáÎÇÕÉ Èåã","edit_comment_own",$edit_comment_own);
            $admin->yesorno("ÊÍÑíÑ ÌãíÚ ÇáÊÚáíŞÇÊ ","edit_comment",$edit_comment);
            $admin->yesorno("ÇÑİÇŞ ãáİÇÊ İí ÇáÊÚíŞÇÊ","upload_comment_file",$upload_comment_file);
            $admin->closetable();

            $admin->opentable("ÎÕÇÆÕ ÇáÇÎÈÇÑ");
            $admin->yesorno("ÇÖÇİÉ ÇÎÈÇÑ","add_news",$add_news);
            $admin->selectstat("ÍÇáÉ ÇáÇÖÇİÉ","stat_news",$stat_news);
            $admin->yesorno("ÇáÊÚáíŞ Úáì ÇáÇÎÈÇÑ","add_news_c",$add_news_c);
            $admin->yesorno("ÅÙåÇÑ ÇáÕæÑÉ İí ÈÑäÇãÌ ÇáÃÎÈÇÑ","view_news_img",$view_news_img);   //  Added (view_news_img) by Myrosy
            $admin->yesorno("ÊäÒíá ÇáãÑİŞÇÊ İí ÈÑäÇãÌ ÇáÃÎÈÇÑ","download_news_file",$download_news_file);    // Modified by Myrosy
            $admin->yesorno("ÇÖÇİÉ ÕæÑÉ ááÎÈÑ","add_news_img",$add_news_img);
            $admin->yesorno("ÊÍÑíÑ ÇáÇÎÈÇÑ ÇáÎÇÕÉ Èåã","edit_news_own",$edit_news_own);
            $admin->yesorno("ÊÍÑíÑ ÌãíÚ ÇáÇÎÈÇÑ","edit_news",$edit_news);
            $admin->yesorno("ÍĞİ ÇáÇÎÈÇÑ ÇáÎÇÕÉ Èåã","delete_news",$delete_news);
            $admin->yesorno("ØÈÇÚÉ ãæÇÖíÚ ÇáÇÎÈÇÑ","print_news",$print_news);
            $admin->yesorno("ÇÓÊÎÏÇã ãÍÑÑ html ÚäÏ ÇáÇÖÇİÉ","ues_editor",$ues_editor);
            $admin->closetable();

            $admin->opentable("ÎÕÇÆÕ ÇáãäÊÏíÇÊ");
            $admin->yesorno("ÇáÇØáÇÚ Úáì ÇáãäÊÏíÇÊ","view_forum",$view_forum);
            $admin->yesorno("ÇÖÇİÉ ãæÖæÚ ÌÏíÏ","add_forum_post",$add_forum_post);
            $admin->selectstat("ÍÇáÉ ÇáÇÖÇİÉ","stat_forum",$stat_forum);
            $admin->yesorno("ÇÖÇİÉ ÊÚáíŞ Úáì ÇáãæÖæÚ","add_forum_c",$add_forum_c);
            $admin->yesorno("ÇÑİÇŞ ãáİÇÊ","upload_forum_file",$upload_forum_file);
            $admin->yesorno("ÊäÒíá ÇáãáİÇÊ ÇáãÑİŞÉ İí ÈÑäÇãÌ ÇáãäÊÏíÇÊ","download_forum_file",$download_forum_file);
            $admin->yesorno("ÊÍÑíÑ ÇáãæÇÖíÚ ÇáÎÇÕÉ Èåã","edit_forum_own",$edit_forum_own);
            $admin->yesorno("ÊÍÑíÑ ÌãíÚ ÇáãæÇÖíÚ ","edit_forum",$edit_forum);
            $admin->yesorno("ÍĞİ ÇáãÔÇÑßÇÊ ÇáÎÇÕÉ Èåã","delete_forum",$delete_forum);
            $admin->yesorno("ØÈÇÚÉ ãæÇÖíÚ ÇáãäÊÏì","print_forum",$print_forum);
            $admin->closetable();

            $admin->opentable("ÎÕÇÆÕ ãÑßÒ ÇáÊÍãíá");
            $admin->yesorno("ÇáÇØáÇÚ Úáì ãÑßÒ ÇáÊÍãíá","view_download",$view_download);
            $admin->yesorno("ÇÖÇİÉ ÈÑÇãÌ İí ãÑßÒ ÇáÊÍãíá","add_download",$add_download);
            $admin->selectstat("ÍÇáÉ ÇáÇÖÇİÉ","stat_download",$stat_download);
            $admin->yesorno("ÇÖÇİÉ ÊÚáíŞ İí ãÑßÒ ÇáÊÍãíá","add_download_c",$add_download_c);
            $admin->yesorno("ÇÑİÇŞ ãáİ","upload_download_file",$upload_download_file);
            $admin->yesorno("ÊÍÑíÑ ßÇİÉ ÇáãæÇÖíÚ İí ãÑßÒ ÇáÊÍãíá","edit_download",$edit_download);
            $admin->yesorno("ÊÍÑíÑ ÇáãæÇÖíÚ ÇáÎÇÕÉ Èåã","edit_download_own",$edit_download_own);
            $admin->yesorno("ÇáÊäÈíå Úä ÑæÇÈØ áÇ ÊÚãá","allow_download_alert",$allow_download_alert);
            $admin->yesorno("ÊŞííã ãÔÇÑßÇÊ ãÑßÒ ÇáÊÍãíá ","allow_download_rate",$allow_download_rate);
            $admin->closetable();
        
            $admin->opentable("ÎÕÇÆÕ Ïáíá ÇáãæÇŞÚ");
            $admin->yesorno("ÇáÇØáÇÚ Úáì Ïáíá ÇáãæÇŞÚ","view_link",$view_link);
            $admin->yesorno("ÇÖÇİÉ ãæŞÚ ÌÏíÏ","add_link",$add_link);
            $admin->selectstat("ÍÇáÉ ÇáÇÖÇİÉ","stat_link",$stat_link);
            $admin->yesorno("ÇáÊäÈíå Úä ÑæÇÈØ áÇ ÊÚãá","allow_link_alert",$allow_link_alert);
            $admin->yesorno("ÊŞííã ÇáãæÇŞÚ ","allow_link_rate",$allow_link_rate);
            $admin->closetable();
            
            $admin->submit("ÇÚÊãÇÏ ÇáÊÚÏíá");
        }
        else if($action=="update")
        {
           $id = $apt->setid('id');

           if($id == 5){
		$_POST['edit_comment']      = 0;
		$_POST['edit_comment_own']  = 0;
		$_POST['edit_news']         = 0;
		$_POST['edit_news_own']     = 0;
		$_POST['delete_news']       = 0;
		$_POST['edit_download_own'] = 0;
		$_POST['edit_download']     = 0;
		$_POST['delete_forum']      = 0;
		$_POST['edit_forum_own']    = 0;
		$_POST['edit_forum']        = 0;
           }

           while (list($key,$val)= each($_POST)){
              if($val == '') $value = 0; else $value  = $val;
              $apt->query("update rafia_privilege set value='$value' WHERE variable='$key' and groupid='$id';");
           }
           if($_POST[pregrouptitle] !== $_POST[grouptitle])$apt->query("update rafia_groups set grouptitle='$_POST[grouptitle]' WHERE groupid='$id';");
           header("Refresh: 1;url=$PHP_SELF?cat=groups&act=groups&".$admin->get_sess());
           $admin->windowmsg("<p>Êã ÊÍÏíË ÇáÈíäÇÊ</p>");
        }
        else if($action=="delete")
        {
             extract($_POST);
             
            if(isset($apt->get[id]))
            {
                $admin->head();
                $admin->openform("$PHP_SELF?cat=groups&act=delete&".$admin->get_sess());
                $admin->opentable("ÊÇßíÏ ÇáÍĞİ");
                print"<p><font color=\"#FF0000\">ÇĞÇ ßÇä İí ÇáãÌãæÚÉ ÇáÊí ÊÑÛÈ İí ÍĞİåÇ ÇÚÖÇÁ İÓæİ  ÊÚÊÈÑåã ÇáãÌáÉ ÒæÇÑ ÇĞÇ áã ÊŞã ÈäŞáåã<br>
                      Çæ íÌÈ Çä ÊäŞá ÌãíÚ ÇÚÖÇÁ ÇáãÌãæÚÉ ÇáãÍĞæİÉ Çáì ãÌãæÚÉ ÇÎÑì <br>
                       ÇÎÊÑ ÇÍÏ ÇáãÌãæÚÇÊ ÇáÊí ÊÑÛÈ äŞá ÇáÇÚÖÇÁ ÇáíåÇ</font></p>";
                $admin->list_user_level(4," ÇäŞá Åáì ÇáãÌãæÚÉ");
                $admin->inputhidden("delid",$apt->get[id]);
                $admin->closetable();
                $admin->submit("ÇÊãÇã ÇáÍĞİ");

           }
            else
            {


                $result    = $apt->query("update rafia_users set usergroup='$usergroup' where usergroup=$delid");

                if ($result)
                {
                    $resultd = $apt->query("delete from rafia_groups where groupid=$delid");
                    $resultd = $apt->query("delete from rafia_privilege where groupid=$delid");
                    if ($resultd)
                    {
                        header("Refresh: 1;url=$PHP_SELF?cat=groups&act=groups&".$admin->get_sess());
                        $admin->windowmsg("<p>Êã ÍĞİ ÇáãÌãæÚÉ</p>");
                    }
                }
            }
        }
        else if($action=="groups")
        {
            $admin->head();
            $result = $apt->query("SELECT * FROM rafia_groups ORDER BY groupid ASC");

            $admin->opentable("ÇáãÌãæÚÇÊ");
            while($row = $apt->dbarray($result))
            {
                extract($row);
                $countuserg  =  $apt->dbnumquery("rafia_users","usergroup='$groupid'");
                echo "<tr><td  class=datacell width=60%><b>$grouptitle</b> <br> <a href=index.php?cat=users&groupid=$groupid&".$admin->get_sess()."> ÇÚÖÇÁ ÇáãÌãæÚÉ </a>  ($countuserg) </td>";
               if(($editble == 1))
               echo "<td width=20% class=datacell align=center><a href=".$PHP_SELF."?cat=groups&act=edit&id=$groupid&".$admin->get_sess().">ÊÍÑíÑ</b></a></td>";
               else
               echo "<td width=20% class=datacell align=center><a href=\"javascript:alert('ÚİæÇ .. åĞå ãÌãæÚÉ ÇÓÇÓíÉ áÇ íãßäß ÊÍÑíÑåÇ')\">ÊÍÑíÑ</b></a></td>";
                if(($deletble == 1))
               echo "<td width=20% class=datacell align=center><a href=".$PHP_SELF."?cat=groups&act=delete&id=$groupid&".$admin->get_sess()." onClick=\"if (!confirm('åá ÊÑÛÈ İÚáÇğ İí ÇáÍĞİ¿')) return false;\">ãÓÍ</b></a></td>";
               else
               echo "<td width=20% class=datacell align=center><a href=\"javascript:alert('ÚİæÇ .. åĞå ãÌãæÚÉ ÇÓÇÓíÉ áÇ íãßäß ÍĞİåÇ')\">ãÓÍ</b></a></td>";
           }
            $admin->closetable();
      }
   }
}
?>