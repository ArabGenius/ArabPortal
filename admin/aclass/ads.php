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

if (stristr($_SERVER['SCRIPT_NAME'], "ads.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

//########## DO NOT REMOVE THIS #########################
//
// Version V2.0
// By mr.point ( adil alsaady ) <adalsaady@hotmail.com>
// Designed for Rafiaphp V2.0(C)
// ADS HACK
// 
//########## DO NOT REMOVE THIS #########################
class ads
{
    function ads ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);
        
        $admin->head();
        
        if ($action=="add")
        {
        $admin->openform("$PHP_SELF?cat=ads&act=insert&".$admin->get_sess());
        $admin->opentable(_ADD_BANNER);//**By ANS**//
        $admin->inputtext(_BANNER_OWNER,'adsname','');//**BY ANS**//
        $admin->inputtext(_BANNER_URL,'adsimg','');//**By ANS**//
        $admin->inputtext(_SITE_URL,'adsurl','');//**BY ANS**//
        $admin->inputtext('«·⁄—÷ (* ··›·«‘)','width','',5);
        $admin->inputtext('«·«— ›«⁄ (* ··›·«‘)','height','',5);
        $viewin ='h';
        $admin->input_select(_SHOW_IN, "viewin",$viewin,7);//**BY ANS**//
        echo "<tr><td  class=datacell>„·«ÕŸ…: </td><td class=datacell>⁄‰œ «Œ Ì«—ﬂ ( «·ŸÂÊ— ›Ì ﬁ«·» „Õœœ )<br> ÌÃ» «‰  ÷⁄ «·ﬂÊœ «· «·Ì ›Ì «Õœ «·ﬁÊ«·» «· Ì  —ÌœÂ«
		<br><input dir=ltr size=35 value='".htmlspecialchars('<!--INC dir="block" file="ads.php" -->')."'></b></td></tr>";
        $admin->yesorno(" ‰‘Ìÿ «·«⁄·«‰","active",$active);
        $admin->closetable();
        $admin->submit(_ADD);//**By ANS**//
        $admin->closeform ();
        
        }
        else if($action=="insert")
        {
             $timestamp = time();
             $adsimg    = $admin->format_data($apt->post[adsimg]);
             $adsurl    = $admin->format_data($apt->post[adsurl]);
             $adsname   = $admin->format_data($apt->post[adsname]);
             $viewin    = $admin->format_data($apt->post[viewin]);
             $active    = $admin->format_data($apt->post[active]);
             $width     = intval($apt->post[width]);
             $height    = intval($apt->post[height]);

             $what=$apt->query("INSERT INTO rafia_ads
                               (adsimg,adsdatetime,adsurl,adsname,viewin,active,width,height) VALUES
                               ('$adsimg','$timestamp','$adsurl','$adsname','$viewin','$active','$width','$height')");
             if($what)
             {
                 echo "<center>";
                 $admin->opentable(_REMINDE);//**By ANS**//
                 echo "<tr><td  class=datacell><center>"._AD_ADD_OK."</center></td></tr>";//**BY ANS**//
            	$admin->closetable();
                 echo "</center>";
             }
        }
        else if ($action=="del")
        {
             $id = $apt->setid('id');
            $what = $apt->query("delete from  rafia_ads where adsid='$id'");
            if($what)
            {
            	echo "<center>";
            	$admin->opentable(_REMINDE);//**BY ANS**//
	            echo "<tr>
	            <td  class=datacell >
	            <center>"._AD_DELETE_OK."</center>
	            </td></tr>";//**BY ANS**//
	            $admin->closetable();
	            echo "</center>";
             }
        }
        else if($action=="list")
        {
            echo "<tr> ";
            $admin->opentable(_INFO,"4");//**BY ANS**//
            $admin->td_msgh(_AD_NAME,30);//**BY ANS**//
            $admin->td_msgh(_ADD_DATE,30);//**BY ANS**//
            $admin->td_msgh(_EDIT,10);//**BY ANS**//
            $admin->td_msgh(_DELETE,10);//**BY ANS**//
            echo "</tr>";
            $get_all_ads=$apt->query("select * from rafia_ads");
            while($fetch=$apt->dbarray($get_all_ads))
            {
                @extract($fetch);
                $adsdate =  $apt->Hijri("$adsdatetime");
                echo "<tr> ";
                $admin->td_msg("$adsname",30);
                $admin->td_msg("$adsdate",30);
                $admin->td_url("$PHP_SELF?cat=ads&act=edit&id=$adsid&".$admin->get_sess(), _EDIT,10);//**BY ANS**//
                $admin->td_url("$PHP_SELF?cat=ads&act=del&id=$adsid&".$admin->get_sess(), _DELETE ,10);//**BY ANS**//
                echo "</tr>";
            }
        }
        else if($action=="veiw")
        {
              echo "<tr> ";
              $admin->opentable(_INFO,"7");//**BY ANS**//
              $admin->td_msgh(_AD_NAME,25);//**BY ANS**//
              $admin->td_msgh(_NUMBER_OF_VIEWS,12);//**BY ANS**//
              $admin->td_msgh(_NUMBER_OF_HITS,12);//**BY ANS**//
              $admin->td_msgh(_SHOW_IN,6);//**BY ANS**//
              $admin->td_msgh(_STATUS,6);//**BY ANS**//
              $admin->td_msgh(_ADD_DATE,35);//**BY ANS**//
	          echo "</tr>";
              $get_all_ads=$apt->query("select * from rafia_ads");
              while($fetch=$apt->dbarray($get_all_ads))
              {
                  @extract($fetch);
                  $adsdate =  $apt->Hijri("$adsdatetime");
                  if($viewin == 'h'){$viewin = '«⁄·Ï «·’›Õ…';
			}elseif($viewin == 'f'){$viewin = '«”›· «·’›Õ…';
			}elseif($viewin == 'm'){$viewin = '«·ﬁ«∆„… «·Ã«‰»Ì…';
			}else{$viewin = 'ﬁ«·» „Õœœ';}
                  if($active == '1'){$active = '‰‘ÿ';}else{$active ='„⁄ÿ·';}

                  echo "<tr> ";
                  $admin->td_msg("$adsname",25);
                  $admin->td_msg("$numshow",10);
                  $admin->td_msg("$clicks",10);
                  $admin->td_msg("$viewin",15);
                  $admin->td_msg("$active",5);
                  $admin->td_msg("$adsdate",40);
                  echo "</tr>";
              }
              $admin->closetable();
         }
         else if ($action=="edit")
         {
             $id = $apt->setid('id');
             $result=$apt->query("select * from rafia_ads where adsid='$id'");
             $ads = $apt->dbarray($result);
             extract($ads);
             $admin->openform("$PHP_SELF?cat=ads&act=update&id=$adsid&".$admin->get_sess());
             $admin->opentable(_EDIT_BANNER);//**BY ANS**//
             $admin->inputtext(_BANNER_OWNER,'adsname',$adsname);//**BY ANS**//
             $admin->inputtext(_BANNER_URL,'adsimg',$adsimg);//**BY ANS**//
             $admin->inputtext('«·⁄—÷ (* ··›·«‘)','width',$width,5);
             $admin->inputtext('«·«— ›«⁄ (* ··›·«‘)','height',$height,5);
             $admin->inputtext(_SITE_URL,'adsurl',$adsurl);//**BY ANS**//
             $admin->input_select(_SHOW_IN, "viewin",$viewin,7);//**BY ANS**//
        		echo "<tr><td  class=datacell>„·«ÕŸ…: </td><td class=datacell>⁄‰œ «Œ Ì«—ﬂ ( «·ŸÂÊ— ›Ì ﬁ«·» „Õœœ )<br> ÌÃ» «‰  ÷⁄ «·ﬂÊœ «· «·Ì ›Ì «Õœ «·ﬁÊ«·» «· Ì  —ÌœÂ«
			<br><input dir=ltr size=35 value='".htmlspecialchars('<!--INC dir="block" file="ads.php" -->')."'></b></td></tr>";
             $admin->yesorno(" ‰‘Ìÿ «·«⁄·«‰","active",$active);
             $admin->closetable();
             $admin->submit(_EDIT);//**BY ANS**//
             $admin->closeform ();

         }
         else if ($action=="update")
         {
             $id = $apt->setid('id');
             $adsimg    = $admin->format_data($apt->post[adsimg]);
             $adsurl    = $admin->format_data($apt->post[adsurl]);
             $adsname   = $admin->format_data($apt->post[adsname]);
             $viewin    = $admin->format_data($apt->post[viewin]);
             $active    = $admin->format_data($apt->post[active]);
             $width     = intval($apt->post[width]);
             $height    = intval($apt->post[height]);

             $update = $apt->query("update rafia_ads set adsimg='$adsimg' ,
                                                  width='$width',
                                                  height='$height',
                                                  adsurl='$adsurl',
                                                  adsname='$adsname',
                                                  viewin='$viewin',
                                                  active='$active'
                                                  where adsid='$id'");

           if($update)
           {
               echo "<center>";
               $admin->opentable(_REMINDE);//**BY ANS**/
               echo "<tr><td  class=datacell ><center>"._BANNER_EDIT_OK."</center></td></tr>";//**BY ANS**//
               $admin->closetable();
               echo "</center>";
           }
       }
    }
}
?>