<?php
if (stristr($_SERVER['SCRIPT_NAME'], "checknew.php")) {
    die ("<center><h3>���� ��� ������� ��� ������</h3></center>");
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
           echo "<tr><td width=20% class=datacell align=center><b>����� ��:</b></td>
           <td width=80% class=datacell align=center><b>$fromname</b></td></tr>";
           echo "<tr><td width=20% class=datacell align=center><b>������:</b></td>
           <td width=80% class=datacell align=center><b>".$apt->Hijri($msgdate)."</b></td></tr>";
           $inf = explode("=^+^=",$message);
           echo "<tr><td width=20% class=datacell align=center><b>������ ����������:</b></td>
           <td width=80% class=datacell align=center><b><a title='���� ��� ����' href=$PHP_SELF?cat=mailus&act=reply&id=$id&".$admin->get_sess().">���� ��� ���� ��� ".$inf[0]."</a></b></td></tr>";
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
          $admin->opentable("�� ��� �������");
          echo "<tr><td width=20% class=datacell>���� :</td>
          <td width=80% class=datacell>".$inf[0]."</td></tr>";
          $admin->inputtext("����� �������","sub",'��:'."$msgtitle");
          $admin->textarea("�� �������","textmsg","\n\n\n\n\n\n ======= ������ ������� ======= \n".$inf[1],'RTL');
          $admin->inputhidden('email',$inf[0]);
          $admin->closetable();
          $admin->submit("������");

	}elseif($action=="send"){
		extract($_POST);
		$mail = new email;
            $sitetitle = $apt->getsettings("sitetitle");
            $fromail   = $apt->getsettings("sitemail");
		$mail->send($email,$sub,$textmsg,$sitetitle,$fromail,0);
		header("Refresh: 1;url=index.php?cat=mailus&".$admin->get_sess());
            $admin->head();
		$admin->windowmsg("<p>�� ����� ������� </p>");

	}elseif($action=="del"){
           $id = intval($apt->get['id']);
           $result = $apt->query("delete from rafia_messages where msgid='$id'");
           if ($result){
               header("Refresh: 1;url=index.php?cat=mailus&".$admin->get_sess());
               $admin->head();
               $admin->windowmsg("<p>�� ��� ������� </p>");
            }else{
               $admin->head();
               $admin->windowmsg("<p>��� �� ��� �������</p>");
            }
	}else{
           $admin->head();
            $result =  $apt->query("SELECT * FROM rafia_messages WHERE mailus='1' order by msgid DESC;");
            $numrows = $apt->dbnumrows($result);
            if($numrows == 0)
            {
 			$admin->windowmsg("<p>���� .. �� ���� �� �����</p>");
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
            $admin->opentable("��� ������� ������� [ $c_new ]",4);
            foreach($newmsginf as $msg)
            {
                 $msginf = @explode("|_+^|",$msg);
                 print "<tr><td  class=datacell width=40%><a href=$PHP_SELF?cat=mailus&act=read&id=$msginf[0]&".$admin->get_sess()."><b>$msginf[2]</b></a></td>
                 <td width=20% class=datacell align=center>$msginf[3]</b></td>
                 <td width=25% class=datacell align=center>".$apt->Hijri($msginf[1])."</td>
                 <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=mailus&act=del&id=$msginf[0]&".$admin->get_sess().">���</b></a></td>";
            }
            $admin->closetable();
		}else{
            $admin->opentable("��� ������� ������� [ $c_new ]",1);
			echo "<tr><td width=20% class=datacell align=center><b>�� ���� ����� �����</b></td></tr>";
            $admin->closetable();
		}

		echo "<br>";
		if(!$c_old == 0){
            $admin->opentable("��� ������� �������� [ $c_old ]",4);
            foreach($oldmsginf as $msg)
            {
                 $msginf = @explode("|_+^|",$msg);
                 print "<tr><td  class=datacell width=40%><a href=$PHP_SELF?cat=mailus&act=read&id=$msginf[0]&".$admin->get_sess()."><b>$msginf[2]</b></a></td>
                 <td width=20% class=datacell align=center>$msginf[3]</b></td>
                 <td width=25% class=datacell align=center>".$apt->Hijri($msginf[1])."</td>
                 <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=mailus&act=del&id=$msginf[0]&".$admin->get_sess().">���</b></a></td>";
            }
            $admin->closetable();
		}else{
            $admin->opentable("��� ������� �������� [ $c_old ]",1);
			echo "<tr><td width=20% class=datacell align=center><b>�� ���� ����� ������</b></td></tr>";
            $admin->closetable();
		}
            }

	}
   }
}
?>
