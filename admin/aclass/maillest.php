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

if (stristr($_SERVER['SCRIPT_NAME'], "maillest.php")) {
    die ("<center><h3>ÚİæÇ åĞå ÇáÚãáíÉ ÛíÑ ãÔÑæÚÉ</h3></center>");
}

class maillest
{
    function maillest ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);
        
        if($action=="send")
        {
            $admin->head();
            
            extract($_POST); @extract($_GET);
            
            if($countlist == ""){$countlist=$start++;}
            
            if(!isset($start)){$start = 0;}

            $sitetitle = $apt->getsettings("sitetitle");

            $result    = $apt->query("SELECT * FROM rafia_email ORDER BY id LIMIT $start,$countlist");
		$mail_vars = "$countlist:=:$fromail:=:$subject";
		if($start == 0){
		$apt->query("update rafia_settings set value='$message' where variable='mail_msg'");
		$apt->query("update rafia_settings set value='$mail_vars' where variable='mail_vars'");
		}
		if($start !== 0){
		$message = $apt->getsettings("mail_msg");
		$mail_vars = $apt->getsettings("mail_vars");
		$array = explode(":=:",$mail_vars);
		$countlist = $array[0];
		$email = $array[2];
		$subject = $array[4];
		}
            $subject = stripslashes($subject);
            $message = stripslashes($message);
            $nextpage  = $start + $countlist;
            $numrows   = $apt->dbnumquery("rafia_email");

		//die($message);

            while($row = $apt->dbarray($result))
            {
                extract($row);
                $mail = new email;
                $mail->send($email,$subject,$message,$sitetitle,$fromail,$ishtml);
                echo "<div align=\"center\"><table border=\"0\" width=\"90%\"><tr><td class=datacell width=\"100%\">Êã ÇáÇÑÓÇá  :  $email <br></td></tr></table></div>";
                flush();
            }

            if($numrows > ($start + $countlist))
            {
		    $admin->windowmsg("<meta http-equiv='refresh' content='5;url=index.php?cat=maillest&act=send&start=$nextpage&ishtml=$ishtml&".$admin->get_sess()."'>
			<p>ÓíÊã ãÊÜÇÈÚÉ ÇáãÑÇÓáÉ ÈÚÏ 5 ËæÇäí .. äÑÌæÇ ÇáÇäÊÙÇÑ</p>");

           }
        }
        else if($action=="emails")
        {

            $admin->head();
            $countusers =$apt->dbnumquery("rafia_email");

            $sitemail =  $apt->getsettings('sitemail');
            echo"ÚÏÏ ÇÚÖÇÁ ÇáŞÇÆãÉ :  $countusers";
            $admin->openform("$PHP_SELF?cat=maillest&act=send&".$admin->get_sess());
            $admin->opentable("ãÑÇÓáÉ ÇáÇÚÖÇÁ");
            $admin->inputtext("ÚÏÏ ãÑÇÊ ÇáÇÑÓÇá İí ÇáÕİÍÉ","countlist",'50','5');
            $admin->inputtext("ÈÑíÏ ÇáãÑÓá","fromail",$sitemail);
            $admin->inputtext("ãæÖæÚ ÇáÑÓÇáÉ","subject",'');
            $admin->textarea("ÇáÑÓÇáÉ","message","ÇáÓáÇã Úáíßã æÑÍãÉ Çááå",'RTL',20);
            $admin->yesorno("ÇáÑÓÇáÉ ÈÊäÓíŞ HTML", 'ishtml',0);
            $admin->closetable();
            $admin->submit("ÅÑÓÜÜÇá");
        }
        else if($action=="list")
        {
            $admin->head();
            $start = $apt->get[start];
            if(!isset($start)){$start = 0;}
            
            $perpage = "50";

            $result         = $apt->query("SELECT * FROM rafia_email  ORDER BY id ASC LIMIT $start,$perpage");
            $page_result    = $apt->query("SELECT * FROM rafia_email");
            $apt->numrows = $apt->dbnumrows($page_result);
              print $admin->pagenum($perpage,"cat=maillest&act=list&".$admin->get_sess());
            $admin->opentable("ÇáŞÇÆãÉ ÇáÈÑíÏíÉ");
            
            while($row = $apt->dbarray($result))
            {
                extract($row);
                echo "<tr> ";
                $admin->td_msg("$email");
                $admin->td_url("$PHP_SELF?cat=maillest&act=edit&id=$id&".$admin->get_sess(), "ÊÍÑíÑ");
                $admin->td_url("$PHP_SELF?cat=maillest&act=delete&id=$id&".$admin->get_sess(), "ÍĞİ");
                echo "</tr>";
            }
            $admin->closetable();
          print $admin->pagenum($perpage,"cat=maillest&act=list&".$admin->get_sess());
        }
        else if($action=="admin")
        {

            $admin->head();

            $admin->openform("$PHP_SELF?cat=maillest&act=search&".$admin->get_sess());
            $admin->opentable("ÇÈÍË Úä ÈÑíÏ İí ÇáŞÇÆãÉ");
            $admin->inputtext("ÓÌá ÇáÈÑíÏ ÇáãØáæÈ ÇáÈÍË ÚäÉ","searchfor","");
            $admin->closetable();
            $admin->submit("ÅÑÓÜÜÇá");
    
            $admin->openform("$PHP_SELF?cat=maillest&act=backup&".$admin->get_sess());
            $admin->opentable("äÓÎ  ÇáŞÇÆãÉ ÇáÈÑíÏíÉ");
            $admin->inputtext("ÇĞÇ ßäÊ ÊÑÛÈ İí ÊÍÏíÏ İÇÕáÉ ãÚíäÉ Èíä ÇáÈÑíÏ æÇáÇÎÑ  <br> ÇĞÇáã ÊÖÚ İÇÕáÉ Óæİ íØÈÚ ßá ÈÑíÏ İí ÓØÑ","backupemail","");
            $admin->closetable();
            $admin->submit("ÅÑÓÜÜÇá");
    
            $admin->openformdata("$PHP_SELF?cat=maillest&act=download&".$admin->get_sess());
            $admin->opentable("ÇÖÇİÉ ŞÇÆãÉ ÈÑíÏíÉ");
            $admin->inputtext("ÇßÊÈ ÇáİÇÕá ÇáãæÌæÏ Èíä ÇáÇíãíáÇÊ Çæ ÇÊÑß ÇáÎÇäÉ İÇÖíÉ ÇĞÇ ßÇä ßá ÈÑíÏ İí ÓØÑ æáÇ íæÌÏ Èíäåã Çí İÇÕá","backupemail","");
            $admin->inputfile("ÑİÚ ãáİ ÇáŞÇÆãÉ ÇáÈÑíÏíÉ");
            $admin->closetable();
            $admin->submit("ÅÑÓÜÜÇá");

        }
        else if($action=="search")
        {
            extract($_POST);
            
            if(isset($searchfor))
            $searchfor = $apt->format_data($searchfor);

            $result = $apt->query("SELECT id,email FROM rafia_email WHERE email LIKE '%" . $searchfor . "%'");

            $numrows = $apt->dbnumrows($result);

            if ( $numrows == "0" )
            {
                   $admin->windowmsg("áÇíæÌÏ äÊíÌÉ");

                exit;
            }
            $admin->head();
            $admin->opentable("äÇÊÌ ÇáÈÍË");
           while($row=$apt->dbarray($result))
           {
              extract($row);
              echo "<tr> ";
              $admin->td_msg("$email");
              $admin->td_url("$PHP_SELF?cat=maillest&act=edit&id=$id&".$admin->get_sess(), "ÊÍÑíÑ");
              $admin->td_url("$PHP_SELF?cat=maillest&act=delete&id=$id&".$admin->get_sess(), "ÍĞİ");
              echo "</tr>";
          }
          
          $admin->closetable();
       }
       else if($action=="edit")
       {
           $admin->head();
           $id = $apt->setid('id');
           $result = $apt->query ("SELECT * FROM rafia_email where id=$id");
           $admin->opentable("ÊÍÑíÑ ÈÑíÏ");
           $row = $apt->dbarray($result);

               extract($row);
               $email     = stripslashes($email);
               $admin->openform("$PHP_SELF?cat=maillest&act=update&id=$id&".$admin->get_sess());
               $admin->inputtext("ÇáÈÑíÏ ÇáÇßÊÑæäí","email",$email);
               $admin->closetable();
               $admin->submit("ÇÚÊãÇÏ ÇáÊÚÏíá");
       }
       else if($action=="delete")
       {
           $id = $apt->setid('id');
          $result = $apt->query("delete from rafia_email where id='$id'");
           if ($result)
           {
               $admin->windowmsg("<p>Êã ÍĞİ ÇáÈÑíÏ ÈäÌÇÍ</p>");
           }
       }
       else if($action=="update")
       {
           extract($_POST);
           $id = $apt->setid('id');
           $result    = $apt->query("update rafia_email set email='$email' where id='$id'");
            if ($result)
           {
               $admin->windowmsg("<p>Êã ÇáÊÍÑíÑ ÈäÌÇÍ</p>");
           }
       }
       else if($action=="backup")
       {

           extract($_POST);

               header("Content-disposition: filename=email.php");
               header("Content-type: unknown/unknown");
	           header("Pragma: no-cache");
	           header("Expires: 0");
            
	       $result = $apt->query("select email from rafia_email ORDER BY id ASC");

           while($row =$apt->dbarray($result))
          {

              $email     = stripslashes($row[email]);
              if($backupemail =="")
              {
                  echo "$email\n";
              }
              else
              {
                  echo "$email".$backupemail;
              }
          }
       }
       else if($action=="download")
       {
		$backupemail = $_POST[backupemail];
           $tmp_name =  $_FILES["name_file"]["tmp_name"];
           require_once('./../func/Files.php');

            if($backupemail =="")
            {
                $file_tmp = Files::read($tmp_name);

                $file_tmp = explode("\n",$file_tmp);
                   while($emialarr = each ($file_tmp))
                   {
                       $email = trim($emialarr["value"]);
                
                       if(($email) && (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email)))
                       {
                           $result  = $apt->query("insert into rafia_email (email) values ('$email')");

                           $insert_count++;
                       }

                   }
              }
              else
              {
                 if(isset($backupemail))
                 {
                require_once('./../func/Files.php');
                $file_tmp = Files::read($tmp_name);

                     $emialarr = explode($backupemail,$file_tmp);
                     $count    = count($emialarr);
                     for ($i = 0; $i < $count; $i++)
                     {
                         $email = trim($emialarr[$i]) ;
                         if(($email) && (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email)))
                         {
                             $result  = $apt->query("insert into rafia_email (email) values ('$email')");

                              $insert_count++;
                         }
                     }
                  }
              }
              if ($result)
              {
                  $admin->windowmsg("<p>Êã ÇÖÇİÉ  $insert_count ÈÑíÏ ÇßÊÑæäí </p>");
              }
              else
              {
                  $admin->windowmsg("<p>ÚİæÇ .. ÍÕá ÎØÃ İí ÇáÇÏÑÇÌ</p>");
              }
         }
    }
}
?>
