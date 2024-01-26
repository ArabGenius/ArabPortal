<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright � 2006   |
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
    die ("<center><h3>���� ��� ������� ��� ������</h3></center>");
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
            $admin->opentable("����� ������");
            $admin->inputtext("��� ��������","grouptitle",$grouptitle);
            $admin->closetable();
            $admin->submit("������ �������");
        }
        else if ( $action == "insert" )
        {
            @extract($_POST);

            if (!$apt->full($apt->post[grouptitle]))
            {
                $admin->windowmsg("��� �� ��� ��� ��������");
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
                $admin->windowmsg("<p>��� ������� ����� </p>");

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
            if($apt->dbnumrows($results)==0){$admin->windowmsg("<p>��� ���� �� ������� ���� ��������</p>");exit;}
            while($rows=$apt->dbarray($results)){
               extract($rows);
               $$variable = $value ? $value : 0;
            }

            $admin->openform("$PHP_SELF?cat=groups&act=update&id=$id&".$admin->get_sess());
            $admin->opentable("����� ����");
            $admin->inputtext("��� ��������","grouptitle",$grouptitle);
            $admin->inputhidden("pregrouptitle",$grouptitle);
            $admin->yesorno("������� ��� ������ ","view_site",$view_site);
            $admin->yesorno("������� ��� ����� ������� ","allow_members_list",$allow_members_list);  // Added By Myrosy 20/10/2006            
            $admin->yesorno("������� ���� �����","use_search",$use_search);
            $admin->closetable();

            $admin->opentable("����� ���������");
            $admin->selectstat("���� �������","allow_comment",$allow_comment);
            $admin->yesorno("����� ��������� ������ ���","edit_comment_own",$edit_comment_own);
            $admin->yesorno("����� ���� ��������� ","edit_comment",$edit_comment);
            $admin->yesorno("����� ����� �� ��������","upload_comment_file",$upload_comment_file);
            $admin->closetable();

            $admin->opentable("����� �������");
            $admin->yesorno("����� �����","add_news",$add_news);
            $admin->selectstat("���� �������","stat_news",$stat_news);
            $admin->yesorno("������� ��� �������","add_news_c",$add_news_c);
            $admin->yesorno("����� ������ �� ������ �������","view_news_img",$view_news_img);   //  Added (view_news_img) by Myrosy
            $admin->yesorno("����� �������� �� ������ �������","download_news_file",$download_news_file);    // Modified by Myrosy
            $admin->yesorno("����� ���� �����","add_news_img",$add_news_img);
            $admin->yesorno("����� ������� ������ ���","edit_news_own",$edit_news_own);
            $admin->yesorno("����� ���� �������","edit_news",$edit_news);
            $admin->yesorno("��� ������� ������ ���","delete_news",$delete_news);
            $admin->yesorno("����� ������ �������","print_news",$print_news);
            $admin->yesorno("������� ���� html ��� �������","ues_editor",$ues_editor);
            $admin->closetable();

            $admin->opentable("����� ���������");
            $admin->yesorno("������� ��� ���������","view_forum",$view_forum);
            $admin->yesorno("����� ����� ����","add_forum_post",$add_forum_post);
            $admin->selectstat("���� �������","stat_forum",$stat_forum);
            $admin->yesorno("����� ����� ��� �������","add_forum_c",$add_forum_c);
            $admin->yesorno("����� �����","upload_forum_file",$upload_forum_file);
            $admin->yesorno("����� ������� ������� �� ������ ���������","download_forum_file",$download_forum_file);
            $admin->yesorno("����� �������� ������ ���","edit_forum_own",$edit_forum_own);
            $admin->yesorno("����� ���� �������� ","edit_forum",$edit_forum);
            $admin->yesorno("��� ��������� ������ ���","delete_forum",$delete_forum);
            $admin->yesorno("����� ������ �������","print_forum",$print_forum);
            $admin->closetable();

            $admin->opentable("����� ���� �������");
            $admin->yesorno("������� ��� ���� �������","view_download",$view_download);
            $admin->yesorno("����� ����� �� ���� �������","add_download",$add_download);
            $admin->selectstat("���� �������","stat_download",$stat_download);
            $admin->yesorno("����� ����� �� ���� �������","add_download_c",$add_download_c);
            $admin->yesorno("����� ���","upload_download_file",$upload_download_file);
            $admin->yesorno("����� ���� �������� �� ���� �������","edit_download",$edit_download);
            $admin->yesorno("����� �������� ������ ���","edit_download_own",$edit_download_own);
            $admin->yesorno("������� �� ����� �� ����","allow_download_alert",$allow_download_alert);
            $admin->yesorno("����� ������� ���� ������� ","allow_download_rate",$allow_download_rate);
            $admin->closetable();
        
            $admin->opentable("����� ���� �������");
            $admin->yesorno("������� ��� ���� �������","view_link",$view_link);
            $admin->yesorno("����� ���� ����","add_link",$add_link);
            $admin->selectstat("���� �������","stat_link",$stat_link);
            $admin->yesorno("������� �� ����� �� ����","allow_link_alert",$allow_link_alert);
            $admin->yesorno("����� ������� ","allow_link_rate",$allow_link_rate);
            $admin->closetable();
            
            $admin->submit("������ �������");
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
           $admin->windowmsg("<p>�� ����� �������</p>");
        }
        else if($action=="delete")
        {
             extract($_POST);
             
            if(isset($apt->get[id]))
            {
                $admin->head();
                $admin->openform("$PHP_SELF?cat=groups&act=delete&".$admin->get_sess());
                $admin->opentable("����� �����");
                print"<p><font color=\"#FF0000\">��� ��� �� �������� ���� ���� �� ����� ����� ����  ������� ������ ���� ��� �� ��� ������<br>
                      �� ��� �� ���� ���� ����� �������� �������� ��� ������ ���� <br>
                       ���� ��� ��������� ���� ���� ��� ������� �����</font></p>";
                $admin->list_user_level(4," ���� ��� ��������");
                $admin->inputhidden("delid",$apt->get[id]);
                $admin->closetable();
                $admin->submit("����� �����");

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
                        $admin->windowmsg("<p>�� ��� ��������</p>");
                    }
                }
            }
        }
        else if($action=="groups")
        {
            $admin->head();
            $result = $apt->query("SELECT * FROM rafia_groups ORDER BY groupid ASC");

            $admin->opentable("���������");
            while($row = $apt->dbarray($result))
            {
                extract($row);
                $countuserg  =  $apt->dbnumquery("rafia_users","usergroup='$groupid'");
                echo "<tr><td  class=datacell width=60%><b>$grouptitle</b> <br> <a href=index.php?cat=users&groupid=$groupid&".$admin->get_sess()."> ����� �������� </a>  ($countuserg) </td>";
               if(($editble == 1))
               echo "<td width=20% class=datacell align=center><a href=".$PHP_SELF."?cat=groups&act=edit&id=$groupid&".$admin->get_sess().">�����</b></a></td>";
               else
               echo "<td width=20% class=datacell align=center><a href=\"javascript:alert('���� .. ��� ������ ������ �� ����� �������')\">�����</b></a></td>";
                if(($deletble == 1))
               echo "<td width=20% class=datacell align=center><a href=".$PHP_SELF."?cat=groups&act=delete&id=$groupid&".$admin->get_sess()." onClick=\"if (!confirm('�� ���� ����� �� ����ݿ')) return false;\">���</b></a></td>";
               else
               echo "<td width=20% class=datacell align=center><a href=\"javascript:alert('���� .. ��� ������ ������ �� ����� �����')\">���</b></a></td>";
           }
            $admin->closetable();
      }
   }
}
?>