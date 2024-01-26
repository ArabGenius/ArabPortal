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

if (stristr($_SERVER['SCRIPT_NAME'], "poll.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class poll
{
    function poll ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

        if ($action=="")
        {
            $admin->head();

            $result = $apt->query("SELECT * FROM rafia_pollques ORDER BY quesid DESC");

            $admin->opentable("ﬁ«∆„… «·«” › «¡",4);
            print "<tr> ";
            $admin->td_msgh("—ﬁ„",5);
            $admin->td_msgh("«·Õ«·…",10);
            $admin->td_msgh("«·”ƒ«·",40);
            $admin->td_msgh(" Õ—Ì—",10);
	        print "</tr>";
         
            while($row = $apt->dbarray($result))
            {
                @extract($row);
                echo "<tr> ";
                
                $admin->openform("$PHP_SELF?cat=poll&act=edit&id=$quesid&".$admin->get_sess());
                
                $admin->td_msg("$quesid",5);
                
                if($stat ==1)
                {
                    $msg = "Ì⁄„·";
                    $admin->td_msg($msg,10);
                }
                else
                {
                    $msg = "«—‘Ì›";
                    $admin->td_msg($msg,10);
                }
                 
                $admin->td_msg("$question" ,40);
               echo "<td class=datacell width=\"20%\" bgcolor='D2D4D4'>
               <FORM METHOD=POST ACTION=\"$PHP_SELF?cat=poll&act=edit&id=$quesid&".$admin->get_sess()."\">
               <SELECT NAME=\"pollstat\">";
               echo "<option value='1'>Ì⁄„·</option>";
               echo "<option value='0'>«—‘Ì›</option>";
               echo "<option value='editp'> ⁄œÌ·</option>";
               echo "<option value='del'>Õ–›</option>";
               echo "</SELECT>
               <INPUT TYPE=\"submit\"  value=\" ‰›Ì–\"></FORM></td>";
               echo "</tr>";
            }
            $admin->closetable();
     }
     else if($action == "add")
     {
         @extract($_POST);
         if(!$submit)
         {
             $admin->head();
             $admin->opentable("⁄œœ «·«Ã«»«  ·Â–« «· ’ÊÌ ");
             $admin->openform("$PHP_SELF?cat=poll&act=add&".$admin->get_sess());
             $admin->inputtext("⁄œœ «·«Ã«»« ø","num",'',10);
             $admin->closetable();
             $admin->submit("„Ê«›ﬁ");
         }
         elseif($submit && $num)
         {
             $admin->head();
             $admin->opentable("≈÷«›…  ’ÊÌ  ÃœÌœ");
             $admin->openform("$PHP_SELF?cat=poll&act=insert&".$admin->get_sess());
             $admin->inputtext("«·”ƒ«·","question",'',40);
             $i = 1;
             
             while ($i <= $num)
             {
                 $admin->inputtext("«·ÃÊ«» :  $i","answers[$i]","",20);

                 $i = $i + 1;
             }

             $admin->inputhidden('H',$num);
             $admin->closetable();
             $admin->submit("≈÷«›…");

             $admin->opentable(" ÕœÌÀ ⁄œœ «·«Ã«»« ");
             $admin->openform("$PHP_SELF?cat=poll&act=add&".$admin->get_sess());
             $admin->inputtext("⁄œœ «·«Ã«»« ø","num",'',10);
             $admin->closetable();
		 $admin->submit(" ÕœÌÀ «·⁄œœ");

         }
     }
     else if($action =="insert")
     {
         extract($_POST);

         $num2 = $H;
         $question = $admin->format_data($apt->post[question]);
         $apt->query("INSERT INTO rafia_pollques (question,stat) VALUES ('$question','1')");

         $id = $apt->insertid();
         $i = 1;
         while ($i <= $num2)
         {
             $answer = $admin->format_data($answers[$i]);
             $apt->query("INSERT INTO rafia_pollanswer (quesid, text, results) VALUES ('$id','$answer', '0')");

             $i = $i + 1;
         }
         $url = "index.php?cat=poll&".$admin->get_sess();
         header("Refresh: 1;url=$url");
         $admin->windowmsg(" „  «·«÷«›… »‰Ã«Õ");
     }
     else if($action =="edit")
     {
         $id = $apt->setid('id');
         $stat = $apt->post[pollstat];

         if ($stat == 'del')
         {
             $Output = " Â· «‰  „ «ﬂœ „‰ —€» ﬂ ›Ì Õ–› Â–« «·”ƒ«· ø Ê«·–Ì —ﬁ„… ".$id." <br> ";
             $Output .=   "<a href=\"$PHP_SELF?cat=poll&act=delete&id=$id&".$admin->get_sess()."\"> Õ–› </a>";
             $admin->windowmsg($Output);
           exit;
         }
         elseif ($stat == 'editp')
         {

        $admin->head();
        $result = $apt->query("SELECT * FROM rafia_pollques WHERE quesid='$id'");
        $que = $apt->dbarray($result);
        $question = $que['question'];
        $admin->opentable(" ⁄œÌ·  ’ÊÌ ");
        $admin->openform("$PHP_SELF?cat=poll&act=updatepoll&id=$id&".$admin->get_sess());
        $admin->inputtext("«·”ƒ«·","question","$question",40);

        $answers = $apt->query("SELECT * FROM rafia_pollanswer WHERE quesid='$id'");
        while($row = $apt->dbarray($answers)){
          extract($row);
          $admin->inputtext("«·ÃÊ«» $i","ans[$pollid]","$text",35);
	  }

             $admin->closetable();
             $admin->submit(" ÕœÌÀ");

        }elseif (($stat == '0') or ($stat == '1'))
        {
             $apt->query( "UPDATE rafia_pollques SET stat='$stat' WHERE quesid='$id'");
             $url = "index.php?cat=poll&".$admin->get_sess();
             $admin->windowmsg(" „  Õ—Ì— «· ’ÊÌ ",$url);
        }

     }
     else if($action =="updatepoll")
     {
          $id = $apt->setid('id');
	    @extract($_POST);
         // die(print_r($ans,1));
	    $result = $apt->query("update rafia_pollques set question='$question' where quesid=$id");
         foreach($ans as $ansid=>$answer)
         {
             $answer = $admin->format_data($answer);
             $apt->query("update rafia_pollanswer set `text`='$answer' where pollid='$ansid';");
         }
	$admin->windowmsg("<p>  „ «· ÕœÌÀ »‰Ã«Õ  </p>","index.php?cat=poll&".$admin->get_sess());
     }
     else if($action =="delete")
     {
          $id = $apt->setid('id');
         $result = $apt->query("delete from rafia_pollques where quesid=$id");

         if ($result)
         {
             $apt->query("delete from rafia_pollanswer where quesid=$id");
             $url = "index.php?cat=poll&".$admin->get_sess();
             header("Refresh: 1;url=$url");
             $admin->windowmsg("<p>  „ «·Õ–›  </p>");
         }
      }
   }
}
?>
