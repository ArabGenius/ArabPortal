<?php
/*
+===========================================+
|      ArabPortal V2.2.x Copyright © 2008   |
|   -------------------------------------   |
|                     BY                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Info      |
|   -------------------------------------   |
|  Last Updated: 25/08/2008 Time: 03:35 AM  |
+===========================================+
*/


define('IN_MENU', true);

class menu
{
    var $menuhead    = '';
    var $menucenter  = '';
    var $menuid      = '';
    var $blockmenu   = '';


    function menu()
    {
        // NULL
    }

    function _menu($k)
    {
         global $apt;

        $query = " SELECT * FROM rafia_menu WHERE menushow = '1' and menualign = '$k'";
        
        $id = $this->menuid;
        
        if((isset($id)) && ($id != ''))
        {
            $query .= " and menuid in($id)";
        }
        
        $query .= " ORDER BY menuorder ASC";
        
        $result = $apt->query($query);

        while($row = $apt->dbarray($result))
        {
            @extract($row);
            
            if($blockmenu != NULL)
            {
                $this->blockmenu  = $blockmenu;
            } else {
                $this->blockmenu  = 'blockmenu';
            }
                                               
			if($menutitle == 'mainmenu')
            {
				$menucenter = $apt->replace_callback($menucenter);       	
            }
            
            $this->menuhead   = str_replace("\"","\\\"",stripslashes($menuhead));

            $this->menucenter = str_replace("\"","\\\"",stripslashes($menucenter));

            if(($checkuser == 1) && ($apt->cookie['cid'] != $apt->Guestid))
            {
                $_menu .= $this->$menutitle();
            }
            elseif(($checkuser == 2) &&  ($apt->cookie['cid'] == $apt->Guestid))
            {
                $_menu .= $this->$menutitle();
            }
            elseif($checkuser == 0)
            {
                $_menu .= $this->$menutitle();
            }
        }
        
        return $_menu;
    }

    function blockFromOut($contents)
    {
    		// $contents = preg_replace_callback("/\[getFile DIR=\"(.+)\"\]/sUi",
			// 'getFile', $contents);
			$contents = preg_replace_callback("/\[getFile DIR=\"(.+)\"\](.+)\[\/getFile\]/sUi",
			 'get_File', $contents);
        return $contents;
    }
    

	    
    function download_menu()
    {
        global $apt;
        
        $result = $apt->query("SELECT id,title FROM rafia_download WHERE allow='yes' ORDER BY clicks DESC LIMIT 5");

        if ($apt->dbnumrows($result) > 0)
        {
            while ( $row = $apt->dbarray($result) )
            {
                @extract($row);
                eval("\$s_menu .= \" " . $this->menucenter  . "\";");
            }
            eval("\$h_menu  =\" " .  $this->menuhead  . "\";");
            return $this->getmenu($h_menu,$s_menu) ;
        }
    }

    function new_download_menu()
    {
        global $apt;

        $result = $apt->query("SELECT id,title FROM rafia_download WHERE allow='yes' ORDER BY id DESC LIMIT 5");

        if ($apt->dbnumrows($result) > 0)
        {
            while ( $row = $apt->dbarray($result) )
            {
                @extract($row);
                eval("\$s_menu .= \" " . $this->menucenter  . "\";");
            }
            eval("\$h_menu  =\" " .  $this->menuhead  . "\";");
            return $this->getmenu($h_menu,$s_menu) ;
        }
    }

    function mainmenu()
    {
        global $apt;
        eval("\$s_menu .= \" " . $this->menucenter  . "\";");
        eval("\$h_menu  =\" " .  $this->menuhead  . "\";");
        return $this->getmenu($h_menu,$s_menu) ;
    }



    function modules()
    {
    global $apt;
	$menu_mods = '';
	if(($apt->cookie['cgroup'] == "1") or ($apt->cookie['cgroup'] == "2")){
		$result = $apt->query("SELECT id,mod_title,mod_sys,mod_name FROM rafia_mods ORDER BY mod_name ASC");
		while($mod = $apt->dbarray($result)){
  		    	extract($mod);
			if($apt->getsettings('html_links')=='yes'){
			$mod_link = "mod_".$mod_name.".html";
    			}else{
    			$mod_link = "mod.php?mod=".$mod_name;
	    		}
			if($mod_sys==0){
  		    	  $menu_mods .= "<li><a href=$mod_link><font color=red>* $mod_title</font></a></li>";
			}else{
  		    	  $menu_mods .= "<li><a href=$mod_link> $mod_title</a></li>";
			}
		}
	}elseif($apt->cookie['cgroup'] == "5"){
		$result = $apt->query("SELECT id,mod_title,mod_name FROM rafia_mods WHERE mod_sys!='0' and mod_user='0' ORDER BY mod_name ASC");
		while($mod = $apt->dbarray($result)){
  		    	extract($mod);
			if($apt->getsettings('html_links')=='yes'){
			$mod_link = "mod_".$mod_name.".html";
    			}else{
    			$mod_link = "mod.php?mod=".$mod_name;
	    		}
  		    	$menu_mods .= "<li><a href=$mod_link> $mod_title</a></li>";
		}
	}else{
		$result = $apt->query("SELECT id,mod_title,mod_name FROM rafia_mods WHERE mod_sys!='0' ORDER BY mod_name ASC");
		while($mod = $apt->dbarray($result)){
  		    	extract($mod);
			if($apt->getsettings('html_links')=='yes'){
			$mod_link = "mod_".$mod_name.".html";
    			}else{
    			$mod_link = "mod.php?mod=".$mod_name;
	    		}

  		    	$menu_mods .= "<li><a href=$mod_link> $mod_title</a></li>";
		}
	}


     eval("\$h_mods_menu =\" " . $this->menuhead. "\";");
     $mods_menu  = $this->getmenu($h_mods_menu,$menu_mods) ;

    return $mods_menu ;
    }


    function menu_ads()
    {
    global $apt;
            $menu_ads = "<center>".$apt->ads_view_in('m')."</center>";
            eval("\$h_ads_menu =\" " . $this->menuhead. "\";");

            $ads_menu  = $this->getmenu($h_ads_menu,$menu_ads) ;

    return $ads_menu ;
    }


    function pages()
    {
    global $apt;
	$menu_page = '';
	if($apt->cookie['cgroup'] == "5"){
			$result = $apt->query("SELECT id,name FROM rafia_pages where showinmenu='1' and active='1' and checkuser='0' ORDER BY id DESC");
		while($page = $apt->dbarray($result)){
  		    	extract($page);
			if($apt->getsettings('html_links')=='yes'){
			$page_link = "page_".$id.".html";
    			}else{
    			$page_link = "index.php?action=pages&id=".$id;
	    		}
  		    	$menu_page .= "<li><a href=$page_link> $name</a></li>";
		}
	}else{
		$result = $apt->query("SELECT id,name FROM rafia_pages where showinmenu='1' and active='1' ORDER BY id DESC");
		while($page = $apt->dbarray($result)){
  		    	extract($page);
			if($apt->getsettings('html_links')=='yes'){
			$page_link = "page_".$id.".html";
    			}else{
    			$page_link = "index.php?action=pages&id=".$id;
	    		}
  		    	$menu_page .= "<li><a href=$page_link> $name</a></li>";
		}
	}

		if($menu_page == '') $menu_page='·«  ÊÃœ ’›Õ«  «÷«›Ì…';
            eval("\$h_pages_menu =\" " . $this->menuhead. "\";");
            $pages_menu  = $this->getmenu($h_pages_menu,$menu_page) ;

    return $pages_menu ;
    }


    function archive_menu ()
    {
        global $apt;

        $showmaxr = $apt->getsettings("showmaxr");

        $result = $apt->query("SELECT id,title FROM rafia_news
                                          WHERE allow='yes'
                                          AND inmenu ='1'
                                          ORDER BY id DESC
                                          LIMIT $showmaxr");
        $reslist = $apt->dbnumrows($result);

        if($reslist > 0)
        {
            while($menu = $apt->dbarray($result))
            {
                $menlisttitle = $apt->format_data_out($menu["title"]);
                $menlistid    = $menu["id"];

                eval("\$s_archive_menu .= \"" . $this->menucenter . "\";");
            }

            eval("\$h_archive_menu =\" " . $this->menuhead. "\";");

            $archive_menu  = $this->getmenu($h_archive_menu,$s_archive_menu) ;
        }
    return $archive_menu ;
    }
//---------------------------------------------------
// menu : this is  poll menu
//---------------------------------------------------
//---------------------------------------------------
//
//---------------------------------------------------
    function poll($info =array())
    {
        global $apt;
        $Output = "<CENTER><FONT class=fontablt>".$info["question"]."</font><hr noshade color=#000000 size=0 width=90%><FORM ACTION=\"poll.php?action=vote&id=".$info[quesid]."\" METHOD=\"post\"><TABLE><TR>";
        $answer = "SELECT * FROM rafia_pollanswer WHERE quesid ='$info[quesid]' ORDER BY pollid";
        $answer_res = $apt->query($answer);
        while($row = $apt->dbarray($answer_res))
        {
            $answer = $row['text'];
            $pollid = $row['pollid'];
            $Output .="<TR><TD><FONT class=fontablt><INPUT TYPE=\"radio\" NAME=\"pollid\" VALUE=\"$pollid\"></FONT></TD>";
            $Output .="<TD><FONT class=fontablt>".$answer."</FONT></TD></TR>";
        }
        $Output .= "</tr></table><center><br><input class=\"button\" type=\"submit\" name=\"submit\" value=\"’Ê \"></center></form><a href=\"poll.php?action=view&id=$info[quesid]\">‰ «∆Ã «· ’ÊÌ </a>
        <br><a href=\"poll.php\">«·√—‘Ì›</a>";
        return $Output;

    }
//---------------------------------------------------
//
//---------------------------------------------------
    function pollresult($question =array())
    {
         global $apt;
        $Output ="<center><font class=fontablt>".$question["question"]."<hr noshade color=#000000 size=0 width=100%></font><table><tr>";
        $answer = "SELECT * FROM rafia_pollanswer WHERE quesid='$question[0]'";
        $answer_res = $apt->query($answer);
        $total = 0;
        $imagebar = "images/poll.gif";
        while($row = $apt->dbarray($answer_res))
        {
            $total = $total + $row["results"];
        }
        $answer_res = $apt->query($answer);
        while($row = $apt->dbarray($answer_res))
        {
            $resultpoll = @(($row["results"] / $total)* 100);
            $resultpoll = number_format($resultpoll);
            $answer  = $row['text'];
            $pollid = $row['pollid'];
            $Output .="<td><font class=fontablt>$answer</font></td></tr>
            <tr><td colspan=2><font size=2>
            <img src=\"$imagebar\" height=10 width=$resultpoll> ".$resultpoll."% :
            <font COLOR=#002200> $row[results]</font>
            </font></td></tr>";
        }
        $Output .="</tr><tr><td><font class=fontablt>„Ã„Ê⁄ «·«’Ê«  : <font color=#000000><b>$total</b></font></td>";
        $Output .="</tr></table></center>";
        return $Output;
    }

function poll_menu()
{
     global $apt;
     
     $result = $apt->query("SELECT * FROM rafia_pollques WHERE stat='1' ORDER BY quesid DESC");

     if ($apt->dbnumrows($result) > 0)
     {
         //$info = $apt->dbarray($result);
         while($info = $apt->dbarray($result))
         {
             $cookiepoll = "cookiepoll".$info[quesid];
             
             if($apt->cookies[$cookiepoll] != $info[quesid])
             {
                 $menu  .= $this->getmenu(" ’ÊÌ ",$this->poll($info)) ;
             }else{
                 $menu  .= $this->getmenu(" ’ÊÌ ",$this->pollresult($info)) ;
             }
         }
          return $menu ;
    }

}

//---------------------------------------------------
// menu : this is  member box
//---------------------------------------------------
function member_box()
{

    global $apt,$pmumrows;
    
    if (( $apt->cookie['cgroup'] > "0") or ( $apt->cookie['cgroup'] !== "5"))
    {
        	$useradmin = $apt->cookie['cadmin'];
            switch($useradmin[$i])
            {
                case "1":
                case "2":
                $countwit = $apt->dbnumquery("rafia_news","allow!='yes'","id");
                $countcwit = $apt->dbnumquery("rafia_comment","(allow !='yes' and news_id !='0') or (allow !='yes' and thread_id !='0')","id");
                $isadmin  = "<li><a href=news.php?action=admin>   Õﬂ„ «·«Œ»«— ($countwit)</font></a><br>\n";
                $isadmin .= "<li><a href=comment.php>   Õﬂ„ «· ⁄·Ìﬁ«  ($countcwit)</font></a><br>\n";
                $countwit = $apt->dbnumquery("rafia_forum","allow!='yes'","id");
                $isadmin .= "<li><a href=forum.php?action=admin>  Õﬂ„ «·„‰ œÏ ($countwit)</font></a><br>\n";
                $countwit = $apt->dbnumquery("rafia_download","allow!='yes'","id");
                $isadmin .= "<li><a href=download.php?action=admin>  Õﬂ„ «·»—«„Ã ($countwit)</font></a><br>\n";
                $countwit = $apt->dbnumquery("rafia_links","allow!='yes'","id");
                $isadmin .= "<li><a href=link.php?action=admin>  Õﬂ„ œ·Ì· «·„Ê«ﬁ⁄ ($countwit)</font></a><br>\n";
                $countwit = $apt->dbnumquery("rafia_guestbook","allow!='yes'","id");   // Added by Myrosy
                $isadmin .= "<li><a href=guestbook.php?action=gbwait>  Õﬂ„ ”Ã· «·“Ê«— ($countwit)</font></a><br>\n";   // Added by Myrosy
                $isadmin .= "<li><a href=online.php>«·„ Ê«ÃœÌ‰ Õ«·Ì«</font></a><br>\n";
                break;
                case "3":
                $uid = $apt->cookie['cid'];
                $results = $apt->query ("SELECT modadmin  FROM `rafia_moderate` where `moderateid`='$uid';");
                if ($apt->dbnumrows($results) > 0){
                while($rows = $apt->dbarray($results)){$modadmin[] = $rows['modadmin'];}
                $modadmin = @array_unique($modadmin); $isadmin = '';
                if(@in_array('2',$modadmin))$isadmin .= "<li><a href=news.php?action=admin> Õﬂ„ «·«Œ»«—</font></a><br>\n";
                if(@in_array('3',$modadmin))$isadmin .= "<li><a href=forum.php?action=admin> Õﬂ„ «·„‰ œÏ</font></a><br>\n";
                if(@in_array('4',$modadmin))$isadmin .= "<li><a href=download.php?action=admin> Õﬂ„ „—ﬂ“ «· Õ„Ì·</font></a><br>\n";
                if(@in_array('5',$modadmin))$isadmin .= "<li><a href=link.php?action=admin> Õﬂ„ œ·Ì· «·„Ê«ﬁ⁄</font></a><br>\n";
                }
                break;
            }
    }

    $member_name = $apt->cookie['cname'];
    
    eval("\$s_member_box .=\" " . $this->menucenter . "\";");
    eval("\$h_member_box .=\" " . $this->menuhead . "\";");

    $menu   = $this->getmenu($h_member_box,$s_member_box) ;
    
  return $menu ;
}
//-------------------------------------------
// menu : this is  last topics menu
//-------------------------------------------
function last_topics()
{
    @extract($GLOBALS);
       global $apt;
eval("\$h_last_topics_menu = \" " .  $this->menuhead  . "\";");
///////////////// forum «·„‰ œÏ ////////
$result = $apt->query("SELECT title,id FROM rafia_forum
                                         WHERE allow='yes'
                                         ORDER BY id DESC LIMIT 10");
if ($apt->dbnumrows($result) > 0)
{
     while($row = $apt->dbarray($result))
     {
         @extract($row);
            $title         = $apt->format_data_out($title);
         $forumtopics .="<a href=forum.php?action=view&id=$id>$title</a><br>";


    }
}
//////////   download „—ﬂ“ «· Õ„Ì· /////////
    $result = $apt->query("SELECT title,id FROM rafia_download
                            WHERE allow='yes' ORDER BY id DESC LIMIT 10");
if ($apt->dbnumrows($result) > 0)
{
     while($row = $apt->dbarray($result))
     {
         @extract($row);
            $title         = $apt->format_data_out($title);
         $downloadtopics  .="
                           <a href=download.php?action=view&id=$id>$title</a><br>";


    }
}
//////////// links œ·Ì· «·„Ê«ﬁ⁄ //////////
if ($apt->dbnumrows($result) > 0)
{
        $result = $apt->query("SELECT title,id FROM rafia_links
                            WHERE allow='yes' ORDER BY id DESC LIMIT 10");

     while($row = $apt->dbarray($result))
     {
         @extract($row);
         $title         = $apt->format_data_out($title);
         $linkstopics  .="
                           <a href=link.php?action=goto&id=$id>$title</a><br>";


    }
}
eval("\$s_last_topics_menu = \" " .$this->menucenter . "\";");

$topics   = $this->getmenu($h_last_topics_menu,$s_last_topics_menu) ;

  return $topics ;
}

//---------------------------------------------------
// menu : this is  topic cat menu
//---------------------------------------------------

function topics_cat_menu()
{

     global $apt;

    $result = $apt->query("SELECT title,id FROM rafia_cat
                             WHERE catType = '1' and subcat='0'
                             ORDER BY ordercat ASC");

    while($menu = $apt->dbarray($result))
    {
        $mentitle = $menu["title"];
        $menid    = $menu["id"];
        eval("\$s_topic_cat .= \" " .$this->menucenter . "\";");

    }
    eval("\$h_cat_menu =\" " . $this->menuhead  . "\";");

    $menu  = $this->getmenu($h_cat_menu,$s_topic_cat) ;
    return $menu ;
}
//---------------------------------------------------
// menu : this is  stat  table
//---------------------------------------------------
function stat_table()
{
    //@extract($GLOBALS);
    
    global $Counter;
    
    $numrowsn  = $Counter->getValue('newsCount');

    $numrowsd =  $Counter->getValue('downCount');

    $numrowsf =  $Counter->getValue('forumCount');

    $numrowsg =  $Counter->getValue('gbCount');

    $numrowsl =  $Counter->getValue('linksCount');

    $numrowsm =  $Counter->getValue('usersCount');

    $numrowsc =  $Counter->getValue('commentCount');

    eval ("\$s_stat_menu =\"" . $this->menucenter . "\";");

    eval ("\$h_stat_menu =\"" . $this->menuhead . "\";");

    $menu   = $this->getmenu($h_stat_menu,$s_stat_menu) ;

  return $menu ;
}
//---------------------------------------------------
// menu : this is  online  table
//---------------------------------------------------
function online_menu()
{
    @extract($GLOBALS);

    $result = $apt->query("SELECT DISTINCT onlineip FROM rafia_online WHERE user_online='Guest'");

    $resuser = $apt->query("SELECT DISTINCT onlineip FROM rafia_online WHERE user_online !='Guest'");

    $not_user    = $apt->dbnumrows($result);

    $user_online = $apt->dbnumrows($resuser);
   
    $totalCount = $Counter->getValue('totalCount');
    $dayCount   = $Counter->getValue('dayCount');
    $mostCount  = $Counter->getValue('mostCount');    
	$mosttime   = date("d /m /Y ",$Counter->getValue('mosttime'));  
	

    if (($not_user > 0)||($user_online >0))
    {
        $online = $not_user + $user_online;

        eval ("\$s_online_menu =\"" . $this->menucenter . "\";");

        eval ("\$h_online_menu =\"" . $this->menuhead . "\";");

        $online_menu   = $this->getmenu($h_online_menu,$s_online_menu) ;
    }
    return $online_menu ;
}
//---------------------------------------------------
//
//---------------------------------------------------
function middle_menu(){
	global $apt;
	$middle_menu_count  =  $apt->getsettings("count_middle_menu");
	$middle_menu = "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr valign=top>";
	$middle_menus = $this->_menu(3);
	$ex = @explode('<!-- BLOCK END -->',$middle_menus);
	$m=0;
	foreach($ex as $amenu){
	$middle_menu .= '<td width=50% valign=top>'.$amenu.'</td>';
	$m++;
	if($m==$middle_menu_count){$middle_menu .= "</tr>";
	$m = 0;
	}
	}
	$middle_menu .= "</tr></table><br>";
      return $middle_menu;
}

//---------------------------------------------------
// menu : this is  stat  table
//---------------------------------------------------

function getmenu($head,$center)
{
     global $apt;
     eval("\$menu = \" " . $apt->gettemplate ( $this->blockmenu ) . "\";");
     $menu  = str_replace("{block_head}",$head,$menu);
     $menu  = str_replace("{block_center}",$center,$menu);
     return $menu;
}

}
?>