<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright © 2006   |
|   -------------------------------------   |
|            By Rafia AL-Otibi              |
|                    And                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Net       |
|   -------------------------------------   |
|  Last Updated: 24/10/2006 Time: 12:00 PM  |
+===========================================+
*/

if (stristr($_SERVER['SCRIPT_NAME'], "menu.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class menu
{
     function filemenu_list()
     {
         global $apt;
         echo  "<tr><td class=datacell width=\"33%\"><p class=fontablt>„·› «·»·Êﬂ «·„” Œœ„ ›Ì Â–Â «·ﬁ«∆„… :
         <br>«Ê «” Œœ„ „Õ ÊÏ «·ﬁ«∆„…</b></td><td class=datacell width=\"72%\"><select name='filemenu'>";
         echo "<option selected value=''></option>";
         $open = @opendir("./../block");
         while($file = @readdir($open)){
         if (($file != ".") && ($file != "..") && (is_file("./../block/$file")))
         {
         $ext = strchr($file,'.');
         if($ext == '.php'){
         $file = str_replace(".php", "" , $file);
         $file = preg_replace("/[^a-zA-Z0-9\-\_]/", "" , $file);
         echo "<option value='$file'>$file</option>";
         }}}
         echo "</select></td></tr>\n";
      }

     function blockmenu_list($block)
     {
         global $apt;
         echo  "<tr><td class=datacell width=\"33%\"><p class=fontablt>«·ﬁ«·» «·„” Œœ„ ›Ì Â–Â «·ﬁ«∆„… :
         ÌÃ» «‰ ÌÊÃœ Â– «·ﬁ«·» ›Ì Ã„Ì⁄ «·ÀÌ„«  «·„” Œœ„…</b></td><td class=datacell width=\"72%\"><select name='blockmenu'>";
         echo "<option selected value='$block'>$block</option>";

                $theme = $apt->getsettings('theme');

        $result = $apt->query("SELECT name FROM rafia_templates
                                  WHERE temptype='7' and theme='$theme'
                                  and name!='$block'");
                                  
         while($row = $apt->dbarray($result))
         {
             extract($row);
             echo "<option value='$name'>$name</option>";
         }
         echo "</select></td></tr>\n";
      }
      
    function menu ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);
       
        if ($action=="")
        {
            $admin->head();
            $admin->openform("$PHP_SELF?cat=menu&act=updateall&id=$id&".$admin->get_sess());

            $result = $apt->query("SELECT * FROM rafia_menu ORDER BY menuorder,menualign ASC");
            while($row = $apt->dbarray($result)){
		$menuid = $row["menuid"]; $menualign = $row["menualign"];
		$allmenu[$menualign][$menuid] = $row;
		}
            $admin->opentable("«·ﬁÊ«∆„",6);
            print "<tr> ";

            $admin->td_msgh("«·ﬁ«∆„…",4);
            $admin->td_msgh("«· — Ì»",10);
            $admin->td_msgh("<center>«·ŸÂÊ—</center>",10); 
            $admin->td_msgh("<center>«·« Ã«Â</center>",10); 
            $admin->td_msgh("«· Õ—Ì—",10);
            $admin->td_msgh("«·„”Õ",10);
	      print "</tr>";

		echo '<tr bgcolor><td class="datacell" bgcolor="D2D4D4" width="120%" background="images/td_back.gif" colspan="6">
			<p align="center"><b>ﬁÊ«∆„ «·Ì„Ì‰</b></td></tr>';
		if(count($allmenu[1])>0){
            foreach($allmenu[1] as $id=>$row){
                	$co = $row["menuid"];
                		echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% > <input type=hidden name=menuid[$co] value=" . $row["menuid"] . "></b></td>";
                	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=35% > " . $row["title"] . "</b></td>";
                		echo "<td width=5% class=datacell align=center ><input name=or[$co] size='1' value=" . $row["menuorder"] . "></b></td>";
			$menushow=$admin->menushow('menushow['.$co.']',$row["menushow"]);
			$menualign =$admin->menualign('menualign['.$co.']',$row["menualign"]);
              	    echo "<td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=menu&act=edit&id=" . $row["menuid"]. "&".$admin->get_sess()."\"><img border=0 src=images/ico_edit.gif /></a></td>";
                  echo "<td width=10% class=datacell align=center><a href=" . $PHP_SELF . "?cat=menu&act=delete&id=" . $row["menuid"] . "&".$admin->get_sess()." onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\"><img border=0 src=images/ico_delete.gif /></a></td></tr>";
                 	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% ></b></td>";
            }
		}
		echo '<tr bgcolor><td class="datacell" bgcolor="D2D4D4" width="120%" background="images/td_back.gif" colspan="6">
			<p align="center"><b>ﬁÊ«∆„ «·Ê”ÿ</b></td></tr>';

		if(count($allmenu[3])>0){
            foreach($allmenu[3] as $id=>$row){
                	$co = $row["menuid"];
                		echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% > <input type=hidden name=menuid[$co] value=" . $row["menuid"] . "></b></td>";
                	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=35% > " . $row["title"] . "</b></td>";
                		echo "<td width=5% class=datacell align=center ><input name=or[$co] size='1' value=" . $row["menuorder"] . "></b></td>";
			$menushow=$admin->menushow('menushow['.$co.']',$row["menushow"]);
			$menualign =$admin->menualign('menualign['.$co.']',$row["menualign"]);
              	    echo "<td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=menu&act=edit&id=" . $row["menuid"]. "&".$admin->get_sess()."\"><img border=0 src=images/ico_edit.gif /></a></td>";
                  echo "<td width=10% class=datacell align=center><a href=" . $PHP_SELF . "?cat=menu&act=delete&id=" . $row["menuid"] . "&".$admin->get_sess()." onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\"><img border=0 src=images/ico_delete.gif /></a></td></tr>";
                 	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% ></b></td>";
            }
            }
		echo '<tr bgcolor><td class="datacell" bgcolor="D2D4D4" width="120%" background="images/td_back.gif" colspan="6">
			<p align="center"><b>ﬁÊ«∆„ «·Ì”«—</b></td></tr>';

		if(count($allmenu[2])>0){
            foreach($allmenu[2] as $id=>$row){
                	$co = $row["menuid"];
                		echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% > <input type=hidden name=menuid[$co] value=" . $row["menuid"] . "></b></td>";
                	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=35% > " . $row["title"] . "</b></td>";
                		echo "<td width=5% class=datacell align=center ><input name=or[$co] size='1' value=" . $row["menuorder"] . "></b></td>";
			$menushow=$admin->menushow('menushow['.$co.']',$row["menushow"]);
			$menualign =$admin->menualign('menualign['.$co.']',$row["menualign"]);
              	    echo "<td width=10% class=datacell align=center><a href=\"" . $PHP_SELF . "?cat=menu&act=edit&id=" . $row["menuid"]. "&".$admin->get_sess()."\"><img border=0 src=images/ico_edit.gif /></a></td>";
                  echo "<td width=10% class=datacell align=center><a href=" . $PHP_SELF . "?cat=menu&act=delete&id=" . $row["menuid"] . "&".$admin->get_sess()." onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\"><img border=0 src=images/ico_delete.gif /></a></td></tr>";
                 	echo "<tr bgcolor= $color ><td class=datacell bgcolor=D2D4D4 width=0% ></b></td>";
            }
            }

            $admin->closetable();
            $admin->submit(" ‹‹Õ—Ì‹—");


        }

        else if ($action=="add")
        {
            $admin->head();
            $admin->opentable("≈÷«›… ﬁ«∆„…");
            $admin->openform("$PHP_SELF?cat=menu&act=insert&".$admin->get_sess());
            $admin->inputtext("«”„ «·ﬁ«∆„…","title","");
            $this->blockmenu_list('blockmenu');
            $admin->selectnum(" — Ì»","menuorder","",'0','100');
            $admin->rightorleftormiddle(" ﬁ⁄ ›Ì «·Ã«‰» ø", 'menualign',1);
            $admin->textarea(" —«∆” «·ﬁ«∆„… .  ” ÿÌ⁄ «” Œœ«„ html","menuhead","",'5','5');
            $this->filemenu_list();
            $admin->textarea("„Õ ÊÏ «·ﬁ«∆„… html","menucenter", "",'5','12');
            $admin->yesorno("«ŸÂ«— «·ﬁ«∆„… ø", 'menushow',1);
            $admin->yesorno("≈ŸÂ«— «·ﬁ«∆„… ··«⁄÷«¡ «·„”Ã·Ì‰ œŒÊ· ›ﬁÿ", 'checkuser',0);
            $admin->closetable();
            $admin->submit("«÷›");

        }
        else if($action=="insert")
        {

            extract($_POST);
    
            if (!$apt->full($title))
            {
              $admin->windowmsg("·„  ﬁ„ »ﬂ «»… «”„ «· ’‰Ì›");
               exit;
            }
    
            if (!$apt->full($menutitle))
            {
                $menutitle = 'mainmenu';
            }
    
            $title       = $admin->format_data($apt->post[title]);
            $menuhead    = $admin->format_data($apt->post[menuhead]);
            $filemenu    = $admin->format_data($apt->post[filemenu]);
		if($filemenu !== ''){
            $menucenter  = '<!--INC dir="block" file="'.$filemenu.'.php" -->';
		}else{
            $menucenter  = $admin->format_data($apt->post[menucenter]);
		}
            $menushow    = $admin->format_data($apt->post[menushow]);
            $checkuser   = $admin->format_data($apt->post[checkuser]);
            $blockmenu   = $admin->format_data($apt->post[blockmenu]);
            $result = $apt->query("insert into rafia_menu
                                   (title,blockmenu,menutitle,menuhead,menucenter,
                                    menualign,menushow,menuorder,checkuser)
                                    values
                                   ('$title','$blockmenu','$menutitle','$menuhead','$menucenter',
                                    '$menualign','$menushow','$menuorder','$checkuser')");
                               
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=menu&".$admin->get_sess());
                $admin->windowmsg("<p> „  «·«÷«›… »‰Ã«Õ</p>");
            }
        }
        else if($action=="edit")
        {
            $admin->head();
            $id     = $apt->setid('id');
            $result = $apt->query("select * from rafia_menu where menuid=$id");
            $row    = $apt->dbarray($result);
            extract($row);
            $admin->opentable(" Õ—Ì— ﬁ«∆„…");
            $admin->openform("$PHP_SELF?cat=menu&act=update&id=$menuid&".$admin->get_sess());
            $admin->inputtext("«”„ «·ﬁ«∆„…","title",stripslashes($title));
            $this->blockmenu_list($blockmenu);
            $admin->selectnum(" — Ì»","menuorder",$menuorder,'0','100');
            $admin->rightorleftormiddle(" ﬁ⁄ ›Ì «·Ã«‰» ø", 'menualign',$menualign);
            if($menuid != "13")$admin->textarea(" —√” «·ﬁ«∆„… .  ” ÿÌ⁄ «” Œœ«„ html","menuhead",htmlspecialchars($menuhead),'5','5');
            if($menuid != "13")$this->filemenu_list();
            if($menuid != "13")$admin->textarea("„Õ ÊÏ «·ﬁ«∆„… html","menucenter", htmlspecialchars($menucenter),'5','12');
            
            $admin->yesorno("«ŸÂ«— «·ﬁ«∆„… ø", 'menushow',$menushow);

            if($menuid != "4" and $menuid != "5")
            {
                $admin->yesorno("≈ŸÂ«— «·ﬁ«∆„… ··«⁄÷«¡ «·„”Ã·Ì‰ œŒÊ· ›ﬁÿ", 'checkuser',$checkuser);
            }

            $admin->closetable();
            $admin->submit(" ‹‹Õ—Ì‹—");
        }
        else if($action=="update")
        {
            $id = $apt->setid('id');
            
            extract($_POST);

            $title       = $admin->format_data($apt->post[title]);
            $menuhead    = $admin->format_data($apt->post[menuhead]);
            $filemenu    = $admin->format_data($apt->post[filemenu]);
		if($filemenu !== ''){
            $menucenter  = '<!--INC dir="block" file="'.$filemenu.'.php" -->';
		}else{
            $menucenter  = $admin->format_data($apt->post[menucenter]);
		}
            $menushow    = $admin->format_data($apt->post[menushow]);
            $checkuser   = $admin->format_data($apt->post[checkuser]);
            $blockmenu   = $admin->format_data($apt->post[blockmenu]);
            if($id=='5') $checkuser   = '1';
            if($id=='4') $checkuser   = '2';
            if($id=='8') $checkuser   = '2';

            $result=$apt->query("update rafia_menu set title='$title',
                                                         blockmenu='$blockmenu',
                                                         menuorder='$menuorder',
                                                         menualign='$menualign',
                                                         menuhead='$menuhead',
                                                         menucenter='$menucenter',
                                                         menushow='$menushow',
                                                         checkuser='$checkuser'
                                                         where menuid=$id");
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=menu&".$admin->get_sess());
                $admin->windowmsg("<p> „ «· ÕœÌÀ</p>");
            }
        }
        ////////////////////
       else if($action=="updateall"){
       extract($_POST);
       foreach($menuid as $menuid){
       $result=$apt->query("update rafia_menu set menuorder='$or[$menuid]',
                                                  menualign='$menualign[$menuid]',
                                                  menushow='$menushow[$menuid]'
                                                  where menuid=$menuid limit 1;");
       }
            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=menu&".$admin->get_sess());
                $admin->windowmsg("<p> „ «· ÕœÌÀ</p>");
            }
        }
//////////////////////
        else if($action=="delete")
        {
            $id = $apt->setid('id');
            $result=$apt->query("delete from rafia_menu where menuid=$id");

           if ($result)
           {
               header("Refresh: 1;url=index.php?cat=menu&".$admin->get_sess());
              $admin->windowmsg("<p> „  ⁄„·Ì… «·Õ–› </p>");
           }
       }
       //---------------------------------------------------
//
//---------------------------------------------------
        else if($action=="updatemenu")
        {
            extract($_POST);

            $value =  $admin->myimplode($menucheck);

            $result = $apt->query("update rafia_settings set value='$value' WHERE variable='index_menuid'");

            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=menu&act=indexmenu&".$admin->get_sess());
                $admin->windowmsg(" „ «· ⁄œÌ· »‰Ã«Õ");
            }
        }

//---------------------------------------------------
//
//---------------------------------------------------
        elseif ($action=="indexmenu")
        {
            $admin->head();

            $admin->modulid_menu($apt->getsettings("index_menuid"),"«Œ — «·ﬁÊ«∆„ «· Ì  —€» «‰  ŸÂ— ›Ì «·«ﬁ”«„ «·⁄«„… , €Ì— «·»—«„Ã «·—∆Ì”Ì…",'menu');
        }
   }
}
?>
