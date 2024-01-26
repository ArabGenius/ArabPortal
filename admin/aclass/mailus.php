<?php
if (stristr($_SERVER['SCRIPT_NAME'], "checknew.php")) {
    die ("<center><h3>ÚİæÇ åĞå ÇáÚãáíÉ ÛíÑ ãÔÑæÚÉ</h3></center>");
}

class mailus
{

   function mailus($action){
	global $apt,$admin;
	$action = $apt->format_data($action);

	if($action=="read"){
           $admin->head();
           $id = intval($apt->get['id']);
           $result = $apt->query("select * from rafia_messages where msgid='$id'");
           $msg = $apt->dbarray($result);
           extract($msg);
           $admin->opentable($msgtitle);
           echo "<tr><td width=20% class=datacell align=center><b>ãÑÓáÉ ãä:</b></td>
           <td width=80% class=datacell align=center><b>$fromname</b></td></tr>";
           echo "<tr><td width=20% class=datacell align=center><b>ÈÊÇÑíÎ:</b></td>
           <td width=80% class=datacell align=center><b>".$apt->Hijri($msgdate)."</b></td></tr>";
           $inf = explode("=^+^=",$message);
           echo "<tr><td width=20% class=datacell align=center><b>ÇáÈÑíÏ ÇáÇáßÊÑæäí:</b></td>
           <td width=80% class=datacell align=center><b><a title='ÅÖÛØ åäÇ ááÑÏ' href=$PHP_SELF?cat=mailus&act=reply&id=$id&".$admin->get_sess().">ÅÖÛØ åäÇ ááÑÏ Úáì ".$inf[0]."</a></b></td></tr>";
           echo "<tr><td colspan='2' width=20% class=datacell><b>".nl2br($inf[1])."</b></td></tr>";
           $admin->closetable();
           $apt->query("update rafia_messages set msgisread='2' where msgid='$id'");
	}elseif($action=="reply"){
           $admin->head();
           $id = intval($apt->get['id']);
           $result = $apt->query("select * from rafia_messages where msgid='$id'");
           $msg = $apt->dbarray($result);
           extract($msg);
           $inf = explode("=^+^=",$message);

          echo"<form action=$PHP_SELF?cat=mailus&act=send&".$admin->get_sess()." method='post'>";
          $admin->opentable("ÑÏ Úáì ÇáÑÓÇáÉ");
          echo "<tr><td width=20% class=datacell>ÅáÜì :</td>
          <td width=80% class=datacell>".$inf[0]."</td></tr>";
          $admin->inputtext("ÚäæÇä ÇáÑÓÇáÉ","sub",'ÑÏ:'."$msgtitle");
          $admin->textarea("äÕ ÇáÑÓÇáÉ","textmsg","\n\n\n\n\n\n ======= ÑÓÇáÊß ÇáÓÇÈŞÉ ======= \n".$inf[1],'RTL');
          $admin->inputhidden('email',$inf[0]);
          $admin->closetable();
          $admin->submit("ÅÑÓÜÇá");

	}elseif($action=="send"){
		extract($_POST);
		$mail = new email;
            $sitetitle = $apt->getsettings("sitetitle");
            $fromail   = $apt->getsettings("sitemail");
		$mail->send($email,$sub,$textmsg,$sitetitle,$fromail,0);
		header("Refresh: 1;url=index.php?cat=mailus&".$admin->get_sess());
            $admin->head();
		$admin->windowmsg("<p>Êã ÇÑÓÇá ÇáÑÓÇáÉ </p>");

	}elseif($action=="del"){
           $id = intval($apt->get['id']);
           $result = $apt->query("delete from rafia_messages where msgid='$id'");
           if ($result){
               header("Refresh: 1;url=index.php?cat=mailus&".$admin->get_sess());
               $admin->head();
               $admin->windowmsg("<p>Êã ÍĞİ ÇáÑÓÇáÉ </p>");
            }else{
               $admin->head();
               $admin->windowmsg("<p>ÎØÃ İí ÍĞİ ÇáÑÓÇáÉ</p>");
            }
	}else{
           $admin->head();
            $result =  $apt->query("SELECT * FROM rafia_messages WHERE mailus='1' order by msgid DESC;");
            $numrows = $apt->dbnumrows($result);
            if($numrows == 0)
            {
 			$admin->windowmsg("<p>ÚİæÇ .. áÇ ÊæÌÏ Çí ÑÓÇáÉ</p>");
            }else{

            while($msgs = $apt->dbarray($result))
            {
                 extract($msgs);
                 if($msgisread == 1){
                 $newmsginf[] = "$msgid|_+^|$msgdate|_+^|$msgtitle|_+^|$fromname|_+^|$ufromid|_+^|$message";
                 }else{
                 $oldmsginf[] = "$msgid|_+^|$msgdate|_+^|$msgtitle|_+^|$fromname|_+^|$ufromid|_+^|$message";
                 }
            }

		$c_new = count($newmsginf);
		$c_old = count($oldmsginf);
		if(!$c_new == 0){
            $admin->opentable("ÚÏÏ ÇáÑÓÇÆá ÇáÌÏíÏÉ [ $c_new ]",4);
            foreach($newmsginf as $msg)
            {
                 $msginf = @explode("|_+^|",$msg);
                 print "<tr><td  class=datacell width=40%><a href=$PHP_SELF?cat=mailus&act=read&id=$msginf[0]&".$admin->get_sess()."><b>$msginf[2]</b></a></td>
                 <td width=20% class=datacell align=center>$msginf[3]</b></td>
                 <td width=25% class=datacell align=center>".$apt->Hijri($msginf[1])."</td>
                 <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=mailus&act=del&id=$msginf[0]&".$admin->get_sess().">ãÓÍ</b></a></td>";
            }
            $admin->closetable();
		}else{
            $admin->opentable("ÚÏÏ ÇáÑÓÇÆá ÇáÌÏíÏÉ [ $c_new ]",1);
			echo "<tr><td width=20% class=datacell align=center><b>áÇ íæÌÏ ÑÓÇÆá ÌÏíÏÉ</b></td></tr>";
            $admin->closetable();
		}

		echo "<br>";
		if(!$c_old == 0){
            $admin->opentable("ÚÏÏ ÇáÑÓÇÆá ÇáãİÑæÁÉ [ $c_old ]",4);
            foreach($oldmsginf as $msg)
            {
                 $msginf = @explode("|_+^|",$msg);
                 print "<tr><td  class=datacell width=40%><a href=$PHP_SELF?cat=mailus&act=read&id=$msginf[0]&".$admin->get_sess()."><b>$msginf[2]</b></a></td>
                 <td width=20% class=datacell align=center>$msginf[3]</b></td>
                 <td width=25% class=datacell align=center>".$apt->Hijri($msginf[1])."</td>
                 <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=mailus&act=del&id=$msginf[0]&".$admin->get_sess().">ãÓÍ</b></a></td>";
            }
            $admin->closetable();
		}else{
            $admin->opentable("ÚÏÏ ÇáÑÓÇÆá ÇáãİÑæÁÉ [ $c_old ]",1);
			echo "<tr><td width=20% class=datacell align=center><b>áÇ íæÌÏ ÑÓÇÆá ãŞÑæÁÉ</b></td></tr>";
            $admin->closetable();
		}
            }

	}
   }
}
?>
