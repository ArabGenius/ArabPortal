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

include('phpmailer/class.phpmailer.php');

class email
{
   var $to_email;
   var $subject;
   var $message ;
   var $from_name;
   var $from_email;
   var $header;
   var $mailuser;
   var $userid;
   var $postid;
   var $field;
   var $use_html;
   var $mail_temp;
   var $phpmailer;
   
//---------------------------------------------------

   function email()
   {
        global $apt;
        
       $this->phpmailer = new phpmailer();

	   if($apt->conf['Mailer'] == 'smtp' )
	   {
	        $this->phpmailer->Host = $apt->conf['smtp_Host'];
	        $this->phpmailer->Port = $apt->conf['smtp_Port'];
	        $this->phpmailer->Username = $apt->conf['smtp_Username'];
	        $this->phpmailer->Password = $apt->conf['smtp_Password'];
	   		$this->phpmailer->IsSMTP();
	   }
	   elseif($apt->conf['Mailer'] == 'sendmail' )
	   {
	    	$this->phpmailer->Sendmail = $apt->conf['sendmail_path'];
	       $this->phpmailer->IsSendmail();
	    }
	   else
	   $this->phpmailer->IsMail();
       
   }
   
//---------------------------------------------------
   function set_use_html($_html)
   {
       $this->use_html = $_html;
   }
   
//---------------------------------------------------
   function send($_toemail,$_subject,$_message,$_from_name,$_from_email,$html=0)
   {
       $this->to_email   = $_toemail;
       $this->subject    = $_subject;
       $this->message    = $_message;
       $this->from_name  = $_from_name;
       $this->from_email = $_from_email;

       if($html == 1)
       {
            $this->send_mail_html();
       }
       else
       {
            $this->send_mail();
       }
   }
//---------------------------------------------------
   function send_mail()
   {
      global $apt;

	$this->phpmailer->From     = $this->from_email;
	$this->phpmailer->FromName = $this->from_name;
	$this->phpmailer->Subject  = $this->subject;
      $this->phpmailer->Body    = $this->message;
      $this->phpmailer->AddAddress($this->to_email);
	if(!$this->phpmailer->Send())
       $return  = true;
     else
	 $return  = false;
	$this->phpmailer->ClearAddresses();
	return $return;
   }
//---------------------------------------------------
   function send_mail_html()
   {

	$this->phpmailer->From     = $this->from_email;
	$this->phpmailer->FromName = $this->from_name;
	$this->phpmailer->Subject  = $this->subject;
      $this->phpmailer->IsHTML(true);
      $this->phpmailer->Body    = $this->message;
      $this->phpmailer->AddAddress($this->to_email);
      $this->phpmailer->MsgHTML($this->phpmailer->Body);
	if(!$this->phpmailer->Send())
      $return  = true;
      else
	$return  = false;
	$this->phpmailer->ClearAddresses();
	return $return;
   }


//---------------------------------------------------

   function mail_user()
   {
        global $apt;
        
        if(($this->mailuser == '1') && ($this->userid > 0 ))
        {
            $check_alertid = $apt->query("SELECT userid FROM rafia_alert
                                            WHERE $this->field='$this->postid'
                                            and userid='$this->userid'");
                                            
             if ($apt->dbnumrows ($check_alertid) == 0)
             {
                 $apt->query ("insert into rafia_alert ($this->field,userid) values
                                                         ('$this->postid','$this->userid')");
             }
        }
   }

//---------------------------------------------------
   function send_to_users($_field,$_userid,$_postid,$url,$_mailuser)
   {
       global $apt;
       
       $this->field      =  $_field;
       $this->userid     =  $_userid;
       $this->postid     =  $_postid;

       if(empty($this->postid))return;

       $name             =  $apt->cookie['cname'] ?  $apt->cookie['cname'] : $apt->post['name'];

       $titlepost        =  $apt->post['titlepost'];
       eval("\$this->subject = \" " . $apt->getsettings("subject_alert") . "\";");
       $this->from_email =  $apt->getsettings("sitemail");
       $this->url        =  $apt->slashedurl($apt->getsettings("siteURL")).$url;
       $this->mailuser   =  $_mailuser;
       $this->from_name  =  $apt->getsettings("sitetitle");

       $name	   = $name;
       $this->subject    = $this->subject;
       $this->from_name    = $this->from_name;

       $this->mail_user();

       
       $result_email     =  $apt->query ("select rafia_users.email,rafia_users.userid, rafia_users.html_msg
                                          from rafia_alert, rafia_users
                                          where rafia_alert.$this->field='$this->postid'
                                          and rafia_alert.sendmsg ='0'
                                          and rafia_alert.userid = rafia_users.userid
                                          and rafia_alert.userid !='$this->userid'");
       while( $row = $apt->dbarray($result_email))
       {
             $this->use_html  = $row[html_msg];
             $this->to_email  = $row[email];
             $userid          = $row[userid];
             $url = $apt->slashedurl($apt->getsettings("siteURL"));
             $delale_url = $url."/members.php?action=delale&$this->field=$this->postid&userid=$userid";

             $replace   =  array(  "{name}"      => $name ,
                                   "{titlepost}" => $apt->post['titlepost'],
                                   "{url}"       => $this->url,
                                   "{delaleurl}" => $delale_url,
                                   "{sitetitle}" => $this->from_name);

             $this->message = $apt->getmessage('msg_new_post',$this->use_html,$replace);
             $this->message = $this->message;
             if($this->use_html ==1)
             {
                 $this->send_mail_html();
             }
             else
             {
                 $this->send_mail();
             }

             $apt->query("UPDATE rafia_alert SET sendmsg='1'
                            WHERE {$this->field}='$this->postid' AND userid='$userid'");
         flush();
        }

    }

//---------------------------------------------------

   function send_to_moderate($cat_id)
   {
       global $apt,$name;
       
       $result = $apt->query("SELECT title,cat_email FROM rafia_cat WHERE id='$cat_id' and cat_email!='NULL'");
	 $url = $_SERVER[PHP_SELF];
       if ($apt->dbnumrows ($result) > 0)
       {
           $row              =  $apt->dbarray($result);
           $this->cat_title  =  $row['title'];
           $this->to_email   =  $row['cat_email'];
           $this->from_email =  $apt->getsettings("sitemail");
           $this->url        =  $apt->slashedurl($apt->getsettings("siteURL")).$url;
           $this->from_name  =  $apt->getsettings("sitetitle");
           $this->subject    =  $this->from_name;
           
           $replace     =  array(  "{name}"      => $name ,
                                   "{titlepost}" => isset($apt->post['titlepost']) ? $apt->post['titlepost'] : $apt->format_post($apt->post['title']),
                                   "{url}"       => $this->url,
                                   "{titlecat}"  => $this->cat_title,
                                   "{sitetitle}" => $this->from_name);

           $this->header = "From: \"".addslashes($this->from_name)."\"<".$this->from_email.">\r\n";
           $this->header.= "Reply-To: ".$this->from_email."\r\n";
           eval ("\$this->message = \"".$apt->getmessage("msg_cat",0,$replace)."\";");
           $this->send($this->to_email,$this->subject,$this->message,$this->from_name,$html=0);
       }
    }
}
?>