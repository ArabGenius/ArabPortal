<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright � 2006   |
|   -------------------------------------   |
|            By Rafia AL-Otibi              |
|                    And                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Net       |
|   -------------------------------------   |
|  Last Updated: 09/11/2006 Time: 02:40 AM  |
+===========================================+
*/

if (stristr($_SERVER['SCRIPT_NAME'], "users.php")) {
    die ("<center><h3>���� ��� ������� ��� ������</h3></center>");
}

class users
{
    function users ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

        if($action=="sendtousers")
        {
            $admin->head();
            
            @extract($_POST);
		$start = (int) $_GET[start];
		if($submit){
		$mail_vars = "$countlist:=:$usergroup:=:$fromail:=:$subject";
            $apt->query("UPDATE rafia_settings set value='$message' where variable='mail_msg'");
            $apt->query("UPDATE rafia_settings set value='$mail_vars' where variable='mail_vars'");
		}else{
		$message = $apt->getsettings("mail_msg");
		$mail_vars = $apt->getsettings("mail_vars");
		$mail_var  = @explode(":=:",$mail_vars);
		$countlist = $mail_var[0];
		$usergroup = $mail_var[1];
		$fromail = $mail_var[2];
		$subject = $mail_var[3];
		}

            if($countlist ==""){$countlist=$start++;}
            if(!isset($start)){$start = 0;}

            $sitetitle = $apt->getsettings("sitetitle");

            $sql = "SELECT * FROM rafia_users";

            if($usergroup != 0)$sql .= " where usergroup='$usergroup'";
            
		$mgrp = $usergroup;
            $sql .= " ORDER BY userid LIMIT $start,$countlist";

            $result = $apt->query($sql);

            $nextpage=$start + $countlist;

            $numrows=$apt->dbnumquery("rafia_users");

            flush();

            while($row = $apt->dbarray($result))
            {
                @extract($row);
                $email= stripslashes($email);
                $username = addslashes($username);
                $mail = new email;
                $umessage = str_replace('{USER_NAME}',$username,$message);
                $mail->send($email,$subject,$umessage,$sitetitle,$fromail,0);
                $username = $umessage = '';
                echo "<div align=\"center\"><table border=\"0\" width=\"90%\">\n<tr><td class=datacell width=\"100%\">�� �������  :  $email <br></td></tr></table></div>";
                flush();
            }
            if($mgrp != 0) $numrows=$apt->dbnumquery("rafia_users where usergroup='$mgrp'");
            if($numrows > ($start + $countlist))
            {                
			echo "<br><div align=\"center\"><table border=\"0\" width=\"90%\">\n<tr><td class=datacell width=\"60%\"><center><a href=index.php?cat=users&act=sendtousers&start=$nextpage&".$admin->get_sess()."><b>���� ��� ������� ������� �� ����� 5 ����� �������� ���������</b></a></center></td></tr></table></div>";
			echo "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?cat=users&act=sendtousers&start=$nextpage&".$admin->get_sess()."\">";            
		}
         }
         else if($action=="search")
         {
             $admin->head();

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
         }
         else if($action=="find")
         {
             extract($_POST);
             
             $searchfor = $apt->format_data($searchfor);

             if ($searchby =="userid")
             {
                 $result = $apt->query("SELECT userid,username,email FROM rafia_users WHERE userid LIKE '%" . $searchfor . "%'");
             }
             else if ($searchby =="username")
             {
                 $result = $apt->query("SELECT userid,username,email FROM rafia_users WHERE username LIKE '%" . $searchfor . "%'");
             }
             else if ($searchby =="email")
             {

                 $result = $apt->query("SELECT userid,username,email FROM rafia_users WHERE email LIKE '%" . $searchfor . "%'");
             }
             else if ($searchby =="password")
             {
                 $searchfor = md5( $searchfor );
                 $result = $apt->query("SELECT userid,username,email FROM rafia_users WHERE password LIKE '%" . $searchfor . "%'");
             }
        
             $numrows = $apt->dbnumrows($result);

             if ( $numrows == "0" )
             {

                 header("Refresh: 1;url=index.php?cat=users&act=search&".$admin->get_sess());
                 $admin->windowmsg("������ �����");
                 exit;
             }
              while($row=$apt->dbarray($result))
              {
                  $resultid .= $row['0'].",";
              }
              $value     =  substr ($resultid,0,strlen($resultid)-1);

              header("Refresh: 1;url=index.php?cat=users&GetByID=".$value."&".$admin->get_sess());
              
              $admin->windowmsg("����� ��� ��� ���� ����� �������");

         }
         else if($action=="emailusers")
         {
             $admin->head();
             $sitemail=  $apt->getsettings('sitemail');
             echo "<form action='index.php?cat=users&act=sendtousers&".$admin->get_sess()."' METHOD=POST>\n";
             $admin->opentable("������ �������");
             $admin->inputtext("��� ���� ������� �� ������","countlist",'50','5');
             $admin->list_user_level(0,"��� ������ ��� ����");
             $admin->inputtext("���� ������","fromail",$sitemail);
             $admin->inputtext("����� �������","subject",'');
             $admin->textarea("�������","message","������ ����� ����� ���� ������� \n",'RTL',15,'<br>�� ���� {USER_NAME} ������ ��� �����');
             $admin->closetable();
             $admin->submit("�������");
        }
         else if($action=="update_u_p")
         {
            $admin->head();
            $start = intval($_GET[start]);
            if(!$start){$start = 0;}
            $perpage = 100;
            $last = $start+$perpage;
            $result =  $apt->query("SELECT userid,username FROM rafia_users WHERE allowpost='yes' LIMIT $start,$perpage");
            while($row = $apt->dbarray($result)){
			$userid = $row[userid];
			$username = $row[username];
			$countN = $apt->dbnumquery("rafia_news","userid=$userid");
			$countF = $apt->dbnumquery("rafia_forum","userid=$userid");
			$countD = $apt->dbnumquery("rafia_download","userid=$userid");
			$countC = $apt->dbnumquery("rafia_comment","userid=$userid");
			$allpost = $countN + $countF + $countD + $countC;
			$user_array[] = "$username => $allpost";
			      $apt->query("update rafia_users set allposts='$allpost' WHERE userid='$userid'");
		}

            $countusers = $apt->dbnumquery("rafia_users","allowpost='yes'");
		$dif		= $countusers - $last;
		if($dif <= '0'){
	            $admin->opentable('���� ���������');
			foreach($user_array as $lines){
				$line = explode(" => ",$lines);
				echo "<tr>
				<td class=datacell width=60%><b>$line[0]</b></td>
				<td width=20% class=datacell align=center>$line[1]</td>
				</tr>";
			}
            	$admin->closetable();
		}else{
	            $admin->opentable('���� ���������');
			foreach($user_array as $lines){
				$line = explode(" => ",$lines);
				echo "<tr>
				<td class=datacell width=60%><b>$line[0]</b></td>
				<td width=20% class=datacell align=center>$line[1]</td>
				</tr>";
			}
            	$admin->closetable();

				echo "<br><center><a href=index.php?cat=users&act=update_u_p&start=$last&".$admin->get_sess()."><b>���� ��� ������ ���� ��������� �� ����� ���� ������� ������</b></a><center><br>";
				echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?cat=users&act=update_u_p&start=$last&".$admin->get_sess()."\">";
		}

        }
        else if($action=="userswait")
        {
            $admin->head();
            @extract($_GET);
            if(!isset($start)){$start = 0;}
            $perpage = "100";
            $result =  $apt->query("SELECT * FROM rafia_users WHERE allowpost='wit'
                                      ORDER BY userid ASC LIMIT $start,$perpage");

            $page_result = $apt->query("SELECT * FROM rafia_users WHERE allowpost='wit'");
            $apt->numrows=$apt->dbnumrows($page_result);
             print $admin->pagenum($perpage,"cat=users&act=userswait&".$admin->get_sess());
            $admin->opentable("����� �� ��� ������ ��������");
            while($row = $apt->dbarray($result))
            {
                extract($row);
                echo "<tr>
                <td  class=datacell width=60%><b>$username</b></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=users&act=editusers&id=$userid&".$admin->get_sess().">�����</b></a></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=users&act=deleteusers&id=$userid&".$admin->get_sess().">���</b></a></td>";
            }
            $admin->closetable();
        }
        else if($action=="addusers")
        {
            $admin->head();
            $admin->opentable("����� ���");
            $admin->openform("$PHP_SELF?cat=users&act=insertusers&".$admin->get_sess());
            $admin->inputtext("��� ��������","username","");
            $admin->inputtext("���� ������","password","");
            $admin->list_user_level(4);
            $admin->inputtext("������ �������","homepage",$homepage);
            $admin->inputtext("������ ���������","email",$email);
            $admin->inputtext("��� ���������","allposts",$allposts,'10');
            $admin->yesorno("��� ������ ����������","showemail",$showemail);
            $admin->yesorno("������� ������� ����� Html","html_msg",$html_msg);
            $admin->yesorno("������ �� ������ ����� ����","statpm",1);
            $admin->selectstheme("portal");
            $allowpost ='yes';
            $admin->input_select("����� �������","allowpost",$allowpost);
            $admin->textarea("�������","signature",'');
            $admin->closetable();
            $admin->submit("������ �������");
        }
       else if ( $action=="insertusers" )
       {
           @extract($_POST);

           $fullarr =  array($username,$password,$email);

           if (!$apt->full($fullarr))
           {
               $admin->windowmsg("��� ����� ��� �������� ����� ������ �������");
               exit;
           }

           $username  = $apt->format_data($username);
           $password  = $apt->format_data(md5($password));
           $homepage  = $apt->format_data($homepage);
           $email     = $apt->format_data($email);
           $signature = $apt->format_post($signature);
           $activate  = $apt->makeactivate();
           $datetime  = time();
           $useradmin  = 0;
           if($usergroup == '1')$useradmin = 1;
           if($usergroup == '2')$useradmin = 2;
           if($usergroup == '3')$useradmin = 3;
           $result=$apt->query("insert into rafia_users (username,
                                                    password,
                                                    useradmin,
                                                    usergroup,
                                                    email,
                                                    allposts,
                                                    homepage,
                                                    showemail,
                                                    signature,
                                                    datetime,
                                                    allowpost,
                                                    activate,
                                                    usertheme,
                                                    statpm,
                                                    html_msg)
                                              values
                                                    ('$username',
                                                    '$password',
                                                    '$useradmin',
                                                    '$usergroup',
                                                    '$email',
                                                    '$allposts',
                                                    '$homepage',
                                                    '$showemail',
                                                    '$signature',
                                                    '$datetime',
                                                    '$allowpost',
                                                    '$activate',
                                                    '$usertheme',
                                                    '$statpm',
                                                    '$html_msg')");

           if ($result)
           {
               header("Refresh: 1;url=index.php?cat=users&".$admin->get_sess());
               $admin->windowmsg("<p>��� ������� ����� </p>");
           }
        }
        else if($action=="editusers")
        {

             $admin->head();
             $id = $apt->setid('id');
             $result = $apt->query ("SELECT * FROM rafia_users where userid=$id");
             $admin->opentable("����������");
             $row = $apt->dbarray($result);

                extract($row);
                $username  = stripslashes($username);
                $password  = stripslashes($password);
                $homepage  = stripslashes($homepage);
                $email     = stripslashes($email);
                $admin->openform("$PHP_SELF?cat=users&act=updateusers&id=$id&".$admin->get_sess());
                $admin->inputtext("��� ��������","username",$username);
                $admin->inputhidden('oldUsername',$username);
                $admin->inputtext("���� ������","password","");
				if($id !== $apt->Guestid){
				$admin->list_user_level($usergroup);
				}else {
                $admin->inputhidden('usergroup',$usergroup);
				echo  "<tr><td class=datacell width=\"33%\">"._GROUP.":</td><td class=datacell width=72%>������ ������</td></tr>";
				}
                $admin->inputtext("������ �������","homepage",$homepage);
                $admin->inputtext("������ ���������","email",$email);
                $admin->inputtext("��� ���������","allposts",$allposts,'10');
                $admin->yesorno("��� ������ ����������","showemail",$showemail);
                $admin->yesorno("������� ������� ����� Html","html_msg",$html_msg);
                $admin->yesorno("������ �� ������ ����� ����","statpm",$statpm);
                $admin->selectstheme($usertheme);
                $admin->input_select("����� �����","allowpost",$allowpost);
                $admin->inputhidden('useradmin',$useradmin);
                $admin->textarea("�������","signature",$signature);
                $admin->closetable();
                $admin->submit("������ �������");
        }
        else if($action=="updateusers")
        {
            extract($_POST);

            if($username != $oldUsername){
            if ($apt->dbnumquery("rafia_users","username='$username' and userid !='$id'")>0){$admin->windowmsg("���� ��� �������� ����� �����"); die;}
		}

            $showemail = $apt->adminunset($showemail);
            $id = $apt->setid('id');
            if (!empty($password))
            {
                $password  = $apt->format_data(md5($password));

                $result    = $apt->query("update rafia_users set
                                            password='$password'
                                            where userid=$id");
            }
            $username  = $admin->format_data($apt->post[username]);
            $homepage  = $admin->format_data($apt->post[homepage]);
            $email     = $admin->format_data($apt->post[email]);
            $signature = $admin->format_data($apt->post[signature]);

           $useradmin  = 0;
           if($usergroup == '1')$useradmin = 1;
           if($usergroup == '2')$useradmin = 2;
           if($usergroup == '3')$useradmin = 3;

           $result    = $apt->query("update rafia_users set
                                      username='$username',
                                      usergroup='$usergroup',
                                      email='$email',
                                      useradmin='$useradmin',
                                      allposts='$allposts',
                                      homepage='$homepage',
                                      showemail='$showemail',
                                      signature='$signature',
                                      allowpost='$allowpost',
                                      usertheme='$usertheme',
                                      statpm='$statpm',
                                      html_msg='$html_msg'
                                      where userid=$id");
            if ($result)
            {
                $oldUsername  = $apt->format_data($oldUsername);
                if($username != $oldUsername)
                {
                    $apt->query("update rafia_news set name='$username' where userid=$id");
                    $apt->query("update rafia_comment set name='$username' where userid=$id");
                    $apt->query("update rafia_forum set name='$username' where userid=$id");
                    $apt->query("update rafia_download set name='$username' where userid=$id");
                    $apt->query("update rafia_moderate set moderatename='$username' where moderateid=$id");
                    $apt->query("update rafia_messages set fromname='$username' where ufromid=$id");
                }
                header("Refresh: 1;url=index.php?cat=users&".$admin->get_sess());
                $admin->windowmsg("<p>&nbsp;�� ����� ������� </p>");
            }
        }
        else if($action=="showavatar"){

     $id = $apt->setid('id');
     $avatarfile = $apt->upload_path."/".$id.".".'avatar';
     if(file_exists($avatarfile)){
     $filesize = @filesize($avatarfile);
     header("Content-type: image/gif");
     header("Content-disposition: inline; filename=".$id.".gif");
     header("Content-Length: $filesize");
     header("Pragma: no-cache");
     header("Expires: 0");
     $open = @fopen($avatarfile,r);
     $data = @fread($open,$filesize);
     @fclose($open);
     print($data);
     }
exit;
        }
        else if($action=="avatars")
        {
            $admin->head();
            $start = (int) $apt->get[start];
            if(!isset($start)){$start = 0;}
            $perpage = "30";
            $result = $apt->query("SELECT * FROM rafia_users  ORDER BY userid ASC LIMIT $start,$perpage");
            $page_result = $apt->query("SELECT * FROM rafia_users");
           
            $apt->numrows    = $apt->dbnumrows($page_result);
            print $admin->pagenum($perpage,"cat=users&act=avatars&".$admin->get_sess());
            $admin->opentable("����������",4);
            while($row = $apt->dbarray($result))
            {
                extract($row);
                echo "<tr><td  class=datacell width=5%><b>$userid</b></td>
                <td  class=datacell width=50%><b>$username</b></td>";
     $avatarfile = $apt->upload_path."/".$userid.".".'avatar';
     if(file_exists($avatarfile)){
                echo "<td width=30% class=datacell align=center><img src=$PHP_SELF?cat=users&act=showavatar&id=$userid&".$admin->get_sess()."></td>";
                echo "<td width=20% class=datacell align=center><a href=$PHP_SELF?cat=users&act=delavatar&id=$userid&".$admin->get_sess()."  onClick=\"if (!confirm('�� ���� ����� �� ����ݿ')) return false;\">���</b></a></td>";
                }else{
                echo "<td width=30% class=datacell align=center>�� ���� ����</td>";
                echo "<td width=20% class=datacell align=center>�� ���� ����</td>";
                }
            }
            $admin->closetable();
            print $admin->pagenum($perpage,"cat=users&act=avatars&".$admin->get_sess()) . "<br>";
        }
        else if($action=="delavatar")
        {
     $id = $apt->setid('id');
     $avatarfile = $apt->upload_path."/".$id.".".'avatar';
	$okdel = @unlink($avatarfile);
	if($okdel){$admin->windowmsg("<p>�� ��� ���� �������� </p>","$PHP_SELF?cat=users&act=avatars&".$admin->get_sess());}else{	
	$admin->windowmsg("<p>��� ��� ��� ��� ���� �������� </p>","$PHP_SELF?cat=users&act=avatars&".$admin->get_sess());}
        }
        else if($action=="deleteusers")
        {
            $id = $apt->setid('id');
		if(( $id == 0) || ($id == $apt->Guestid)){
		header("Refresh: 3;url=index.php?cat=users&".$admin->get_sess());
		$admin->windowmsg("���� .. �� ���� ��� ����� ������");
		die;
		}
            $result = $apt->query("delete from rafia_users where userid=$id");
            if ($result){
            $apt->query("update rafia_news set userid='$apt->Guestid' where userid=$id");
            $apt->query("update rafia_forum set userid='$apt->Guestid' where userid=$id");
            $apt->query("update rafia_download set userid='$apt->Guestid' where userid=$id");
            $apt->query("update rafia_comment set userid='$apt->Guestid' where userid=$id");
            $admin->windowmsg("<p>�� ��� �������� </p>");
            }
        }
        else if($action=="")
        {
            $admin->head();

            $start = $apt->get[start];
            if(!isset($start)){$start = 0;}
            $perpage = "50";
            if(isset($apt->get['GetByID']))
            {
                $GetByID = $apt->get['GetByID'];

                $result  = $apt->query("SELECT * FROM rafia_users  where userid in($GetByID) ORDER BY userid ASC LIMIT $start,$perpage");
                $page_result = $apt->query("SELECT * FROM rafia_users where userid in('$GetByID')");

            }
            elseif(isset($apt->get['groupid']))
            {
                $groupid = $apt->setid('groupid');
                $result  = $apt->query("SELECT * FROM rafia_users  where usergroup='$groupid' ORDER BY userid ASC LIMIT $start,$perpage");
                $page_result = $apt->query("SELECT * FROM rafia_users where usergroup='$groupid'");
            }
            else
            {
                $result = $apt->query("SELECT * FROM rafia_users  ORDER BY userid ASC LIMIT $start,$perpage");
                $page_result = $apt->query("SELECT * FROM rafia_users");
            }
            
            $apt->numrows    = $apt->dbnumrows($page_result);
            print $admin->pagenum($perpage,"cat=users&".$admin->get_sess());
            $admin->opentable("����������",4);
            while($row = $apt->dbarray($result))
            {
                extract($row);
                echo "<tr><td  class=datacell width=5%><b>$userid</b></td>
                <td  class=datacell width=60%><b>$username</b></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=users&act=editusers&id=$userid&".$admin->get_sess().">�����</b></a></td>
                <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=users&act=deleteusers&id=$userid&".$admin->get_sess()."  onClick=\"if (!confirm('�� ���� ����� �� ����ݿ')) return false;\">���</b></a></td>";
            }
            $admin->closetable();
            print $admin->pagenum($perpage,"cat=users&".$admin->get_sess());
        }
        else if($action=="uptitle")
        {
            extract($_POST);
               $id = $apt->setid('id');
            $result = $apt->query("update rafia_usertitles set title ='$title',
                                                       posts ='$posts',
                                                       Iconrep ='$Iconrep'
                                                       where id=$id");
            if ($result)
            {
                $admin->windowmsg("�� ����� ����� �����");
            }
        }
        else if($action=="insertitle")
        {
            extract($_POST);

            $result = $apt->query("insert into rafia_usertitles
                            (title,posts,Iconrep) values
                            ('$title','$posts','$Iconrep')");
            if ($result){$admin->windowmsg("��� ������� �����");}
        }
        else if($action=="edititles")
        {
            $id = $apt->setid('id');
            $admin->head();
            $result = $apt->query("select * from rafia_usertitles where id='$id'");
            $row = $apt->dbarray($result);
            extract($row);
            $admin->openform("$PHP_SELF?cat=users&act=uptitle&id=$id&".$admin->get_sess());
            $admin->opentable("����� ");
            $admin->inputtext('�����','title',$title);
            $admin->inputtext('��� ��� ��� ������� �','posts',$posts);
            $admin->inputtext('��� ����� ������ �����','Iconrep',$Iconrep);
            $admin->closetable();
            $admin->submit  ("�����");
        }
        else if($action=="deltitles")
        {
            $id = $apt->setid('id');
            $result = $apt->query("DELETE FROM rafia_usertitles WHERE id ='$id'");
            if ($result){$admin->windowmsg("�� ����� �����");}
        }
        else if($action=="usertitles")
        {
            $admin->head();
            $admin->openform("$PHP_SELF?cat=users&act=insertitle&".$admin->get_sess());
            $admin->opentable("����� ���");
            $admin->inputtext('�����','title',"");
            $admin->inputtext('��� ��� ��� ������� �','posts',"");
            $admin->inputtext('��� ����� ������ �����','Iconrep',"");
            $admin->closetable();
            $admin->submit  ("�����");

            $admin->opentable("����� �������","6");
            echo "<tr> ";
              $admin->td_msgh("�����",15);
              $admin->td_msgh("��� ��� ��� �������",30);
              $admin->td_msgh("��� �������",15);
              $admin->td_msgh("������ ������",20);
              $admin->td_msgh(" �����",10);
              $admin->td_msgh("��� ",10);
	        echo "</tr>";
            $result = $apt->query("SELECT * FROM rafia_usertitles WHERE title  !='0' ORDER BY id");
            while($row=$apt->dbarray($result))
            {
                extract($row);
                $usertitles    =  explode("-", $apt->userRating($posts));
                $userimgtitle  =  $usertitles[0];
                $userimgtitle  =  str_replace('images/','./../images/',$userimgtitle);
                echo "<tr> ";
                $admin->td_msg("$title",15);
                $admin->td_msg("$posts",30);
                $admin->td_msg("$Iconrep",15);
                $admin->td_msg("$userimgtitle",20);
                $admin->td_url("$PHP_SELF?cat=users&act=edititles&id=$id&".$admin->get_sess(), "�����",10);
                $admin->td_url("$PHP_SELF?cat=users&act=deltitles&id=$id&".$admin->get_sess(), "���",10);
                echo "</tr>";

            }
            $admin->closetable();

        }
    }
}
?>