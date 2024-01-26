<?php
/*
+===========================================+
|      ArabPortal V2.2.x Copyright © 2009   |
|   -------------------------------------   |
|                     BY                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Info      |
|   -------------------------------------   |
|  Last Updated: 10/02/2009 Time: 03:00 AM  |
+===========================================+
*/

if (stristr($_SERVER['SCRIPT_NAME'], "admin_func.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class admin_func
{
    var $use_cookies = 0;
    var $use_pre_ip  = 0; // ·«  ” Œœ„ Â–Â «·« ›Ì Õ«·… ﬂ«‰  ⁄‰œﬂ „‘ﬂ·…  €Ì— «·«Ì»Ì
    var $expires     = 3600;
    var $expsess     = 3600;
    var $Rows        = '';
    var $ColWidth    = array();
    
//-----------------------------------------------

   	function admin_func()
	{
        global $apt;
        
        if (isset($apt->get))
        {
            while (list ($key, $val) = each ($apt->get))
            {
                $key = trim(stripslashes(strip_tags($val)));
            }
        }
        $expsess =  $apt->time - $this->expsess;
        
        $result  =  $apt->query("delete from rafia_admin_sess where sess_TIME<'$expsess'");

    }

//---------------------------------------------------

    function format_data($r)
    {
        return mysql_escape_string(stripslashes(trim($r)));
    }

//-----------------------------------------------

    function get_sess($str='')
    {
        if( $str == '' )
        {
            return "sessID=".$this->get_sessID();
        }
        else
        {
            if(strstr($str,"?"))
            {
                return $str."&sessID=".$this->get_sessID();
            }
            else
            {
                return $str."?sessID=".$this->get_sessID();
            }
        }
    }

//-----------------------------------------------

      function login()
      {
          global $apt;
          
          if (!$apt->full( $apt->post ))
          {
              $this->windowmsg(_NO_POST_INFO);//**By ANS**/
              exit;
          }

          $user_name =  $apt->format_data( $apt->post['user_name']);
          
          $user_pass =  $apt->format_data(md5( $apt->post['user_pass']));
          
          $result    = $apt->query("SELECT userid,username,password,useradmin,usergroup,allowpost from rafia_users where  (username ='$user_name') AND (password ='$user_pass') AND (useradmin = 1)");

           if (@$apt->dbnumrows($result) > 0)
           {
                 $sess       =   $apt->dbarray($result);
                 $logintime  =   $apt->time;
                 $ip         =   $apt->ip;
                 $newSid     =   substr(md5(uniqid(rand())),15);

                 foreach ($sess as $key => $value)
                 {
                     if(is_integer($key)){unset($sess[$key]);}
                 }

                 $sess_value = serialize(array($sess));

                 if($apt->conf['use_cookies'] == 1) SetCookie("sessID",$newSid,$logintime + $this->expires);
                 if($this->use_pre_ip == 1){$r_ip = explode('.',$ip); $ip = $r_ip[0].'.'.$r_ip[1].'.'.$r_ip[2];}

                 $query = "INSERT into rafia_admin_sess (sessID,sessIP,sess_TIME,sess_VALUE,sess_LOGTIME) values
                                                      ('$newSid','$ip','$logintime','$sess_value','$logintime')";

                 $result =  $apt->query($query);

                 if($result == true)
                 {
                     $refe = preg_replace("/(&sessID=).*/i", "", $apt->refe);
                     $refe = preg_replace ("/(php?|php).*/i","php?",$refe);

                     $this->bodymsg(_LOGIN_OK,"index.php?sessID=".$newSid);  //**By ANS**//
                 }
                 else
                 {
                     $this->windowmsg(_ERROR_LOGIN_ERROR);//**BY ANS**//
                 }
           }
           else
           {
                $this->windowmsg(_ERROR_USER_OR_PASS_WRONG);//**BY ANS**//

           }

     }

//-----------------------------------------------

      function get_var()
      {
          global $apt;
          
         if($this->get_sessID() == true)
         {
              $sessID  =  $this->get_sessID();
              $expsess =  $apt->time + $this->expsess;
              $ip =  $apt->ip;
              if($this->use_pre_ip == 1){$r_ip = explode('.',$ip); $ip = $r_ip[0].'.'.$r_ip[1].'.'.$r_ip[2];}
              $result  =  $apt->query("SELECT sess_VALUE from  rafia_admin_sess where  sessID='$sessID' and sessIP='$ip' and sess_TIME<'$expsess'");

              if (@$apt->dbnumrows($result) > 0)
              {
                  $sess        =  $apt->dbarray($result);
                  
                  list($array) =  @unserialize($sess['sess_VALUE']);
                  
                  return $array ;
              }
              else
              {
                  return false;
              }
          }
      }

//-----------------------------------------------
      function get_sessID()
      {
          global $apt;

          if($apt->conf['use_cookies'] == 1)
          {
             $sessID = $apt->cookies['sessID']  ? $apt->cookies['sessID'] : $apt->request['sessID'];
          }
          else
          {
             $sessID =  $apt->get['sessID'];
          }
          return $sessID;
      }

//-----------------------------------------------

     function is_login()
     {
         global $apt;
         
         $sessID  = $this->get_sessID();
         $sessIP  = $apt->ip;
         $expsess = $apt->time + $this->expsess;

         if($this->use_pre_ip == 1){$r_ip = explode('.',$sessIP); $sessIP = $r_ip[0].'.'.$r_ip[1].'.'.$r_ip[2];}

         $result  = $apt->query("SELECT sessIP from  rafia_admin_sess where  sessID='$sessID' and sessIP='$sessIP' and sess_TIME<'$expsess'");

         if ($apt->dbnumrows($result) > 0)
         {
                 $apt->query("update rafia_admin_sess set sess_TIME='".$apt->time."' where  sessID='$sessID'");
                return true;
         }
         else
         {
             return false;
         }
     }

//-----------------------------------------------
	function get_Str ($Str)
	{
		$Str = eregi_replace("[^A-Z]+","",$Str);
		return $Str;
	}

//-----------------------------------------------
     function admin_login($value)
     {
         global $apt;
         
         $sessID     = $this->get_sessID();
         $logintime  = $apt->time;
         $sessIP     = $apt->ip;
         $adinfo     = $this->get_var();
         $a_userid   = $adinfo[admin_userid];
         $a_nameid   = $adinfo[admin_userid];
         $value      = $this->format_data($value);
         
         $query = "INSERT into rafia_admin_login (adminID,adminIP,admin_NAME,admin_TIME,admin_USERID,admin_VALUE) values
                                                 ('$sessID','$sessIP','$a_nameid','$logintime','$a_userid','$value')";
         $result =  $apt->query($query);
         
         if ($result)
         {
                return true;
         }
         else
         {
             return false;
         }
     }

//-----------------------------------------------
    function logout()
    {
        global $apt;
        
        $sessID   =  $apt->get[logout];

        $result   = $apt->query("delete from rafia_admin_sess where sessID='$sessID'");

        $expires  = time()+(60*60);

        setcookie('sessID','',$expires);

        unset($this->get[sessID]);
        
        if ($result)
        {
              return true;
        }
        else
        {
            return false;
        }
    }

//-----------------------------------------------
    function myimplode($arr)
    {
        if(isset($arr))
        {
            foreach($arr as $arrid)
            {
                $arr_id .= $arrid.",";
            }
           return  substr ($arr_id,0,strlen($arr_id)-1);
        }
    }

//-----------------------------------------------
    function order()
    {
        if(isset($arr))
        {
            foreach($arr as $arrid)
            {
                $arr_id .= $arrid.",";
            }
           return  substr ($arr_id,0,strlen($arr_id)-1);
        }
    }

//---------------------------------------------------

    function pagenum(&$perpage,$link)
    {
        global $apt;
        if ($apt->numrows>"$perpage")
        {
            $pagenum = "<p><font class=fontablt>"._PAGES." : ";//**BY ANS**//
            
            if($apt->get['page'] > 2)
            {
                $pagenum .= "<a href=$this->self?$link&start=0&page=1>[1]</a> ... \n";
            }
            
            $pages = ceil($apt->numrows/$perpage);
            
            if($apt->get['page'] == 0)
            {
                $apt->get['page'] = 1;
            }

            if($apt->get['page'] > 0)
            {
                $apt->get['page'] = $apt->get['page'] - 1;
            }
            
            $maxpage =  $apt->get['page'] + 3 ;
            
            for ($i = $apt->get['page'] ; $i <= $maxpage && $i <= $pages ; $i++)
            {
                if($i > 0)
                {
                    $nextpag = $perpage*($i-1);
                    
                    if ($nextpag == $apt->get['start'])
                    {
                        $pagenum .= "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
                    }
                    else
                    {
                        $pagenum .= "<font class=fontht><a href=$this->self?$link&start=$nextpag&page=$i>[$i]</a></font>&nbsp;\n";
                    }
                }
             }
             if (! ( ($apt->get['start']/$perpage) == ($pages - 1) ) && ($pages != 1) )
             {
                 $nextpag = ($pages*$perpage)-$perpage;

                 $pagenum .= " ... <font class=fontht><a href=$this->self?$link&start=$nextpag&page=$pages>[$pages]</a></font>\n";
             }
             
             $pagenum .= "</font></p>";
          }
          return $pagenum;
      }

//---------------------------------------------------

    function delete_cat($id,$modulid,$cat)
    {
        global $apt;

        $this->head();

        $this->openform("$PHP_SELF?cat=$cat&act=delete&".$this->get_sess());

        $this->opentable(_CONFORM_DELETE);//**BY ANS**//

        $res = $apt->dbfetch("select * from rafia_cat WHERE id='$id'");
		//**BY ANS**//
        echo "<p><font color=\"#FF0000\">"._DELETE_CAT.":<font color=\"#000000\"><b> $res[title]  </b></font>
        <br>«–« ﬂ‰  „ «ﬂœ ÌÃ» ⁄·Ìﬂ «Œ Ì«— «Õœ «·«ﬁ”«„ «· «·Ì… Õ Ï Ì „ ‰ﬁ· «·„Ê«÷Ì⁄ «·ÌÂ „‰ «·ﬁ”„ «·–Ì ”Ê› ÌÕ–› ,
        <br>
        «Ê ”Ê› Ì „ Õ–› Ã„Ì⁄ «·„Ê«÷Ì⁄ «·„ÊÃÊœ… ›Ì «·ﬁ”„ «·„Õ–Ê› «–« ·„  ﬁ„ »Œ Ì«— «Õœ «·«ﬁ”«„ «· «·Ì…
        </font></p>";
         echo "<p><center><a href='javascript:history.go(-1);'><b>( ≈·€«¡ «·Õ–› )</a><br></center></p>";
        echo "<tr><td class=datacell width='33%'>Õœœ ﬁ”„ </td><td class=datacell>\n";
        echo "<select name='tocatid'>\n";
        echo "<option value='0'>"._MOVE_POSTS_TO." </option>";//**BY ANS**//

        $result = $apt->query("select * from rafia_cat WHERE  catType='$modulid' and ismine=0 and id!='$id' order by id");

        while($row=$apt->dbarray($result))
        {
            $idcat=$row["id"];
            
            $titlecat=$row["title"];
            
            echo "<option value='$idcat'>$titlecat</option>";
        }
        
        echo "</select></td></tr>\n";
        
        $this->inputhidden("delid",$id);
        
        $this->closetable();
        
        $this->submit(_DELETE);//**By ANS**//
    }

//---------------------------------------------------

     function editsetting($t,$modul='0')
     {
         global $apt;

         $result = $apt->query("select * from rafia_settings where settingtype='$t' and modulname='$modul' ORDER BY id ASC");

         while($row = $apt->dbarray($result))
         {
             @extract($row);
             switch($titletype)
             {
                 case "1":
                 if($variable =="dtimehour")
                 {
                     $this->select_time(@constant($title),$value);
                 }
                 else
                 {
                     $this->inputtext(@constant($title),$variable,$value,$options);
                 }
                 break;
                 case "2":
                 $this->input_select(@constant($title),$variable,$value,$options);
                 break;
                 case "3": $this->selectnum(@constant($title),$variable,$value,'1',$options);
                 break;
                 case "4":
                 $this->textarea(@constant($title),$variable,$value, "RTL",$options);
                 break;
                 case "5": $this->yesorno(@constant($title),$variable,$value);
                 break;
             }
         }
    }

//---------------------------------------------------

    function modulid_menu($menu,$msg,$cat)
    {
        global $apt;

        $this->opentable(_SELECT_MENUS);//**BY ANS**//

        $this->openform("$PHP_SELF?cat=$cat&act=updatemenu&".$this->get_sess());

        echo "<tr><td class=datacell valign=\"top\" width='33%'>$msg</td><td class=datacell>\n";

        $menuarr = explode(",",$menu);

        $result = $apt->query("SELECT * FROM rafia_menu WHERE menushow='1'");

        while($row = $apt->dbarray($result))
        {
            extract($row);

            if(in_array($menuid,$menuarr))
            {
                echo "<input type=\"checkbox\" name=\"menucheck[]\" value=\"$menuid\" checked id=\"\">   $title  <br>";
            }
            else
            {
                echo "<input type=\"checkbox\" name=\"menucheck[]\" value=\"$menuid\" id=\"\">   $title  <br>";
            }
        }

        echo "</td></tr>\n";

        $this->closetable();
        
        $this->submit(_UPDATE);//**BY ANS**//
    }

//---------------------------------------------------

     function group_check_box($msg,$name,$value = 0)
     {
         global $apt;
         $catgrouparr = explode(",", $value);
         echo "<tr><td class=datacell width='33%'>$msg</td><td class=datacell>\n";

         $result = $apt->query("SELECT * FROM rafia_groups  ORDER BY groupid ASC");

         while($row = $apt->dbarray($result))
         {
             extract($row);
             if(in_array($groupid,$catgrouparr))
             {
                 echo "<input type=\"checkbox\" name=\"$name\" value=\"$groupid\" checked id=\"\">   $grouptitle <br>";
             }
             else
             {
                 echo "<input type=\"checkbox\" name=\"$name\" value=\"$groupid\" id=\"\">   $grouptitle <br>";
             }
         }
         echo "</td></tr>\n";
     }

//---------------------------------------------------

     function yesorno($msg,$name,$value = 0,$id ='')
     {
           if(!$id) $id = $name;
           echo "<tr><td class=datacell width=\"33%\">$msg</td><td class=datacell width=\"72%\">";
           if( $value =='0' )
           {
               echo "<input id=\"$id\" type=\"radio\" name='$name'  value='1'  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._YES."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input id=\"$id\" type=\"radio\" name='$name'  value=\"0\" checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._NO."";//**BY ANS**//

           }else{
               echo "<input id=\"$id\" type=\"radio\" name='$name'  value='1' checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._YES."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input id=\"$id\" type=\"radio\" name='$name'  value=\"0\"  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._NO."";//**BY AMS//**
               }
           echo "</td></tr>";

     }

//---------------------------------------------------

    function TableCell($cell)
    {
        $Html = "<tr>";
        if($this->bgColor=="#dbe7f9")
        $this->bgColor="#e6f0ff";
        else
        $this->bgColor="#dbe7f9";

        for ( $i=0; $i < count($cell); $i++ )
        {
            if(!is_numeric($cell[$i]))
            $align = 'right';
            else
            $align = 'center';

            $Html .= "<td align=\"$align\" bgColor=\"$this->bgColor\" class=TableBodyNumeric>{$cell[$i]}</td>";
        }

        $Html .=  "</tr>";
        return $Html;
    }
    
    function Table($cell,$title)
    {

        $Html  .= '<table id="table1" cellSpacing="1" bgcolor="#FFFFFF" cellPadding="6" width="90%" border="0" bordercolor="#FFFFFF"  dir="rtl">';
        if(is_array($cell))
        {
            $Header = "<tr>";
            $i=0;
            foreach ($cell as $value)
            {
                $Header .= "<td background=\"images/tbg_th1.png\" width=\"{$this->ColWidth[$i]}%\" class=TableHeader><p><font color=\"#000000\"><b>{$value}</b></font></p></td>";
                $i++;
            }
            $Header .=  "</tr>";
        }
        
        $Html .= "<td noWrap background=\"images/td_back.gif\"  align=\"middle\" colSpan=\"$i\"><font size=4 color=\"#FFFFFF\"><b>$title</b></td>".$Header;
        $Html .=  $this->Rows;
        $Html .= "<td noWrap background=\"images/tbg_th1.png\"  align=\"middle\" colSpan=\"$i\"></td>";
        $Html .=  "</table></div>";
        return $Html;
    }

    function ViewHref($Link,$target="_self")
    {
       $ViewLink = "<p align=\"center\"><a target=\"$target\" href=\"$Link&".$this->get_sess()."\"><img alt=\"⁄—÷\"  border=\"0\" src=\"images/ico_view.gif\" align=\"middle\"></a>";
       return $ViewLink;
    }

    function EditHref($Link)
    {
       $DelLink = "<p align=\"center\"><a href=\"$Link&".$this->get_sess()."\"><img alt=\" Õ—Ì—\"  border=\"0\" src=\"images/ico_edit.gif\" align=\"middle\"></a>";
       return $DelLink;
    }

    function AddHref($Link)
    {
       $DelLink = "<p align=\"center\"><a href=\"$Link&".$this->get_sess()."\"><img alt=\"«÷«›…\"  border=\"0\" src=\"images/ico_create.gif\" align=\"middle\"></a>";
       return $DelLink;
    }
    function DelHref($Link)
    {
       $DelLink = "<p align=\"center\"><a href=\"$Link&".$this->get_sess()."\" onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\"><img alt=\"Õ–›\"  border=\"0\" src=\"images/ico_delete.gif\" align=\"middle\"></a>";
       return $DelLink;
    }
    
    function NewHref($Link,$images=0,$target="_self")
    {
        if(!$images)
        $images = "ico_notice.gif";
        $NewLink = "<p align=\"center\"><a target=\"$target\" href=\"$Link&".$this->get_sess()."\"><img alt=\"\"  border=\"0\" src=\"images/$images\" align=\"middle\"></a>";
        return $NewLink;
    }
    
//---------------------------------------------------

     function rightorleft($msg,$name,$value = 1)
     {
           echo "<tr><td class=datacell width=\"33%\">$msg</td><td class=datacell width=\"72%\">";
           if( $value =='2' )
           {
               echo "<input  type=\"radio\" name='$name'  value='1'  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"2\" checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._LEFT."";//**BY ANS**//

           }else{
               echo "<input  type=\"radio\" name='$name'  value='1' checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"2\"  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._LEFT."";
               }
           echo "</td></tr>";

     }

     function rightorleftormiddle($msg,$name,$value=1)
     {
           echo "<tr><td class=datacell width=\"33%\">$msg</td><td class=datacell width=\"72%\">";
           if($value==3)
           {
               echo "<input  type='radio' name='$name' value='1' style='font-family: MS Sans Serif; font-size: 8px '> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type='radio' name='$name' value='2' style='font-family: MS Sans Serif; font-size: 8px '><font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type='radio' name='$name' value='3' checked style='font-family: MS Sans Serif; font-size: 8px '><font class=fontablt>"._MIDDLE."";
           }else if($value==2)
           {
               echo "<input  type=\"radio\" name='$name' value='1' style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name' value=\"2\" checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name' value=\"3\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._MIDDLE."";

           }else{
               echo "<input  type=\"radio\" name='$name'  value='1' checked  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"2\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"3\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._MIDDLE."";
               }
           echo "</td></tr>";

     }

//---------------------------------------------------

     function select_time($msg,$value)
     {
         global $apt;//**BY ANS**//
         
        $difference = array( '-12' => -12 ,
        '-11' => -11 ,
        '-10' => -10 ,
        '-9' => -9 ,
        '-8' => -8 ,
        '-7' => -7 ,
        '-6' => -6 ,
        '-5' => -5 ,
        '-4' => -4 ,
        '-3' => -3 ,
        '-2' => -2 ,
        '-1' => -1 ,
        '0' => 0 ,
        '1' => 1 ,
        '2' => 2 ,
        '3' => 3 ,
        '4' => 4 ,
        '5' => 5 ,
        '6' => 6 ,
        '7' => 7 ,
        '8' => 8 ,
        '9' => 9 ,
        '10' => 10 ,
        '11' => 11 ,
        '12' => 12 );
        
         echo  "<tr><td class=datacell width=\"33%\"><p class=fontablt>$msg :</b></td><td class=datacell width=\"72%\">
         <select name='dtimehour'>";
         
         foreach($difference as $diffvalue)
         {
             if( $diffvalue == $value)
             echo "<option selected value='$value'>$value</option>";
             else
             echo "<option value='$diffvalue'>$diffvalue</option>";
         }

         echo "</select></td></tr>\n";
      }
      
      
     function selectstheme($themed)
     {
         global $apt;//**BY ANS**//
         echo  "<tr><td class=datacell width=\"33%\"><p class=fontablt>"._THEME.":</b></td><td class=datacell width=\"72%\"><select name='usertheme'>";
         echo "<option selected value='$themed'>$themed</option>";

         $result = $apt->query("select theme from rafia_design where theme!='$themed' and usertheme='1' order by id");

         while($row = $apt->dbarray($result))
         {
             extract($row);
             echo "<option value='$theme'>$theme</option>";
         }
         echo "</select></td></tr>\n";
      }

//---------------------------------------------------

    function add_select_arr($arr,$key)
    {
        $this->select_arr[$key] = $arr;
    }
    

    function input_select($msg,$name,&$value,$key ='1')
    {
        $this->select_arr[1] = array('yes' => _YES,'wit' =>  _APPROVE, 'no' =>  '·«');
        $this->select_arr[2] = array('yes' => _YES,'wit' =>  _APPROVE);
        $this->select_arr[3] = array('yes' => _YES, 'no' =>  '·«');
        $this->select_arr[4] = array('db'  => _IN_DATABASE, 'fil' =>  _IN_DIR);
        $this->select_arr[5] = array('h'   => _HJRI, 'm' => _CRISTINE);
        
        $this->select_arr[6] = array('timestamp'   => _ORDER_BY_TIMESTAMP,
                                      'date_time'  => _ORDER_BY_DATE_TIME,
                                      'c_comment'  => _ORDER_BY_COMMENT);
       $this->select_arr[7] = array('h'   => "√⁄·Ï «·’›Õ…", 'f' => "√”›· «·’›Õ…", 'm' => "«·ﬁ«∆„… «·Ã«‰»Ì…", 'b' => "ﬁ«·» „Õœœ");

       $this->select_arr[8] = array('cp'   => _SENDTOME_CP, 'mail' => _SENDTOME_MAIL);

       $this->select_arr[9] = array('formA'   => _FORMA, 'formB' => _FORMB);

       $this->select_arr[10] = array('yes' => ' ”ÃÌ· „›⁄· „”»ﬁ«','wit' => ' ”ÃÌ· Ê  ›⁄Ì· »«·»—Ìœ','swt' => ' ”ÃÌ· Ê  ›⁄Ì· »«·«œ«—…', 'no' =>  '«· ”ÃÌ· „€·ﬁ');
       
        echo " <tr><td class=datacell width='33%'><p class=fontablt>$msg:<Br></td><td class=datacell width='72%'><select name='$name'>";
        foreach ($this->select_arr[$key] as $k => $v) {
        printf('<option value="%s" %s>%s</option>',
                htmlspecialchars($k),
                (string)$k === $value ? 'selected' : '',
                htmlspecialchars($v));
        }
        echo "</select></td></tr>";
    }

//---------------------------------------------------

     function list_user_level($key ,$msg =_GROUP,$dntlist=5)//**BY ANS**//
     {
         global $apt;
         echo  "<tr><td class=datacell width=\"33%\"><font class=normalfont>$msg</td><td  class=datacell  width=72%>";
         echo  "<select name='usergroup'>";
		 if($dntlist !== '') $the_cond = " and groupid !='$dntlist'"; else $the_cond='';
         if($key ==0){
         $countusers = $apt->dbnumquery("rafia_users");
         echo  "<option  selected value=\"0\">ﬂ· «·„Ã„Ê⁄«  [$countusers ⁄÷Ê]</option>\n";
         }else
         {
             $result = $apt->query ("SELECT groupid,grouptitle FROM rafia_groups where groupid='$key'");
             $gr     = $apt->dbarray($result) ;
             $num = $apt->dbnumquery("rafia_users","usergroup=$key");
             echo  "<option  value=\"$gr[groupid]\">$gr[grouptitle] [ $num ⁄÷Ê]</option>\n";
         }
         
         $result = $apt->query ("SELECT groupid,grouptitle FROM rafia_groups where groupid !='$key' $the_cond ORDER BY groupid ASC");
         while( $row = $apt->dbarray($result)){
            $gpid = $row[groupid];
            $num = $apt->dbnumquery("rafia_users","usergroup=$gpid");
            echo "<option  value=\"$row[groupid]\">$row[grouptitle] [ $num ⁄÷Ê]</option>\n";
        }
        echo  "</select></td></tr>";
     }
//---------------------------------------------------
// uploadfile
//---------------------------------------------------
    function getend($name)
    {
        return end (explode (".",$name));
    }

     function uploadfile($id)//**BY ANS**//
     {
         global $apt;

          if (is_uploaded_file($apt->files["name_file"]['tmp_name']))
          {
               $filename = $apt->upload_path."/".$id.".catid";
                if(@copy($apt->files["name_file"]['tmp_name'], $filename))
                {
                    $upcat      =  "catid";
                    $uppostid   = $id;
                    $uptypes    = $apt->files["name_file"]["type"];
                    $upname     = $apt->files["name_file"]['name'];
                    $upsize     = $apt->files["name_file"]["size"];
                    $upend      = $this->getend($upname);
                    $imagealign = $apt->post['imagealign'];

                    $apt->query ("insert into rafia_upload (upcatid,
                                                            uppostid,
                                                            upuserid,
                                                            uptypes,
                                                            upname,
                                                            upsize,
                                                            upstat,
                                                            upcat,
                                                            upend,imagealign)
                                                            values
                                                           ('$upcatid',
                                                            '$uppostid',
                                                            '$upuserid',
                                                            '$uptypes',
                                                            '$upname',
                                                            '$upsize',
                                                            '1',
                                                            '$upcat',
                                                            '$upend',
                                                            '$imagealign')");

                      return true;
                }

          }
          
     }

//---------------------------------------------------

function selectCat($subcat,$id,$catType)
{
    global $apt;
    echo "<tr><td class=datacell width='33%'>"._IF_SUB."</td><td class=datacell>\n";//**BY ANS**//
    echo "<select name='subcat'>\n";
    if($catType == 2){
    if($subcat >0)
    {
        $result = $apt->query("select id,title from rafia_cat where catType='$catType' and id='$subcat'");
        $row=$apt->dbarray($result);
        echo "<option value='$row[id]'>$row[title]</option>";
    }
    }else{
    if($subcat >0)
    {
        $result = $apt->query("select id,title from rafia_cat where catType='$catType' and id='$subcat'");
        $row=$apt->dbarray($result);
        echo "<option value='$row[id]'>$row[title]</option>";
    }
    else
    {
        echo "<option value='0'>"._MAIN_CAT."</option>";//**BY ANS**//
    }
    }
    $result = $apt->query("select id,title from rafia_cat where catType='$catType' and id!='$subcat' and id!='$id' order by id");
    while($row=$apt->dbarray($result))
    {
        $idcat=$row["id"];
        $titlecat=$row["title"];
        echo "<option value='$idcat'>$titlecat</option>";
    }
    echo "</select></td></tr>\n";
}
                
                
function selectstat($msg,$name,&$value)
{//**BY ANS**//
    $arr_1 = array('yes' => _ALLOW,'wit' =>  _APPROVE);
    $arr_4 = array("$value" => "$value");
    echo "<tr><td class=datacell width='33%'><p class=fontablt>$msg:<Br></td><td class=datacell width='72%'><select name='$name'>";
    $arrmerge= array_unique(array_merge($arr_4, $arr_1));
    while($_arr = each ($arrmerge))
    {
        $values = $_arr['value'];
        $key = $_arr["key"];
        echo "<option  value=\"$key\">$values</option>\n";
    }
    echo "</select></td></tr>";
}

//---------------------------------------------------

function selectnum($msg,$name,$value,$s ='1',$e ='10')
{
    echo " <tr><td class=datacell width=\"33%\" ><p class=fontablt>$msg:</td>
           <td class=datacell width=\"72%\" >
           <select name='$name'> ";
		for ($i=$s; $i<=$e; $i++)
		{
		  if ($i==$value)
		  {
			  echo "<option value=$i selected>$i</option>";
		  }
		  else
		  {
		  	echo "<option value=$i>$i</option>";
		  }
    }
  echo "</select></td></tr>";
}

//---------------------------------------------------

    function head($J=0)
    {
	  global $CONF;
        print "<HTML DIR=RTL LANG=AR-SA>
              <head>
              <meta http-equiv=Content-Type content=text/html; charset=windows-1256>
              <meta http-equiv=Content-Language content=ar-sa>
              <META content=\"Arab Portal\" name=keywords>
              <META content=\"ArabPortal , Powered by ArabPortal\" name=description>
              <link rel=\"stylesheet\" type=\"text/css\" href=\"$CONF[class_folder]/style.css\">
              <title>"._ADMIN_CP."</title>
              <style><!--
              .sec_head    { font-family: Arial; font-size: 12pt; color: #333333; font-weight: bold;
               background-color: #EEEEEE;cursor:hand; }
               .sec_block   { font-family: Tahoma; font-size: 10pt; color: #993333; padding-right: 5; }
               -->
               </style>
               <script>
               function block_switsh(bid){
                   if (bid.style.display=='none'){
                       bid.style.display='';
                   }else{
                       bid.style.display='none';
                   }
               }
               </script>";
               
        if($J == 1)
        {
            print $this->template_header();
        }
        elseif($J != 0)
        {
           print $J;
        }
        print "</head><center><br>";
    }

//---------------------------------------------------

     function template_string()
     {//**BY ANS**//
         echo "<tr><td class=datacell width=\"33%\">
              <p class=fontablt></b></td>
              <td class=datacell width=\"72%\">
              <input name=\"string\" type=\"text\" size=15 onChange=\"n = 0;\"></font>
              <input onclick=\"javascript:findInPage(temp.string.value);\" type=\"button\" value="._DO_SEARCH.">
              <input type=\"button\" value=\""._COPY_TEMPLATE_TO_CLIPBORAD."\" name=\"copy\" onclick='javascript:copytemp()'></p>
              </td></tr>";
     }
     
//---------------------------------------------------

    function foot()
    {
        echo "</td></tr></table></center></div><BR><p align=center><font size=2>Arab Portal , Powered by <a target=_blank href=http://www.ArabPortal.info>Arab Portal</a> V2.2, Copyright© 2009 &nbsp;</font> </p></body></html>";
    }

//-----------------------------------------------

    function bodymsg($msg,$url)
    {
        header("Refresh: 1;url=" . $url . "");
        
        print "<html dir=rtl>
              <head>
              <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1256\">
              <title>—”«·…</title>
              </head>
              <body bgcolor='3A789F'>
              <br><br><br><br><center>
              <table border=\"0\" width=\"100%\" height=\"22\" cellpadding=\"9\">
              <tr>
              <td width=\"100%\" height=\"16\" bgcolor=\"#9FC1D7\">
              <p align=\"center\"><b><font size=\"4\" color=\"#FFFFFF\">$msg</font></b>
              <p align=\"center\"><b><font size=\"3\" color=\"#FFFFFF\"></font></b></td> </tr>
              </table> </center>
              </body>
              </html>";
       exit;

    }

//---------------------------------------------------

    function login_form()
    {
        $this->head();//**By ANS**//
        echo"<script language=javascript>
        if (window.name == 'admin') {
            parent.document.location = 'index.php';
            }
            </script>";

// ############### Added By Myrosy ##############
echo "<center><br />
<TABLE WIDTH=354 BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <TR>
        <TD>
            <IMG SRC=images/login_05.gif WIDTH=17 HEIGHT=115></TD>
        <TD background=images/login_04.gif>
            <p align=center>
            <IMG SRC=images/login_03.gif WIDTH=127 HEIGHT=115></TD>
        <TD>
            <IMG SRC=images/login_01.gif WIDTH=15 HEIGHT=115></TD>
    </TR>
    <TR>
        <TD bgcolor=#9FC1D7>
            &nbsp;</TD>
        <TD width=100% bgcolor=#9FC1D7>
            <form method=post action=index.php>
                <div align=center>
        <table border=0 bgcolor=#9FC1D7 style=border-collapse:collapse>
        <tr>
        <td colspan=2 align=center bgcolor=#9FC1D7 background=images/apt_23.gif>
        <b><font face=Tahoma size=2 color=#FFFFFF>"._ADMIN_CP."</font></b></td>
        </tr>
        <tr>
        <td  align=center width=100 bgcolor=#9FC1D7><font size=3 color=#FFFFFF><b>"._USERNAME."</b></font> </td>
        <td bgcolor=#9FC1D7><input type=text size=20 name=user_name value=\"\"></td>
        </tr>
        <tr>
        <td align= center width= 100 bgcolor=#9FC1D7><font size=3 color=#FFFFFF><b>"._PASSWORD."</b></font></td>
        <td bgcolor=#9FC1D7><b><input type=password size=20 name=user_pass value=\"\"></b></td>
        </tr>
        <tr>
        <td align= center colspan=2 bgcolor=#9FC1D7>
        <input type=submit value="._LOGIN."></td>
        </tr>
        </table>
        </div>
            </TD>
        <TD bgcolor=#9FC1D7>
            &nbsp;</TD>
    </TR>
    <TR>
        <TD>
            <IMG SRC=images/login_15.gif WIDTH=17 HEIGHT=27></TD>
        <TD background=images/login_14.gif>
            <p align=center><b><font face=Arial size=1 color=#81CBEA>Powered by: </font><a href=http://www.arabportal.info/>
             <font face=Arial size=1 color=#81CBEA>
            <span style=text-decoration: none>ArabPortal v2.2</span></font></a><font face=Arial size=1 color=#81CBEA>, Copyright© 2009</font></b></TD>
        <TD>
            <IMG SRC=images/login_11.gif WIDTH=15 HEIGHT=27></TD>
    </TR>
</TABLE></center>";

// ############# Myrosy End ################

        exit();
    }

//---------------------------------------------------

    function windowmsg($msg,$link='')
    {
        $this->head();
        if($link !== ''){
        echo "<meta http-equiv=\"refresh\" content=\"2;URL=$link\">";
        }
        echo "<body bgcolor='3A789F'>
        <br>
        <div align='center'>
        <center>
        <table border='0' width='80%' bgcolor='74A9CB'>
        <tr>
        <td width='100%' align='center'><br>$msg<br></td>
        </tr>
        </table>
        </center>
        </div>";
    }

//---------------------------------------------------

function opentable($msg,$colspan=3)
{
    echo "<table border=0 width=90% bgcolor=#3A789F cellspacing=0 cellpadding=10>
          <tr><td><br><div align=center><table border=0 width=100% cellspacing=1 cellpadding=3><tr>
          <td width=100% bgcolor=#3A789F colspan='$colspan' align=center  background=\"images/td_back.gif\">
          <font color=\"#FFFFFF\"><b>$msg</b></td></tr>\n";
}

//---------------------------------------------------

function opentable2()
{
    echo "<table border=\"0\" width=\"100%\">";
}

//---------------------------------------------------

function trtdhead($msg)
{
    echo "<tr><td width=\"100%\" align=\"center\" bgcolor='376371' background=\"images/td_back.gif\"><font color=\"#FFFFFF\"><b>$msg</td></tr>";
}

//---------------------------------------------------

function trtdurl($url,$msg,$target="_self")
{
    if($target !='_self') $target ="target=$target";
    
    echo "<tr><td class=datacell width=\"100%\" bgcolor='D2D4D4' align=\"right\"><a href=\"$url\"  $target>$msg</a></td></td></tr>";
}

//---------------------------------------------------

function td_url($url,$msg,$width=30)
{
    echo "<td class=datacell width=\"$width%\" bgcolor='D2D4D4' align=\"right\"><a href=\"$url\">$msg</a></td>";
}

//---------------------------------------------------

function td_msg($msg,$width="30")
{
    print"<td width=\"$width%\" class=datacell>$msg</td> "  ;
}

//---------------------------------------------------

function td_msgh($msg,$width=30)
{
    print"<td width=\"$width%\" class=datacellt  bgcolor='D3DCE3'>$msg</td> "  ;
}

//---------------------------------------------------

function closetable2(){
 echo "</table>";
}

//---------------------------------------------------

function closetable(){
 echo "</tr></td></table></div></tr></td></table><br>";
}

//---------------------------------------------------

function inputtext($msg,$name,$value,$size = '30')
{
    $arabic = '«|√|≈|»|À|Ã|Õ|Œ|œ|–|—|“|”|‘|’|÷|ÿ|Ÿ|⁄|€|›|ﬁ|ﬂ|Â|Ê|Ì|0|1|2|3|4|5|6|7|8|9';
     if (!eregi (($arabic),$value))
    {
        $dir =  "dir='ltr'";
    }
    
    echo "<tr><td class=datacell width=\"33%\"><p class=fontablt>$msg :</b></td><td class=datacell width=\"72%\"><input type=\"text\" name='$name' size='$size' $dir value=\"$value\"></td></tr>\n";
}

//---------------------------------------------------

function radioyesno($value='1'){//**BY ANS**//
echo "<tr><td class=datacell width=\"33%\"><p class=fontablt>"._USE_HTML_TAGS." :</b></td><td class=datacell width=\"72%\"><INPUT echo class=forminput TYPE=\"radio\" name=H VALUE='$value' style=\"font-family: MS Sans Serif; font-size: 10px \"> "._YES."<INPUT class=forminput TYPE=\"radio\" name=H VALUE=\"0\" checked style=\"font-family: MS Sans Serif; font-size: 10px \"> "._NO."</td></tr>\n";
}

//---------------------------------------------------

function inputhidden($H,$K){
echo "<input type=\"hidden\" name=\"$H\" value=\"$K\">";
}

//---------------------------------------------------

function textarea ($msg,$name,$value,$dir='ltr',$rows='10',$other=''){
echo "<tr><td class=datacell width=\"33%\"><p class=fontablt>$msg :</b>$other</td><td class=datacell width=\"72%\"><textarea cols=60 rows='$rows' dir='$dir' name='$name'>$value</textarea></td></tr>\n";
}

//---------------------------------------------------

function submit($value,$name='submit')
{
echo "<center><br><input type=\"submit\" name=\"$name\" value=\"$value\"><br><br></center>";
$this->closeform ();
}

//---------------------------------------------------

function openform ($value)
{
    echo "<FORM name=\"admin\" ACTION=\"$value\" METHOD=\"post\" >";
}

//---------------------------------------------------

function closeform ()
{
    echo "</FORM>";
}

//---------------------------------------------------

    function openformdata($action)
    {    ?>
         <form action="<?echo$action;?>" method=post name=rafia enctype="multipart/form-data">
         <?

    }

//---------------------------------------------------

function inputfile($msg){
    echo "<tr><td class=datacell width=\"33%\"><p class=fontablt>$msg :</b></td><td class=datacell width=\"72%\">";
    echo "<input type=\"file\" name=\"name_file\" value=\"\">";echo "</td></tr>";
}

//---------------------------------------------------
     function menushow($name,$value=0)
     {
           echo "<td width=25% class=datacell align=center>";
           if($value==1)
           {
               echo "<input  type='radio' name='$name' value='1' checked   style='font-family: MS Sans Serif; font-size: 8px '> <font class=fontablt>  ŸÂ—";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type='radio' name='$name' value='2' style='font-family: MS Sans Serif; font-size: 8px '><font class=fontablt>·« ŸÂ—";//**BY ANS**//

           }else{
               echo "<input  type=\"radio\" name='$name'  value='1' style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>  ŸÂ—";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value='2' checked  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>·« ŸÂ—";//**BY ANS**//
               }
           echo "</b></td>";
     }
     function menualign($name,$value=1)
     {
                echo "<td width=35% class=datacell align=center>";

           if($value==3)
           {
               echo "<input  type='radio' name='$name' value='1' style='font-family: MS Sans Serif; font-size: 8px '> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type='radio' name='$name' value='2' style='font-family: MS Sans Serif; font-size: 8px '><font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type='radio' name='$name' value='3' checked style='font-family: MS Sans Serif; font-size: 8px '><font class=fontablt>"._MIDDLE."";
           }else if($value==2)
           {
               echo "<input  type=\"radio\" name='$name' value='1' style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name' value=\"2\" checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name' value=\"3\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._MIDDLE."";

           }else{
               echo "<input  type=\"radio\" name='$name'  value='1' checked  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> "._RIGHT."";//**By ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"2\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._LEFT."";//**BY ANS**//
               echo "&nbsp;&nbsp;";
               echo "<input type=\"radio\" name='$name'  value=\"3\" style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt>"._MIDDLE."";
               }
           echo "</b></td>";

     }
//--------------------------------------------------
function template_header()
{
$NO_MATCH  = _NO_MATCH;
$TEMPLATE_COPYED = _TEMPLATE_COPYED;
return <<<TEMH
<script language="JavaScript">

var NS4 = (document.layers);
var IE4 = (document.all);

var win = window;
var n   = 0;

function findInPage(str) {

  var txt, i, found;

  if (str == "")
    return false;

  if (NS4) {

    if (!win.find(str))
      while(win.find(str, false, true))
        n++;
    else
      n++;

    if (n == 0)
      alert("$NO_MATCH");//**BY ANS**//
  }

  if (IE4) {
    txt = win.document.body.createTextRange();

    for (i = 0; i <= n && (found = txt.findText(str)) != false; i++) {
      txt.moveStart("character", 1);
      txt.moveEnd("textedit");
    }

    if (found) {
      txt.moveStart("character", -1);
      txt.findText(str);
      txt.select();
      txt.scrollIntoView();
      n++;
    }

    else {
      if (n > 0) {
        n = 0;
        findInPage(str);
      }

      else
        alert('$NO_MATCH');//**BY ANS**//
    }
  }

  return false;
}
function copytemp() {
	var tempval= eval('document.temp.template')
	tempval.focus()
	tempval.select()
	if (document.all){
	therange=tempval.createTextRange()
	therange.execCommand('Copy')
	alert("{$TEMPLATE_COPYED}");//**BY ANS**//
	}
}
</script>
TEMH;
}
//--------------------------------------------------
}
?>