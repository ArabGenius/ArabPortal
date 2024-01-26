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

if (stristr($_SERVER['SCRIPT_NAME'], "pages.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class pages
{
    function pages($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

	if ($action=="pages")
        {
            $admin->head();
            $result =  $apt->query("SELECT * FROM rafia_pages ORDER BY name ASC");

            $admin->opentable("«·’›Õ«  «·«÷«›Ì…",5);

            while($temp = $apt->dbarray($result))
            {
                extract($temp);
                echo "<tr><td  class=datacell width=60%><a target=_blank href=./../index.php?action=pages&id=$id><b>$name</b></a><br>—«»ÿ «·’›Õ… : index.php?action=pages&id=$id</td>
                <td width=20% class=datacell align=center><b>$count “Ì«—…</b></td>
                <td width=10% class=datacell align=center><a href=$PHP_SELF?cat=pages&act=editpage&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>
                <td width=10% class=datacell align=center><a href=$PHP_SELF?cat=pages&act=deletepage&id=$id&".$admin->get_sess().">„”Õ</b></a></td>";
            }
            $admin->closetable();
        }
        else  if ($action=="editpage")
        {
            $admin->head();
             $id = $apt->setid('id');
            $result =  $apt->query("SELECT * FROM rafia_pages WHERE id='$id'");
            $temp = $apt->dbarray($result);
            extract($temp);
		echo "
		<script language=JavaScript>
		function PostWrite(text) {
			document.editor.pagetext.value += text; 
		}
		function addnew(text) {
			text = ' ' + text + ' ';
			PostWrite(text);
		}
		</script>
		";

            echo "<form name=editor action=index.php?cat=pages&act=updatepage&id=$id&".$admin->get_sess()." METHOD=POST>\n";
            $admin->opentable(" Õ—Ì— ’›Õ…");
            $admin->inputtext("≈”„ «·’›Õ…","name",$name);
            $admin->yesorno(" ‰‘Ìÿ «·’›Õ…", 'active',$active);
            $admin->yesorno("··«⁄÷«¡ ›ﬁÿ", 'checkuser',$checkuser);
            $admin->yesorno("ŸÂÊ— «·ﬁ«∆„… «·Ì”—Ï", 'lmenu_show',$lmenu_show);
            $admin->yesorno("‰’ HTML", 'ishtml',$ishtml);
            $admin->yesorno("⁄—÷ «·⁄œ«œ ›Ì «·’›Õ…", 'showcount',$showcount);
            $admin->yesorno("⁄—÷ ›Ì ﬁ«∆„… «·’›Õ« ", 'showinmenu',$showinmenu);
            $admin->yesorno(" ’›Ì— ⁄œ«œ «·’›Õ…", 'tozero',0);
		echo "<tr><td align=center colspan='3' span=2 class=datacell>
		      <input onclick=JavaScript:addnew('[--NEW_PAGE--]') type=button value='«÷«›… „ﬁÿ⁄ ’›Õ…'>
	      	<input onclick=JavaScript:addnew('[--USER_NAME--]') type=button value='«÷«›… «”„ «·⁄÷Ê'>
		      <input onclick=JavaScript:addnew('[--TIME_NOW--]') type=button value='«÷«›… «· «—ÌŒ'>

		</td></tr>";
            $admin->textarea("ﬂÊœ «·’›Õ…","pagetext",$pagetext);
            $admin->closetable();
            $admin->submit(" ‹‹‹Õ‹—Ì‹—");
       }
       else  if ($action=="updatepage")
       {
           extract($_POST);
           $id = $apt->setid('id');
           if($ishtml == 0)$pagetext = $apt->format_post($pagetext);
           if($tozero == 1){
           $result = $apt->query("update rafia_pages set name='$name', checkuser='$checkuser',active='$active',ishtml='$ishtml',showcount='$showcount',count='0', pagetext='$pagetext',showinmenu='$showinmenu',lmenu_show='$lmenu_show' where id='$id'");
           }else{
           $result = $apt->query("update rafia_pages set name='$name', checkuser='$checkuser',active='$active',ishtml='$ishtml',showcount='$showcount', pagetext='$pagetext',showinmenu='$showinmenu',lmenu_show='$lmenu_show' where id='$id'");
           }
           if ($result)
           {
               header("Refresh: 0;url=index.php?cat=pages&act=pages&".$admin->get_sess());
               $admin->head();
               $admin->opentable("⁄—÷ «·ﬁÊ«·»");
               $admin->windowmsg("<p> „ «· ÕœÌÀ  </p>");
               $admin->closetable();
           }
       }
       else  if ($action=="addpage")
       {
           $admin->head();

          $admin->openform("$PHP_SELF?cat=pages&act=insertpage&".$admin->get_sess());
          $admin->opentable("≈÷«›… ’›Õ… ÃœÌœ…");
          $admin->inputtext("≈”„ «·’›Õ…","name",$name);
          $admin->yesorno("··«⁄÷«¡ ›ﬁÿ :", 'checkuser',0);
          $admin->yesorno("ŸÂÊ— «·ﬁ«∆„… «·Ì”—Ï", 'lmenu_show',1);
          $admin->yesorno("‰’ HTML", 'ishtml',$ishtml);
          $admin->yesorno(" ‰‘Ìÿ «·’›Õ… :", 'active',1);
          $admin->yesorno("⁄—÷ «·⁄œ«œ ›Ì «·’›Õ… :", 'showcount',1);
          $admin->yesorno("⁄—÷ ›Ì ﬁ«∆„… «·’›Õ« ", 'showinmenu',$showinmenu);
          $admin->textarea("ﬂÊœ «·’›Õ…","pagetext",$pagetext);
          $admin->closetable();
          $admin->submit("≈÷«›…");
      }
      else  if ($action=="insertpage")
      {
          extract($_POST);
          if($ishtml == 0)$pagetext = $apt->format_post($pagetext);

          $result = $apt->query("insert into rafia_pages
                                 (name,checkuser,active,pagetext,ishtml,showcount,showinmenu,lmenu_show)
                                  values
                                 ('$name','$checkuser','$active','$pagetext','$ishtml','$showcount','$showinmenu','$lmenu_show')");
          if ($result)
          {
              header("Refresh: 0;url=index.php?cat=pages&act=pages&".$admin->get_sess());

              $admin->windowmsg("<p> „  «·«÷«›… </p>");
          }
       }
       else  if ($action=="deletepage")
       {     $id = $apt->setid('id');
          $result = $apt->query("delete from rafia_pages where id='$id'");

          if ($result)
          {
              header("Refresh: 1;url=index.php?cat=pages&act=pages&".$admin->get_sess());
              $admin->windowmsg("<p> „ Õ–› «·’›Õ… </p>");
          }
       }
    }
}
?>