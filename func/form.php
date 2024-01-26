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
|  Last Updated: 08/11/2006 Time: 10:00 PM  |
+===========================================+
*/
class form
{
    var $countpost    = "";
    var $counthead    = "";
    var $use_smiles   = 1;

    function forum_form($k = '')
    {
         global $apt,$CTree;
         
         $this->countpost = $apt->getsettings('txtcount3');

        if ($k == 'add')
        {

            if( $apt->cookie['cid'] == $apt->Guestid)
            {
                $form .= $this->inputform  ($apt->lang_form['28'],"text","name","","");
                $form .= $this->hiddenform  ("userid", '0');
            }
            if(!isset($apt->get['cat_id']))
            {
                 $form .= $this->selectform ("2");
            }
            $form .= $this->inputform  ($apt->lang_form['1'],"text", "title","*","",30);
             eval("\$this->form_code = \"" . $apt->gettemplate ( 'form_code' ) . "\";");

             $form .= $this->iconid     ();
             $form .= $this->txtcount   ();
             $form .= $this->textarea   ();
             $form .= $this->tellform   ();
             $form .= $this->yesorno($apt->lang_form['36'],"usesig", "1");

             if ($apt->checkgroup('upload_forum_file'))
             {
                 $form .= $this->inputform  ($apt->lang_form['29'],"file","phpfile","");
             }
            if(isset($apt->get['cat_id']))
            {
                 $form .= $this->hiddenform  ("cat_id",$apt->get['cat_id']);
            }

             $image = "themes/".$GLOBALS[themepath]."/topic.gif";

             $return = $apt->table_cat_link(LANG_TITLE_FORUMS,$cat_id);
         
           $output = array("action"      => "$PHP_SELF?action=insert",
                            "title"      => LANG_TITLE_ADD_THREAD,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
        }
        elseif($k == 'edit')
        {
             $form_lang_head = $lang_head['23'];

            if ( ($apt->checkcadmincat($apt->row['cat_id'])) or ($apt->checkgroup('edit_forum_own')) )
            {
                  if ($apt->checkcmodcat('can_delete'))
                  {
                      $form .= $this->deleteform();
                  }
                  $form .= $this->admin_select_cat_id("2");
                  $form .= $this->iconid     ();
                  $form .= $this->inputform  ($apt->lang_form['28'],"text","name","",stripslashes($apt->row['name']));
            }
             $form .= $this->inputform    ($apt->lang_form['1'],"text", "title","*",stripslashes($apt->row['title']));
                  eval("\$this->form_code = \"" . $apt->gettemplate ( 'form_code' ) . "\";");

             $form .= $this->txtcount     ();
             $form .= $this->textarea     ($apt->lang_form['5'],"post",stripslashes($apt->row['post']));
            if ($apt->checkcadmincat($apt->row['cat_id']))
            {
                 $form .= $this->adminallow();
                 if ($apt->row['uploadfile'] > 0)
                 {
                     $form .= $this->editupload();
                     $form .= $this->hiddenform  ("uploadfile", $apt->row['uploadfile']);
                 }
            }
            $form .= $this->hiddenform  ("action","UF&id=".$apt->row['id']);

             $image = "themes/".$GLOBALS[themepath]."/do.gif";

             $return = $apt->table_cat_link(LANG_TITLE_FORUMS,$apt->row['cat_id'],$apt->row['title']);
           $output = array("action"      => "$PHP_SELF?action=UF&id=".$apt->row['id'],
                            "title"      => LANG_TITLE_EDIT_POST,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
                           
        }
         $return .= $this->formtable($output);
         return $return;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function gb_form($k = '')
    {
        @extract($GLOBALS);
        
        $this->countpost = $apt->getsettings('txtcount5');
        
	if($k == 'edit')
        {
            $form_lang_head = LANG_TITLE_ADD_SIGNATURE ;
            if ($apt->checkcmodcat('can_delete'))
            {
                $form .= $this->deleteform();
            }
            $form .=  $this->inputform ( $apt->lang_form['16'],"text", "name","*",$apt->row['name']);
            $form .=  $this->inputform ( $apt->lang_form['14'],"text", "email","*",$apt->row['email'],"30");
            $form .=  $this->inputform ( $apt->lang_form['15'],"text", "url","",$apt->row['url'],"50");
            $form .=  $this->txtcount  ();
            $form .=  $this->textarea  ( $apt->lang_form['17'],"post",$apt->row['guestbook']);
            $form .=  $this->adminallow();
            $form .= $this->hiddenform  ("action","UG&id=".$apt->row['id']);

            $image = "themes/".$GLOBALS[themepath]."/sign.gif";
           $output = array("action"      => "$PHP_SELF?action=UG&id=".$apt->row['id'],
                            "title"      => LANG_TITLE_ADD_SIGNATURE,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );

        }
         return $this->formtable($output);
    }

//---------------------------------------------------
//
//---------------------------------------------------
    function news_form($k = '')
    {
         @extract($GLOBALS);
         $this->counthead = $apt->getsettings('txtcount1');
         $this->countpost = $apt->getsettings('txtcount2');
        if($k == 'edit')
        {
            $form_lang_head = LANG_TITLE_EDIT_POST;
            if ( $apt->checkcadmincat($apt->row['cat_id']) || $apt->checkgroup('edit_news'))
            {
                if (($apt->checkcmodcat('can_delete')) || $apt->checkgroup('edit_news'))
                {
                    $form .= $this->deleteform();
                }
                if (($apt->checkcmodcat('can_move')) || $apt->checkgroup('edit_news'))
                {
                     $form .=  $this->admin_select_cat_id("1");
                }

                $form .=  $this->inputform  ($apt->lang_form['28'],"text","name","",stripslashes($apt->row['name']));
            }

             $form .= $this->inputform    ($apt->lang_form['1'],"text", "title","*",stripslashes($apt->row['title']));

             $form .= $this->txtcounth    ();
             $form .= $this->textareahead ($apt->lang_form['2'],stripslashes($apt->row['news_head']));

           if($apt->row['ues_editor'] == 0)
            {
                eval("\$this->form_code = \"" . $apt->gettemplate ( 'form_code' ) . "\";");
            }
            else
            {
                    $form .=  $apt->editor();
                    $this->use_smiles = 0;
                    $form .= $this->hiddenform  ("ues_editor", '1');
            }
            $form .=  $this->txtcount     ();
            $form .=  $this->textarea     ($apt->lang_form['3'],"post",stripslashes($apt->row['post']));

            if (  $apt->checkcadmincat($apt->row['cat_id'])  || $apt->checkgroup('edit_news'))
            {
                     $form .=  $this->yesorno($apt->lang_form['35'],"inmenu",  $apt->row['inmenu']);
                     $form .=  $this->yesorno($apt->lang_form['34'],"inindex",  $apt->row['inindex']);
                     if ($apt->cookie['cgroup'] == $apt->a_g){
                     $form .=  $this->yesorno("Œ‹»‹— —∆‹Ì‹”‹Ì ø","main", $apt->row['main']);}
                     $form .=  $this->yesorno($apt->lang_form['33'],"catmig", $apt->row['catmig']);
                     $form .=  $this->adminallow();
                  if ($apt->row['uploadfile'] > 0)
                 {
                     $form .= $this->editupload();
                     $form .= $this->hiddenform  ("uploadfile", $apt->row['uploadfile']);
                 }
                 else
                 {
                     if(($apt->getsettings('imgallow') == 'yes') ||($apt->checkgroup('add_news_img')))
                     {
                         $form .=  $this->inputform  ($apt->lang_form['26'],"file","phpfile","");
                         $form .=  $this->inputimgform  ($apt->lang_form['27']);

                     }
                 }
            }
            $form .= $this->hiddenform  ("action","UN&id=".$apt->row['id']);
            $image = "themes/".$GLOBALS[themepath]."/do.gif";

             $output = array("action"     => "$PHP_SELF?action=UN&id=".$apt->row['id'],
                             "title"      => LANG_TITLE_EDIT_POST,
                             "method"     => '',
                             "name"       => '',
                             "content"    => $form,
                             "image"      => $image
                            );
             $return = $apt->table_cat_link(LANG_TITLE_NEWS,$apt->row['cat_id'],$apt->row['title']);
        }

        $return .= $this->formtable($output);
         return $return;
    }
//---------------------------------------------------
//---------------------------------------------------
   function download_form($k = '')
   {
        extract($GLOBALS);

         $this->counthead = $apt->getsettings('txtcount9');
         $this->countpost = $apt->getsettings('txtcount4');

        if ($k == 'add')
        {
            $form = "";
            
            if( $apt->cookie['cid'] == $apt->Guestid)
            {
                $form .= $this->inputform  ($apt->lang_form['28'],"text","name","*","");
                $form .= $this->hiddenform  ("userid", '0');
            }
            
            if(!isset($cat_id))
            {
                $form .=     $this->selectform ("3");
            }
            $form .=   $this->inputform  ($apt->lang_form['1'],"text", "title","*");
            $form .=   $this->inputform  ($apt->lang_form['7'],"text", "size","*","","15");
            $form .=   $this->inputform  ($apt->lang_form['8'],"text", "url","*","","50");
            if ($apt->checkgroup('upload_download_file'))
            {
                $form .= $this->inputform  ($apt->lang_form['29'],"file","phpfile","");
            }

            $form .=   $this->inputform  ($apt->lang_form['40'],"text", "exp_show","","","50");

            $form .=  $this->yesorno("··√⁄÷«¡ ›ﬁÿ",'formmember','1');
            $form .=  $this->txtcounth();
            $form .=  $this->textareahead   ($apt->lang_form['9']);
            eval("\$this->form_code = \"" . $apt->gettemplate ( 'form_code' ) . "\";");
            $form .=  $this->txtcount   ();
            $form .=  $this->textarea   ($apt->lang_form['10']);
            $form .=  $this->yesorno($apt->lang_form['39'],'downFrom','1');
            $form .=  $this->tellform   ();
            if(isset($cat_id))
            {
                 $form .= $this->hiddenform  ("cat_id", $cat_id);
            }
            
            $form .= $this->hiddenform  ("action","insert");
            
            
            $image = "themes/".$GLOBALS[themepath]."/prog.gif";
            
            $output = array("action"     => "$PHP_SELF?action=insert",
                            "title"      => LANG_TITLE_ADD_PROGRAM,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                            );

        }
        elseif($k == 'edit')
        {

            if ($apt->checkcadmincat($apt->row['cat_id']))
            {
                if ($apt->checkcmodcat('can_delete'))
                {
                    $form .= $this->deleteform();
                }
                  $form .= $this->admin_select_cat_id("3");
                  $form .= $this->inputform  ($apt->lang_form['28'],"text","name","",stripslashes($apt->row['name']));
            }
             $form .= $this->inputform    ($apt->lang_form['1'],"text", "title","*",stripslashes($apt->row['title']));
             $form .= $this->inputform    ($apt->lang_form['7'],"text", "size","*",stripslashes($apt->row['size']),"7");
             $form .= $this->inputform    ($apt->lang_form['8'],"text", "url","*",stripslashes($apt->row['url']),"50");
             $form .= $this->inputform    ($apt->lang_form['40'],"text", "exp_show","",stripslashes($apt->row['exp_show']),"50");
             $form .=  $this->yesorno("··√⁄÷«¡ ›ﬁÿ",'formmember',$apt->row['formmember']);
             $form .= $this->txtcounth    ();
             $form .= $this->textareahead     ($apt->lang_form['9'],stripslashes($apt->row['post_head']));
                eval("\$this->form_code .= \"" . $apt->gettemplate ( 'form_code' ) . "\";");

             $form .= $this->txtcount     ();
             $form .= $this->textarea     ($apt->lang_form['3'],"post",stripslashes($apt->row['post']));
             $form .=  $this->yesorno($apt->lang_form['39'],'downFrom',stripslashes($apt->row['downFrom']));

             if ($apt->checkcadmincat($apt->row['cat_id']))
             {
                 $form .= $this->adminallow();
             }
             
             $form .= $this->hiddenform  ("action","UD&id=".$apt->row['id']);
             $image = "themes/".$GLOBALS[themepath]."/prog.gif";
             $output = array("action"     => "$PHP_SELF?action=UD&id=".$apt->row['id'],
                             "title"      => LANG_TITLE_ADD_PROGRAM,
                             "method"     => '',
                             "name"       => '',
                             "content"    => $form,
                             "image"      => $image
                            );
        }
        return $this->formtable($output);
    }
//---------------------------------------------------
//
//---------------------------------------------------
   function link_form($k = '')
   {
       extract($GLOBALS);
          $this->countpost = $apt->getsettings('txtcount10');
        if($k == 'edit')
        {
            if ( $apt->checkcadmincat($apt->row['cat_id']))
            {
                if ($apt->checkcmodcat('can_delete'))
                {
                    $form .= $this->deleteform();
                }
                if ($apt->checkcmodcat('can_move'))
                {
                     $form .=  $this->admin_select_cat_id("4");
                }
            }

            $form .= $this->inputform($apt->lang_form['18'],"text", "title","*",stripslashes($apt->row['title']));
            $form .= $this->inputform($apt->lang_form['19'],"text", "url","*",stripslashes($apt->row['url']),"50");
            $form .= $this->inputform($apt->lang_form['16'],"text", "name","*",stripslashes($apt->row['name']));
            $form .= $this->inputform($apt->lang_form['14'],"text", "email","",stripslashes($apt->row['email']));
            $form .= $this->txtcount();
            $form .= $this->textarea($apt->lang_form['9'],"post",stripslashes($apt->row['post']),"8");
            $form .= $this->adminallow();
            $form .= $this->hiddenform  ("action","UK&id=".$apt->row['id']);
           $image = "themes/".$GLOBALS[themepath]."/do.gif";
            $output = array("action"     => "$PHP_SELF?action=UK&id=".$apt->row['id'],
                            "title"      => LANG_TITLE_ADD_SITE,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
            
        }
          return $this->formtable($output);
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function comment_form($k ='',$d = 1)
    {
          global $apt;
         $this->countpost = $apt->getsettings('txtcount6');
         if ($k == 'add')
         {
           $form .=  $this->txtcount();

             if( $apt->cookie['cid'] == $apt->Guestid)
            {
                 $form .= $this->inputform  ($apt->lang_form['28'],"text","name","","");

            }
           $form .=  $this->inputform   ( $apt->lang_form['1'],"text", "title","","",30);

            eval("\$this->form_code .= \"" . $apt->gettemplate ( 'form_code' ) . "\";");

           $form .=   $this->textarea    ( $apt->lang_form['5'],"post",$apt->row['quote'],"15",70);
           if ($apt->checkgroup('upload_comment_file'))
           {
                 $form .= $this->inputform  ($apt->lang_form['29'],"file","phpfile","");
           }
           $form .=   $this->tellform    ();
           $form .=   $this->yesorno($apt->lang_form['36'],"usesig", "1");
           $form .=   $this->hiddenform  ("cat_id",$apt->row[cat_id]);
           $form .=   $this->hiddenform  ("post_id",$apt->row[id]);
           $form .=   $this->hiddenform  ("titlepost",$apt->row[title]);
           $form .=   $this->hiddenform  ("action","insertcomment");
           $form .=   $this->hiddenform  ("spam","1");
           $image = "themes/".$GLOBALS[themepath]."/replay.gif";
           
           $output = array("action"      => "$PHP_SELF?action=insertcomment",
                            "title"      => LANG_TITLE_ADD_COMMENT,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
                           
           $output = $this->formtable($output,1);
           
           if(!isset($apt->get[qp])&&!isset($apt->get[qc])&& $d == 1)
           {
               $output .=   $apt->iframe();
           }
        }
        elseif($k == 'edit')
        {
             $form_lang_head = LANG_TITLE_EDIT_POST;
             $form .= $this->txtcount  ();
             
             if ( $apt->checkcadmincat($apt->row['cat_id']))
            {
                  if ($apt->checkcmodcat('can_delete'))
                  {
                      $form .= $this->deleteform();
                  }
                  $form .=$this->inputform    ($apt->lang_form['1'],"text", "title","",stripslashes($apt->row['title']));
            }

              eval("\$this->form_code .= \"" . $apt->gettemplate ( 'form_code' ) . "\";");

             $form .= $this->textarea  ($apt->lang_form['5'],"post",stripslashes($apt->row['comment']));

             if (( $apt->checkcadmincat($apt->row['cat_id']))&& ($apt->row['uploadfile'] > 0) )
             {
                $form .= $this->editupload();
                $form .= $this->hiddenform  ("uploadfile", $apt->row['uploadfile']);
             }
             $form .=  $this->adminallow();
             $form .=$this->hiddenform  ("cat_id",$apt->row['cat_id']);
             $form .=$this->hiddenform  ("post_id",$apt->row['post_id']);
             $form .=$this->hiddenform  ("commentpage", $apt->refe);
             $form .=$this->hiddenform  ("action","UC&id=".$apt->row['id']);
             $image = "themes/".$GLOBALS[themepath]."/edit.gif";

             $output = array("action"     => "$PHP_SELF?action=UC&id=".$apt->row['id'],
                             "title"      => LANG_TITLE_EDIT_POST,
                             "method"     => '',
                             "name"       => '',
                             "content"    => $form,
                             "image"      => $image
                           );
                           
             $output = $this->formtable($output);
        }

        return $output;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function fastreplay($id)
    {
            global $apt;
            $result = $apt->query("SELECT * FROM rafia_forum WHERE allow='yes' and id='$id' limit 1;");
            $row    = $apt->dbarray($result);
            $this->countpost = $apt->getsettings('txtcount6');
            $form .= $this->txtcount    ();
            if( $apt->cookie['cid'] == $apt->Guestid)
            {
                 $form .= $this->inputform  ($apt->lang_form['28'],"text","name","","");

            }
            $form .= $this->inputform   ( $apt->lang_form['1'],"text", "title","","",30);
             eval("\$this->form_code .= \"" . $apt->gettemplate ( 'form_code' ) . "\";");

            $form .= $this->textarea    ( $apt->lang_form['5'],"post","","10");
            $form .= $this->tellform    ();
            $form .= $this->yesorno($apt->lang_form['36'],"usesig", "1");
           $form .=   $this->hiddenform  ("spam","1");
            $form .= $this->hiddenform  ("cat_id",$row[cat_id]);
            $form .= $this->hiddenform  ("post_id",$row[id]);
            $form .= $this->hiddenform  ("titlepost",$row[title]);
            $image = "themes/".$GLOBALS[themepath]."/replay.gif";
            $output = array("action"     => "$PHP_SELF?action=insertcomment",
                            "title"      => LANG_TITLE_ADD_COMMENT,
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
           return $this->formtable($output,1);
        }
//---------------------------------------------------
    function nfastreplay($id,$bbcode=1)
    {
            global $apt,$cat_id;
            $this->countpost = $apt->getsettings('txtcount6');
            $form .= $this->txtcount    ();
            if( $apt->cookie['cid'] == $apt->Guestid)
            {
                 $form .= $this->inputform  ($apt->lang_form['28'],"text","name","","");

            }
            $form .= $this->inputform   ( $apt->lang_form['1'],"text", "title","","",30);
            if($bbcode==1)eval("\$this->form_code = \"" . $apt->gettemplate ( 'form_code' ) . "\";");

            $form .= $this->textarea    ( $apt->lang_form['5'],"post","","15");
            $form .=   $this->hiddenform  ("spam","1");
            $form .= $this->hiddenform  ("usesig",'1');
            $form .= $this->hiddenform  ("cat_id",$cat_id);
            $form .= $this->hiddenform  ("post_id",$id);
            $image = "themes/".$GLOBALS[themepath]."/replay.gif";
            $output = array("action"     => "news.php?action=insertcomment",
                            "title"      => LANG_TITLE_ADD_COMMENT . ' ”—Ì⁄ ',
                            "method"     => '',
                            "name"       => '',
                            "content"    => $form,
                            "image"      => $image
                           );
           return $this->formtable($output,1);
        }
//---------------------------------------------------


//------------- form  functions ----------//

    function txtcounth()
    {
        ?>
      <script language='JavaScript'>
        function countIt()
        {
            if(document.rafia.post_head.value.length > <? print $this->counthead ; ?> )
            {
               alert("<? print ("⁄œœ «·«Õ—› «ﬂÀ— „‰  :  ".$this->counthead ." Õ—›" );?> ");

              return false;
            }
        }

        </script>
 <?
    }
    function txtcount()
    {

        return "<script language=JavaScript>

        function proces()
        {
            value = document.rafia.post.value;
            len = $this->countpost - value.length;
            if (len < 0 )
            {
                document.rafia.post.value = document.rafia.post.value.substring(0,$this->countpost);
                document.rafia.left.value = 0;
            }
            else
            {
                document.rafia.left.value = len
            }
            document.rafia.remain.value = len;
        }
        </script>";
    }

    function txtcount_old()
    {

        return "<script language=JavaScript>
        document.onkeyup = proces;
        document.onmouseup = proces;
        document.onmousedown = proces;
        function proces()
        {
            value = document.rafia.post.value;
            len = $this->countpost - value.length;
            if (len < 0 )
            {
                document.rafia.post.value = document.rafia.post.value.substring(0,$this->countpost);
                document.rafia.left.value = 0;
            }
            else
            {
                document.rafia.left.value = len
            }
        }
        </script>";
    }


 function formtable($form =array(),$antispam=0,$val='commenttnotspam')
 {
     if(empty($form[method]))
     $form[method] =  "post";
     if(empty($form[name]))
     $form[name]   = "rafia";
	if($antispam==1){
		$checkspam = "<script language=javascript>function checkForm(){document.rafia.spam.value= '".$val."';}</script>";
	$onsub = "onsubmit=\"return checkForm(this);\"";
	}
       $output = $checkspam . '<form action="'.$form[action].'" '.$onsub.' method="'.$form[method].'" name="'.$form[name].'" enctype="multipart/form-data">
       <table border="0" width="90%"cellspacing="0" cellpadding="0">
					<tr>
						<td align=right class="forum_header">
					'. $form[title].' </td>
					</tr>
                      <tr>
						<td>
						<table border="0" width="100%" id="table13" cellpadding="2" class="fontablt">
                         '.$form[content].'
						</table>
						</td>
					</tr>
					<tr>
						<td align="center">';
                            if(!empty($form[image]))
                            {
                                $output .='<input border="0" src="'.$form[image].'" name="I1" width="98" height="24" type="image">';
                            }
                            else
                            {
                                $output .= "<input type=submit  name=\"".$form[submit]."\" value='$title' style=\"border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 2; padding-bottom: 2\">";
                            }
						$output .= '</td>
					</tr>
				</table> </form>';
    return $output;
  }

    function openformdata($action, $method = 'post', $name = 'rafia')
    {
       //global $bgcolor4;
       $form .= '<form action='.$action.' method="post" name="rafia" enctype="multipart/form-data">';
       $form .= "<table bgcolor=\"$bgcolor4\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" style=\"border-collapse: collapse; border-width: 0\" bordercolor=\"#111111\" width=\"90%\" id=\"AutoNumber7\">\n";

       return $form;
    }

    function openform($action, $method = 'post', $name = 'rafia')
    {
        global $bgcolor4;
          $form .= "<form onsubmit=\"return ValidateForm()\" action='$action' method=$method name=$name>\n";
          $form .= " <table bgcolor=\"$bgcolor4\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" style=\"border-collapse: collapse; border-width: 0\" bordercolor=\"#111111\" width=\"90%\" id=\"AutoNumber7\">\n";
        return $form;
    }


    function closeform()
    {
         $form .= "</table>";
         $form .= "</form>";
          return $form;
    }

    function tdform($title ='', $text='', $value='')
    {
        if($text==''){
             $text = "";
        }else{
             $text = "<br><font color=\"#FF0000\">$text</font>";
        }
         $form ="<tr><td align=right nowrap class=\"info_bar\">$title :$text</td>
		<td align=right width=\"100%\">$value</td></tr>";
               return $form;
    }


     function submit($title,$name='submit')
     {
          $form .= "<tr><td align=right width=100%><center>";
          $form .= "<input type=submit  name=\"$name\" value='$title' style=\"border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 2; padding-bottom: 2\">";
          $form .= "</center></td></tr><br>\n";
           return $form;
     }
    function tellform()
    {
         $form .= "<td align=right nowrap class=\"info_bar\"> «· »·Ì€ »«·»—Ìœ «·≈·ﬂ —Ê‰Ì
                  </td> <td align=right width=\"100%\">";
         $form .= "<input type=\"radio\" name=H value='1'>  ‰⁄„ ";
         $form .= "<input type=\"radio\" name=H value='0' checked>  ·« ";
         $form .= "</td></tr>\n";
           return $form;
    }


    function hiddenform($name, $value='')
    {
        return "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
    }

    function cellform($title ='', $full='', $value='')
    {
        if($full==''){
             $full = "";
        }else{
             $full = "<font color=\"#FF0000\">*</font>";
        }
         $form ="<tr><td align=right nowrap class=\"info_bar\">$title : $full </td>
				<td align=right width=\"100%\">$value</td>
			</tr>";
          return $form;
    }

    function inputform($title ='', $type, $name, $full='', $value='',$size='35')
    {
        if($full=='')
        {
             $full = "";
        }
        else
        {
             $full = "<font color=\"#FF0000\">*</font>";
        }
         $form ="<tr><td align=right nowrap class=\"info_bar\">$title : $full </td>
				<td align=right width=\"100%\">
				<input type=\"$type\" name=\"$name\" value=\"$value\" size=\"$size\" class=\"text_box\">
				</td>
				</tr>";
    
           return $form;
    }


    function inputimgform($title ='')
    {

         $form = "<tr>";
         $form .= "<td align=right nowrap class=\"info_bar\">$title</font></td>";
         $form .= "<td align=right width=100%>";
         $form .= "<font class=fontablt> €ÌÌ— «·«»⁄«œ «·ÕﬁÌﬁÌ…</font><input type=checkbox name=\"imagesame\" value='1'>";
         $form .= "&nbsp;&nbsp;";
         $form .= "<font class=fontablt>⁄—÷: </font><input type=\"text\" name=\"imagewidth\" value=\"150\" size=5>";
         $form .= "</td></tr>\n";
         $form .= "<tr><td align=right nowrap class=\"info_bar\"> ÕœÌœ «·« Ã«… </td>";
         $form .= "<td align=right width=100%><input  type=\"radio\" name=imagealign  value='right' checked style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> Ì„Ì‰";
         $form .= "&nbsp;&nbsp;";
         $form .= "<input type=\"radio\" name=imagealign  value=\"left\"  style=\"font-family: MS Sans Serif; font-size: 8px \"> <font class=fontablt> Ì”«— ";
         $form .= "</td></tr>\n";
         return $form;
    }

    function smiles()
    {
        global $bgcolor3,$apt;
         $form .= "<br>&nbsp;<div align=\"center\">
                 <table border=\"0\" id=\"table14\" cellspacing=\"4\" cellpadding=\"4\" bgcolor=\"#F4F4F4\" style=\"border-style: inset; border-width: 1px\"><tr><td>";
         $result = $apt->query("SELECT * FROM rafia_smileys ORDER BY id");

        while($row=$apt->dbarray($result))
        {
             @extract($row);
             $form .= "<a href=\"javascript:emoticons('$code')\">
                       <img src=\"images/smiles/$smile\" alt=\"$smilename\" border=\"0\"></a>&nbsp;";
             $count++;
            if ($count == 3)
            {
                $form .= "<br>";
                $count = 0;
            }

        }
          $form .= "</td></tr></table></div>\n";
           return $form;
    }

    function iconid()
    {
          $form  = "<tr><td align=right nowrap class=\"info_bar\">
                   <INPUT type=\"radio\" name=\"iconid\" value=\"0\" CHECKED> ⁄œ„ «” Œœ«„ «ÌﬁÊ‰…  </td>";
          $form .= '<td align=right valign="top" width="100%">
          <INPUT type="radio" name="iconid" value="1">&nbsp;
          <IMG SRC="images/icon/1.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="2" >&nbsp;
          <IMG SRC="images/icon/2.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="3" >&nbsp;
          <IMG SRC="images/icon/3.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="4" >&nbsp;
          <IMG SRC="images/icon/4.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="5" >&nbsp;
          <IMG SRC="images/icon/5.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="6" >&nbsp;
          <IMG SRC="images/icon/6.gif"  ALIGN="center" alt="">&nbsp;&nbsp;
          <INPUT type="radio" name="iconid" value="7" >&nbsp;&nbsp;
          <IMG SRC="images/icon/8.gif"  ALIGN="center" alt="">';
          $form .= "</td></tr>";
       return $form;
   }

    function textarea($title ='«·‰’', $names='post',$value='',$rows='15',$cols='60')
    {

         $form = "<tr>";
         if($this->use_smiles == 1)
         {
             $smiles =  $this->smiles();
         }

       if ($names=="post")
       {
            $form .= "<td align=right nowrap class=\"info_bar\" valign=\"top\">$title : <font color=\"#FF0000\">*</font> $smiles <br> ÿÊ· «·‰’ ÌÃ» «‰ ÌﬂÊ‰ <br> √ﬁ· „‰ : $this->countpost  Õ—›<br><center> <A HREF=\"#count\" onmouseover=\"return proces()\">≈Œ »«— «·ÿÊ·</A><a neme='count'><br> »ﬁÏ ·ﬂ : <input size=5 name=remain type=text value=$this->countpost> <center> </td>
            <td align=right width=\"100%\">".$this->form_code."<br><textarea dir=rtl onmouseover=\"return proces()\" onbeforeeditfocus=\"return proces()\" onmouseout=\"return proces()\" name=$names rows=$rows cols=$cols id=\"$names\" wrap=virtual>$value</textarea>";
            $form  .= "<input type='hidden' name='left' value='$this->countpost' size='7' class=\"text_box\">";
            $form .= "</td></tr>\n";
        }
       if ($names=="message")
       {
            $form .= "<td align=right nowrap class=\"info_bar\" valign=\"top\">$title :</td>
            <td align=right width=\"100%\"><font color=\"#FF0000\">*</font><textarea dir=rtl name=$names rows=$rows cols=$cols id=\"$names\"  wrap=virtual>$value</textarea>";
            $form .= "</td></tr>";
       }
          return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function textareahead($title ='«·‰’',$value='')
    {
        $form  = "<tr><td align=right nowrap class=\"info_bar\" valign=\"top\">$title : <font color=\"#FF0000\">*</font></td>";
        $form .=  '<td align=right width=\"100%\"><textarea dir=rtl name="post_head" rows="8" cols="60"onkeypress="return countIt();">'.$value.'</textarea></td></tr>';
        return $form;

    }
//---------------------------------------------------
//
//---------------------------------------------------
    function selectform($catid)
    {
        global $apt;
        $form .=  "<tr><td align=right nowrap class=\"info_bar\">ÌÃ» \n";
        $form .=  "<font color=\"#FF0000\">*</font></td>";
        $form .=  "<td align=right width=\"100%\"><select name='cat_id'>\n";
        $form .=  "<option value=''> ≈Œ — ﬁ”„</option>";
        $result = $apt->query("select * from rafia_cat where
                                catType='$catid' order by id");
        while($row=$apt->dbarray($result))
        {
            $id=$row["id"];
            $title=$row["title"];
            $form .=  "<option value='$id'>$title</option>";
        }
        $form .=  "</select></td></tr>\n";
         return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
     function selectstheme($themed)
     {
         global $apt;
        $form .=  "<tr>";
        $form .=  "<td align=right nowrap class=\"info_bar\">«·‘ﬂ· «·„” Œœ„(«· ’„Ì„)";
        $form .=  "</TD>\n";
        $form .=  "<td align=right width=\"100%\"><select name='usertheme'>";
        $form .=  "<option selected value='$themed'>$themed</option>";

        $result = $apt->query("select * from rafia_design where
                                  theme!='$themed' and usertheme='1' order by id");

        while($row = $apt->dbarray($result))
        {
            extract($row);
            $form .=  "<option value='$theme'>$theme</option>";
         }
         $form .=  "</select></td></tr>\n";

        return $form;
     }
//---------------------------------------------------
//
//---------------------------------------------------
    function selectsform($title)
    {
         global $arr_subject;
        $form .=  "<tr>";
        $form .=  "<td align=right nowrap class=\"info_bar\">$title :";
        $form .=  "<td align=right width=\"100%\"><select name=\"subject\">\n";
        while($arr_value = each ($arr_subject))
        {
            $value = $arr_value['value'];
            $form .=  "<option  value=\"$value\">$value</option>\n";

         }
        $form .=  "</td></tr>\n";
     return $form;
     }
//---------------------------------------------------
//
//---------------------------------------------------
         function editupload()
         {
             $form .=  "<tr>";
             $form .=  "<td align=right nowrap class=\"info_bar\">«·„—›ﬁ«  </td>";
             $form .= "<td align=right width=\"100%\">
             <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
              <tr>
              <td align=right class='row1' width=\"100%\">
              <table cellpadding='4' cellspacing='0' width='100%' border='0'>
              <tr>
              <td><input type='radio' name='editupload' value='keep' checked></td>
              <td align=right width='100%'><font class=fontablt> «Õ ›Ÿ »«·„·› «·„—›ﬁ</td>
              </tr>
              <tr>
              <td><input type='radio' name='editupload' value='delete'></td>
              <td align=right width='100%'><font class=fontablt>Õ–› «·„·› «·„—›ﬁ</td>
              </tr>
              <tr>
              <td align=right valign='middle'><input type='radio' name='editupload' value='new'></td>
              <td align=right width='100%'><font class=fontablt>«” »œ· »„·› „—›ﬁ ÃœÌœ<br><input type='file' size='30' name='phpfile' onClick=document.rafia.editupload[2].checked=true;></td>
              </tr>
              </table>
              </td>
             </tr>
             </table>";
            $form .=  "</td></tr>\n";
            return $form;
      }
//---------------------------------------------------
//
//---------------------------------------------------
     function yesorno($title,$name,$value = 0)
     {
           $form .=  "<tr><td align=right nowrap class=\"info_bar\"> $title </td><td align=right width=\"100%\">";
           if( $value =='0' )
           {
               $form .=  "<input  type=\"radio\" name='$name'  value='1'>  ‰⁄„";
               $form .=  "&nbsp;&nbsp;";
               $form .=  "<input type=\"radio\" name='$name'  value=\"0\" checked> ·«";

           }else{
               $form .=  "<input  type=\"radio\" name='$name'  value='1' checked>  ‰⁄„";
               $form .=  "&nbsp;&nbsp;";
               $form .=  "<input type=\"radio\" name='$name'  value=\"0\">  ·«";
               }
           $form .=  "</td></tr>";
        return $form;
     }
//---------------------------------------------------
//
//---------------------------------------------------
    function deleteform()
    {
        $form =  "<tr><td align=right nowrap class=\"info_bar\">
         Õ‹‹‹–›     </td><td align=right width=100%>
         <input type=\"checkbox\"  name=\"del\" value='1'>
         </td></tr>";

        return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function formmember($k=0)
    {
        global $apt;
        if($k =='0')
        $form =  "<tr> <td align=right width=20%><font class=fontablt>··«⁄÷«¡ ›ﬁÿ ø  &nbsp;&nbsp;<select name='formmember'><option value='no'>·«</option><option value='yes'>‰⁄„</option></select></td></tr>";
        else
        $form  =  "<tr> <td align=right width=20%><font class=fontablt>··«⁄÷«¡ ›ﬁÿ ø  &nbsp;&nbsp;<select name='formmember'><option value='".$apt->adminset($apt->row['formmember'])."'>".$apt->adminset($apt->row['formmember'])."</option><option value='yes'>‰⁄„</option><option value='no'>·«</option></select></td></tr>";
       return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function adminallow()
    {
        global $apt;
        $arr = array('yes' => '‰⁄„','wit' =>  '≈‰ Ÿ«—');
        $arrvalue = array("".$apt->row['allow']."" => "".$apt->adminset($apt->row['allow'])."");
        $arrmerge= array_unique(array_merge($arrvalue, $arr));
         $form .= "<tr> <td align=right nowrap class=\"info_bar\">
         <font class=fontablt>≈⁄ „«œ </TD>  <td align=right width=\"100%\">
         <select name='allow'>";
        while($_arr = each ($arrmerge))
        {
            $values = $_arr['value'];
            $key = $_arr["key"];
            $form .= "<option  value=\"$key\">$values</option>\n";
        }
        $form .= "</select></td></tr>";
        return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function admin_select_cat_id($catid)
    {
        global $apt;
        
        $form  =  "<tr>";
        $form .=  $this->hiddenform  ("catid", $apt->row['cat_id']);
        $form .=  "<td align=right nowrap class=\"info_bar\">
        «·«ﬁ”«„ :</TD>
        <td align=right width=\"100%\">
        <select name='cat_id'>";

        $resultf= $apt->query("select * from rafia_cat where catType='$catid' and id='".$apt->row['cat_id']."'");
        $rowf = $apt->dbarray($resultf);

        $idcat=$rowf["id"];
        $titlecat=$rowf["title"];
        $form .= "<option value='$idcat'> $titlecat</option>";


        $result = $apt->query("select * from rafia_cat where catType='$catid' AND id!='".$apt->row['cat_id']."' order by id");
        while( $row = $apt->dbarray($result))
        {
            $idcat=$row["id"];
            $titlecat=$row["title"];
            $form .=    "<option value='$idcat'> $titlecat</option>";
        }
        $form .= "</select></td></tr>";
       return $form;
    }
}
?>