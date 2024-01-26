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

if (stristr($_SERVER['SCRIPT_NAME'], "template.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class template
{
    function template ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);

        if($action=="temp")
        {
            $admin->head();

            $admin->opentable("«·ﬁÊ«·»",4);
            $result = $apt->query("select theme from rafia_design ORDER BY id ASC");

            while($row = $apt->dbarray($result))
            {
                extract($row);
                $num = $apt->dbnumquery("rafia_templates","theme ='$theme'");
                echo "<tr><td  class=datacell width=55%><b>$theme - $num </b></td>
                <td width=15% class=datacell align=center><a href='$PHP_SELF?cat=template&act=view&row=$row[0]&".$admin->get_sess()."'>⁄—÷</b></a></td>
                <td width=15% class=datacell align=center><a href='$PHP_SELF?cat=template&act=search&row=$row[0]&".$admin->get_sess()."'>»ÕÀ</b></a></td>
                <td width=15% class=datacell align=center><a href='$PHP_SELF?cat=template&act=delete&row=$row[0]&".$admin->get_sess()."'>„”Õ</b></a></td>";
            }
            $admin->closetable();
        }
        else if($action=="search")
        {
            $admin->head();
		$row = $apt->get[row];
            echo "<form action=index.php?cat=template&act=dosearch&".$admin->get_sess()." METHOD=POST name=\"temp\">\n";
            $admin->opentable("»ÕÀ ›Ì ﬁÊ«·» $row");
            $admin->textarea("»ÕÀ ⁄‰","template",'','ltr',10);
            $admin->inputhidden('row',$row);
            $admin->closetable();
            $admin->submit("»‹‹Õ‹‹À");
		
        }
        else if($action=="dosearch")
        {
            $admin->head();
            $row = $apt->post['row'];
            $template = trim($apt->post['template']);
            if($template == '')
            {
 			$admin->windowmsg("<p>⁄›Ê« .. ·„ Ì „ ﬂ «»… «Ì ﬂ·„… ··»ÕÀ</p>");
			exit;
            }
            $result =  $apt->query("SELECT id,name FROM rafia_templates WHERE theme='$row' AND template like '%$template%' ORDER BY name ASC");
            $numrows = $apt->dbnumrows($result);
            if($numrows == 0)
            {
 			$admin->windowmsg("<p>·„ Ì „ «ÌÃ«œ «Ì ‰ «∆Ã ··»ÕÀ</p>");
            }else{
            $admin->opentable("⁄œœ ‰ «∆Ã «·»ÕÀ $numrows");
            while($temp = $apt->dbarray($result))
            {
                 extract($temp);
                 print "<tr><td  class=datacell width=60%><b>$name</b></td>
                 <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=template&act=edit&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>
                 <td width=20% class=datacell align=center><a href=$PHP_SELF?cat=template&act=deletetemp&id=$id&".$admin->get_sess().">„”Õ</b></a></td>";
            }
            }
            $admin->closetable();
        }
        else if($action=="view")
        {
            $admin->head();
            $row = $apt->get['row'];
            if($apt->dbnumquery("rafia_templates","theme='$row'") > 0)
            {
                $temptype_result =  $apt->query("SELECT * FROM rafia_temptype ORDER BY tempid ASC");

                while($type = $apt->dbarray($temptype_result))
                {
                    $typeid = $type[tempid];
                    $result =  $apt->query("SELECT * FROM rafia_templates WHERE theme='$row' AND temptype='$type[tempid]' ORDER BY name ASC");
                    $admin->opentable($type[temptypetitle],4);

                    while($temp = $apt->dbarray($result))
                    {
                        extract($temp);
                        
                        //$templates[$typeid][$name] = "\"".$name."\"";
                        
                        print "<tr><td  class=datacell width=55%><b>$name</b></td>
                       <td width=15% class=datacell align=center><center><a href='#' onclick=\"window.open('index.php?cat=template&act=showtemp&id=$id&".$admin->get_sess()."', '', 'HEIGHT=400, resizable=yes, WIDTH=550, screenX=313,screenY=259,left=190,top=90');return false;\">⁄‹—÷</a></center></td>
                       <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=template&act=edit&id=$id&".$admin->get_sess()."> Õ—Ì—</b></a></td>
                       <td width=15% class=datacell align=center><a href=$PHP_SELF?cat=template&act=deletetemp&id=$id&".$admin->get_sess().">„”Õ</b></a></td>";
                    }

               $admin->closetable();
                }
                
                //echo'<p align="left"><pre>';print_r($templates);echo'</pre>';
                //$value = serialize(array($templates));echo $value;exit;
            }
            else
            {
                $admin->opentable("·«ÌÊÃœ ﬁÊ«·»");
                echo "<tr><td  class=datacell width=90% align='center'><b>·«ÌÊÃœ ﬁÊ«·» ›Ì Â–«
                «· ’„Ì„ Â·  —€» ›Ì  À»Ì  «·ﬁÊ«·» «·«› —«÷Ì…ø</b>
                <br>
                <a href=$PHP_SELF?cat=template&act=install&row=$row&".$admin->get_sess()."><b> >>> À»Ì  «·ﬁÊ«·» «·«› —«÷Ì…<<< <b></a>
                <p>Ì” Õ”‰  À»Ì  «·«› —«÷Ì À„ «· ⁄œÌ· ⁄·Ì…</p>
                </td>";
                $admin->closetable();
            }
        }
        else if($action=="showtemp")
        {
            $id = $apt->setid('id');
            echo "<html dir=rtl><META http-equiv=Content-Type content=\"text/html; charset=windows-1256\">";
            $apt->theme = $apt->getsettings('theme');
            $result = $apt->query("SELECT * FROM rafia_design WHERE theme='".$apt->theme."'");
            $drow         = $apt->dbarray($result);
            eval("\$css =\"".str_replace("\"","\\\"",stripslashes($drow['style_css'])). "\";");
            echo '<style>' . $css . '</style>';

            $result =  $apt->query("SELECT name,template FROM rafia_templates WHERE id='$id'");
            $temp = $apt->dbarray($result);
            extract($temp);
            echo '<title> ⁄—÷ ﬁ«·» ' . $name . '</title>';
		//$themepath = $drow['themepath'];
		$template = str_replace('$themepath',$drow['themepath'],$template);
		$template = str_replace("themes/","./../themes/",$template);
		echo $template;

		exit();
        }
        else if($action=="edit")
        {
            $admin->head(1);
            $id = $apt->setid('id');
            $result =  $apt->query("SELECT * FROM rafia_templates WHERE id='$id'");
            $temp = $apt->dbarray($result);
            extract($temp);
            echo "<form action=index.php?cat=template&act=update&id=$id&".$admin->get_sess()." METHOD=POST name=\"temp\">\n";
            $admin->opentable("⁄—÷ «·ﬁÊ«·»");
            $admin->inputtext("≈”„ «·ﬁ«·»","name",$name);
            $admin->textarea("ﬂÊœ «·ﬁ«·»","template",htmlspecialchars($template),'ltr',18);
            $admin->template_string();
            $admin->inputhidden('H',$theme);
            $admin->closetable();
            $admin->submit(" ‹‹‹Õ‹—Ì‹—");
      }
      else if($action=="update")
      {
           extract($_POST);
          $id = $apt->setid('id');
          $template = $admin->format_data($apt->post[template]);
          $theme    = $admin->format_data($apt->post[theme]);
          $name     = $admin->format_data($apt->post[name]);
          $temptype = $admin->format_data($apt->post[temptype]);
    
          $result  = $apt->query("update rafia_templates set name='$name', template='$template' where id='$id'");

          if($apt->conf['Theme_From_File']==1)
          {
              $Cache = new Cache();
              $Cache->mkCache_update ($id);

          }
          if ($result)
          {
              header("Refresh: 1;url=index.php?cat=template&act=view&row=$H&".$admin->get_sess());
              $admin->head();
              $admin->opentable("⁄—÷ «·ﬁÊ«·»");
              $admin->windowmsg("<p> „ «· ÕœÌÀ  </p>");
              $admin->closetable();
          }
          else
          {
              $admin->windowmsg("<p>Œÿ√ «À‰«¡ «· ÕœÌÀ</p>");
          }
      }
      else if($action=="add")
      {
          $admin->head();
          $admin->opentable("«·ﬁÊ«·»");

          echo"<form action=$PHP_SELF?cat=template&act=insert&".$admin->get_sess()." method='post'><tr><td class=datacell width=\"33%\"><p class=fontablt>ÌÃ»  ÕœÌœ «·ÀÌ„ :</b></td><td class=datacell><select name='theme'><option value=''> ≈÷«›… ﬁ«·» ›Ì ø</option>";

          $result = $apt->query("select theme from rafia_design order by id");
          
          while($row = $apt->dbarray($result))
          {
              extract($row);
              
              echo "<option value='$theme'>$theme</option>";
              
          }
          
          echo"</select></td></tr> ";

          $admin->inputtext("≈”„ «·ﬁ«·»","name",'');

          echo"<tr><td class=datacell width=\"33%\"><p class=fontablt>«·„Ã„Ê⁄… :</b></td><td class=datacell><select name='temptype'>";

          $type =  $apt->query("SELECT * FROM rafia_temptype ORDER BY tempid ASC");

          while($row = $apt->dbarray($type))
          {
              extract($row);
              echo "<option value='$tempid'>$temptypetitle</option>";
          }
          echo"</select></td></tr> ";
          //$admin->selectnum("«·„Ã„Ê⁄…","temptype","",'0','10');
          $admin->textarea("ﬂÊœ «·ﬁ«·»","template",'');
          $admin->closetable();
          $admin->submit("≈÷«›…");
      }
      else if($action=="insert")
      {
          $template = $admin->format_data($apt->post[template]);
          $theme    = $admin->format_data($apt->post[theme]);
          $name     = $admin->format_data($apt->post[name]);
          $temptype = $admin->format_data($apt->post[temptype]);

          $result= $apt->query("insert into rafia_templates
                                                      (theme,name,temptype,template)
                                                      values
                                                      ('$theme','$name','$temptype','$template')");
          if ($result)
          {
              header("Refresh: 1;url=index.php?cat=template&act=view&row=$theme&".$admin->get_sess());
              $admin->head();
              $admin->windowmsg("<p> „  «·«÷«›… </p>");
          }
          else
          {
              $admin->windowmsg("<p>Œÿ√ «À‰«¡ «·«÷«›…</p>");
          }
          $admin->closetable();
      }
      else if($action=="install")
      {
          $row       = $apt->get[row];
          $themename = "arabportal.theme";
          
          $row      = $apt->get[row];
          
          $filesize  = filesize($themename);

          $filenum   = fopen($themename,"r");

          $filetemp  = fread($filenum,$filesize);

          fclose($filenum);

          $design_temp = explode("|-|",$filetemp);
      
          $exp_temp    = explode("|--|",$design_temp[1]);
      
          $count       = count($exp_temp);

          for ($i = 1; $i < $count; $i++)
          {
              $exp     = explode("<=>",$exp_temp[$i]);

              $exp[2] = $admin->format_data($exp[2]);
              $exp[2] = $admin->format_data($exp[2]);

          
              $result  = $apt->query("insert into rafia_templates
                                                            (theme,
                                                            name,
                                                            temptype,
                                                            template)
                                                            values
                                                            ('$row',
                                                            '$exp[0]',
                                                            '$exp[1]',
                                                            '$exp[2]')");
          }

          if ($result)
          {
              $admin->head();
              $admin->opentable(" „ «· À»Ì ");
              echo "<tr><td  class=datacell width=90% align='center'><b> „  À»Ì  «·ﬁÊ«·» «·«› —«÷Ì…</b>
              <br><a href=index.php?cat=template&act=view&row=$row&".$admin->get_sess()."> ⁄—÷ «·ﬁÊ«·»</a></td>";
              $admin->closetable();
          }
          else
          {
              $admin->windowmsg("<p>Œÿ√ «À‰«¡  À»Ì  «·ﬁ«·» «·«› —«÷Ì</p>");
          }
       }
       else if($action=="delete")
       {
            $row = $apt->get['row'];
           $result = $apt->query("delete from rafia_templates where theme='$row'");

           if ($result)
           {
               header("Refresh: 1;url=index.php?cat=template&act=temp&".$admin->get_sess());

               $admin->windowmsg("<p> „ Õ–› «·ﬁÊ«·» </p>");
            }
            else
            {
                $admin->windowmsg("<p>Œÿ√ ›Ì Õ–› «·ﬁÊ«·»</p>");
            }
        }
        else if($action=="deletetemp")
        {
            $id = $apt->setid('id');
            $result = $apt->query("delete from rafia_templates where id='$id'");

            if ($result)
            {
                header("Refresh: 1;url=index.php?cat=template&act=temp&".$admin->get_sess());

                $admin->windowmsg("<p> „ Õ–› «·ﬁ«·» </p>");
            }
            else
            {
                $admin->windowmsg("<p>Œÿ√ ›Ì Õ–› «·ﬁÊ«·»</p>");
            }
        }
        else if($action=="docopy")
        {
            extract($_POST);
            header("Content-disposition: filename=$ctheme.theme");
            header("Content-type: unknown/unknown");
	        header("Pragma: no-cache");
	        header("Expires: 0");
 
	        $result = $apt->query("select * from rafia_design where theme='$ctheme'");
         
	        $row    = $apt->dbarray($result);
         
            extract($row);
            
            echo "$theme<=>$usertheme<=>$themepath<=>$style_css<=>$pagehd<=>$pageft<=>$themewidth|-|";

            $result = $apt->query("SELECT id,name,temptype,template FROM rafia_templates WHERE theme='$ctheme'");
            $num    = $apt->dbnumrows($result);

            while($rows =$apt->dbarray($result))
            {
                extract($rows);
                
                $template = str_replace("\'","\"",$template);
                
                echo "|--|$name<=>$temptype<=>$template\n";
            }
            exit;
        }
        else  if ($action=="copy")
        {
            $admin->head();

            $admin->opentable("‰”Œ ﬁÊ«·»");

            echo"<tr><td class=datacell width='90%'><form action=$PHP_SELF?cat=template&act=docopy&".$admin->get_sess()." method='post'><table border='0' width='100%' cellspacing='2' cellpadding='0'>
                 <tr><td class=datacell ><select name='ctheme'><option value=''> Õœœ «·ﬁ«·» </option>";$result = $apt->query("select theme from rafia_design order by id");

            while($row = $apt->dbarray($result))
            {
                extract($row);
                echo "<option value='$theme'>$theme</option>";
            }

            echo"</select></td></tr><tr><td class=datacell><input type='submit' name='submit' value='‰‹‹‹”‹Œ'></td></tr></table></form></td></tr>";

            $admin->closetable();

            $admin->opentable("—›⁄ «·ﬁÊ«·»");

            $admin->openformdata("$PHP_SELF?cat=template&act=uploadtemp&".$admin->get_sess());

            $admin->inputtext("«”„ «· ’„Ì„","theme",'','10');

            $admin->inputfile("«Ê ﬁ„ »—›⁄  „·› «·ﬁÊ«·» „⁄ «· «ﬂœ „‰ ‰ﬁ· „Ã·œ «·’Ê— «·Œ«’… »Â–« «·ﬁ«·»");

            $admin->closetable();

            $admin->submit("≈÷‹‹‹«›…");
    
        }
        else  if ($action=="uploadtemp")
        {
            $upload = $apt->files["name_file"];
            $theme  = str_replace(' ','_',$apt->post[theme]);
            $tmp_name =  $upload["tmp_name"];

            if (is_uploaded_file($upload['tmp_name']))
            {
                $path = $apt->upload_path."/".$upload['name'];
                if(move_uploaded_file($tmp_name, $path))
                {
                    $themename = $path;
                }
            }
            else
            {
                $admin->windowmsg("·«Ì„ﬂ‰ —›⁄ «·„·›");
               exit;
            }

            $filesize = @filesize($themename);

            $filenum = @fopen($themename,"r");

            $filetemp = @fread($filenum,$filesize);

            @fclose($filenum);

            $design_temp    = explode("|-|",$filetemp);
            $exp_design     = explode("<=>",$design_temp[0]);
            $exp_design[3]  = $admin->format_data($exp_design[3]);
            $exp_design[4]  = $admin->format_data($exp_design[4]);
            $exp_design[5]  = $admin->format_data($exp_design[5]);
            $exp_design[6]  = $admin->format_data($exp_design[6]);

            if ($fp = @fopen("../html/css/".$theme.".css", "w+")){
                @fwrite($fp,stripslashes($exp_design[3]));
                @fclose($fp);
            }else{
                  $admin->windowmsg("<p>·„ Ì „ﬂ‰ «·‰Ÿ«„ „‰ «‰‘«¡ „·› ” «Ì· ›Ì «·„Ã·œ html/css/</p>
                   «ﬂœ «‰ «·„Ã·œ html/css/ ·Â «· ’—ÌÕ 777");
                  exit;
            }
            $result=$apt->query("insert into rafia_design
                                                    (theme,
                                                     usertheme,
                                                     themepath,
                                                     style_css,
                                                     pagehd,
                                                     pageft,
                                                     themewidth)
                                              values
                                                     ('$theme',
                                                     '$exp_design[1]',
                                                     '$exp_design[2]',
                                                     '$exp_design[3]',
                                                     '$exp_design[4]',
                                                     '$exp_design[5]',
                                                     '$exp_design[6]')");
            $did = $apt->insertid();

            $exp_temp  = explode("|--|",$design_temp[1 ]);
            
            $count    = count($exp_temp);

            for ($i = 1; $i < $count; $i++)
            {
                $exp     = explode("<=>",$exp_temp[$i]);

                $exp[2] = $admin->format_data($exp[2]);

                $result   = $apt->query("insert into rafia_templates (theme,name,temptype,template) values ('$theme','$exp[0]','$exp[1]','$exp[2]')");
            }

            $admin->head();
            $admin->opentable(" Õ„Ì· «·ÀÌ„");
            echo"<tr><td class=datacell width='90%'> „ —›⁄ Ê  —ﬂÌ» «·ÀÌ„ »‰Ã«Õ .. «÷€ÿ “— «·«ﬂ„«· Õ Ï Ì „ «ﬂ„«· «· ‰’Ì»</td></tr>";
            $admin->openformdata("$PHP_SELF?cat=template&act=editdesign&id=$did&".$admin->get_sess());
            $admin->closetable();
            $admin->submit  ("«ﬂ„«· «· ‰’Ì»");

	}
       else if($action=="editdesign")
       {
           $admin->head();
           $id = $apt->setid('id');
           $admin->opentable(" Õ—Ì—");
	       $result=$apt->query("select * from rafia_design where id='$id'");
	       $row=$apt->dbarray($result);
           extract($row);
           $admin->openform("$PHP_SELF?cat=template&act=updatedesign&id=$id&".$admin->get_sess());
           $admin->inputtext("«”„ «· ’„Ì„","theme",$theme,'10');
           $admin->yesorno("«·”„«Õ ··«⁄÷«¡ »” Œœ«„…", 'usertheme',$usertheme);
           $admin->inputtext("„ﬁ«” ⁄—÷ «·’›Õ…","themewidth",$themewidth,'10');
           $admin->inputtext("„”«— „Ã·œ «·’Ê— ·ÀÌ„","themepath",$themepath,'30');
           $admin->closetable();
           $admin->opentable("Style Sheet");
           $admin->textarea(" «··€… «·‰„ÿÌ… CSS ","style_css",$style_css,'ltr',18);
           $admin->closetable();
           $admin->opentable("—«” «·’›Õ…");
           $admin->textarea("√⁄·Ï «·’›Õ…","pagehd",$pagehd);
           $admin->closetable();
           $admin->opentable("–Ì· «·’›Õ…");
           $admin->textarea("√”›· «·’›Õ…","pageft",$pageft);
           $admin->closetable();
           $admin->submit(" ‹Õ‹‹—Ì‹—");
       }
       else if($action=="adddesign")
       {
           $admin->head();
           $admin->openform("$PHP_SELF?cat=template&act=insertdesign&".$admin->get_sess());
           $admin->opentable("≈÷«›…");
           $admin->inputtext("«”„ «· ’„Ì„","theme",'','10');
           $admin->yesorno("«·”„«Õ ··«⁄÷«¡ »” Œœ«„…", 'usertheme',0);
           $admin->inputtext("„”«— „Ã·œ «·’Ê— ·ÀÌ„","themepath",'','30');
           $admin->closetable();
           
           $admin->opentable("Style Sheet");
           $admin->textarea(" «··€… «·‰„ÿÌ… CSS ","style_css",$style_css,'ltr',18);
           $admin->closetable();
           
           $admin->opentable("—«” «·’›Õ…");
           $admin->textarea("√⁄·Ï «·’›Õ…","pagehd",'');
           $admin->closetable();

           $admin->opentable("–Ì· «·’›Õ…");
           $admin->textarea("√”›· «·’›Õ…","pageft",$pageft);
           $admin->closetable();
           $admin->submit("≈÷‹‹‹«›…");
       }
       else if($action=="insertdesign")
       {
           extract($_POST);
		$theme  = str_replace(' ','_',$theme);
           if (!$apt->full($theme))
           {
               $admin->windowmsg("«·—Ã«¡ ﬂ «»… «”„ «· ’„Ì„ ");
               exit;
           }
           
           if(!file_exists("../html/css"))
           {
                 if(!mkdir("../html/css", 0755))
                 {
                     echo "·«Ì„ﬂ‰ «‰‘«¡ „Ã·œ »«”„ css";
                     exit;
                 }
             }
            if ($fp = @fopen("../html/css/".$theme.".css", "w+"))
            {
                fwrite($fp,stripslashes($_POST[style_css]));
                fclose($fp);
            }
            
           $style_css  = $admin->format_data($_POST[style_css]);
           $pagehd     = $admin->format_data($_POST[pagehd]);
           $pageft     = $admin->format_data($_POST[pageft]);
           
           $result = $apt->query("insert into rafia_design (theme,
                                                     usertheme,
                                                     themepath,
                                                     style_css,
                                                     pagehd,
                                                     pageft)
                                              values
                                                     ('$theme',
                                                     '$usertheme',
                                                     '$themepath',
                                                     '$style_css',
                                                     '$pagehd',
                                                     '$pageft')");

                  if ($result)
                  {
                      $admin->windowmsg("<p> „ ≈÷«›… «· ’„Ì„ »‰Ã«Õ</p>");
			      }
         }
       else if($action=="updatedesign")
       {
           extract($_POST);
           $id = $apt->setid('id');

           if (!$apt->full($theme))
           {
               $admin->windowmsg("«·—Ã«¡ ﬂ «»… «”„ «· ’„Ì„ ");
               exit;
           }
           
             if(!file_exists("../html/css"))
             {
                 if(!mkdir("../html/css", 0755))
                 {
                     echo "·«Ì„ﬂ‰ «‰‘«¡ „Ã·œ";
                     exit;
                 }
             }
            if ($fp = @fopen("../html/css/".$theme.".css", "w+"))
            {
                fwrite($fp,stripslashes($_POST[style_css]));
                fclose($fp);
            }
           $theme  = str_replace(' ','_',$theme);
           $style_css  = $admin->format_data($_POST[style_css]);
           $pagehd  = $admin->format_data($_POST[pagehd]);
           $pageft  = $admin->format_data($_POST[pageft]);
           
           $result=$apt->query("update rafia_design set theme='$theme',
                                                usertheme='$usertheme',
                                                themewidth='$themewidth',
                                                themepath='$themepath',
                                                style_css='$style_css',
                                                pagehd='$pagehd',
                                                pageft='$pageft'
                                                where id='$id'");
              if ($result)
              {
                  $id =  $apt->setid('id');
                  header("Refresh: 1;url=index.php?cat=template&act=editdesign&id=$id&".$admin->get_sess());
                  $admin->windowmsg("<p>&nbsp; „  ÕœÌÀ «· ’„Ì„ </p>");
              }
         }
         else if($action=="deletedesign")
         {
             if(isset($apt->get['id']))
             {
                 $id = $apt->get['id'];
                 $res = $apt->dbfetch("select * from rafia_design WHERE id='$id'");

                 $admin->head();
                 $admin->openform("$PHP_SELF?cat=template&act=deletedesign&".$admin->get_sess());
                 $admin->opentable("Õ–›  ’„Ì„");
                 print "<tr><td class=datacell>Â·  —€» »Õ–› Â–« «· ’„Ì„ : ".$res[theme];
                 print "<p><center><a href='javascript:history.go(-1);'><b>( ≈·€«¡ «·Õ–› )</a><br></center></p></td></tr>";
                 $admin->inputhidden("delid",$id);
                 $admin->inputhidden("theme",$res[theme]);
                 $admin->closetable();
                 $admin->submit  ("« „«„ «·Õ–›");
            }
            else
            {
                if(isset($apt->post['theme']))
                {
                    $theme   = $apt->post['theme'];
                    $delid   = $apt->post['delid'];
			  $ctheme = $apt->getsettings('theme');
                    if($ctheme == $theme){
                    $admin->windowmsg("<p>⁄›Ê« ... Â–« «· ’„Ì„ ÂÊ «· ’„Ì„ «·«› —«÷Ì<br>«–« «—œ  Õ–›Â ›ﬁ„ » €ÌÌ— «· ’„Ì„ «·«› —«÷Ì „‰ «·«⁄œ«œ« </p>");
			  exit;}
                    $resultt = $apt->query("delete from rafia_templates where theme='$theme'");
            
                    if ($resultt)
                    {
                        $result = $apt->query("delete from rafia_design where id=$delid");
                    }
                }
                if ($result)
                {
                    $admin->windowmsg("<p> „ Õ–› «· ’„Ì„ </p>");
                }
                else
                {
                    $admin->windowmsg("<p>Œÿ√ ›Ì Õ–› «· ’„Ì„</p>");
                }
            }
        }
        else if($action=="design")
        {
             $admin->head();
    
             $result = $apt->query("select theme,id from rafia_design ORDER BY id ASC");
    
             $admin->opentable("«· ’«„Ì„");
    
            while($row=$apt->dbarray($result))
            {
                extract($row);

                echo "<tr><td width=57% class=datacell><b>$theme </b></td>
                <td width=10% class=datacell align=center ><a href=$PHP_SELF?cat=template&act=editdesign&id=$id&".$admin->get_sess()."><b> Õ—Ì—</b></a></td> ";
                if ($id <>1)
                {
                    echo "<td width=10% class=datacell align=center><a href=$PHP_SELF?cat=template&act=deletedesign&id=$id&".$admin->get_sess()."><b>„”Õ</b></a></td>";
                }
            }

            $admin->closetable();
        }
    }
}
?>