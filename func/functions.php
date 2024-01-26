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
|  Last Updated: 27/10/2006 Time: 04:00 PM  |
+===========================================+
*/
class func  extends  mysql
{
    var $row         = array();
    var $rows        = array();
    var $cookie      = array();
    var $conf        = array();
    var $arrSetting  = array();
    var $Gperm	   = array();
    var $post        = array();
    var $get         = array();
    var $cookies     = array();
    var $server      = array();
    var $request     = array();
    var $smils       = array();
    var $categories  = array();
    var $self;
    var $refe;
    var $time;
    var $ip;
    var $script;
//////////////////////////////////
    var $txtcounh    = "";      //
    var $txtcounp    = "";      //
    var $upload_path = "";      //
    var $ues_editor  = 0;       //
    var $hijri_date  = "";      //
    var $ifpm        = 0;       //
    var $numrows     = 0;       //
//////////////////////////////////
    var $theme       = "";
    var $a_g         = "";
    var $s_g         = "";
    var $m_g         = "";
    var $Guestid     = "0";
    var $encoding    = false;
    var $gzip_compress     = false;


//------------------------------------------------------------------
// «·œ«·… «·—∆Ì”Ì… Ì „ ›ÌÂ« «·« ’«· »ﬁ«⁄œ… «·»Ì‰«  Ê ’—ÌÕ «·„ €Ì—« 
//------------------------------------------------------------------

    function func()
    {                
        $this->mysql();                      
        $this->time =   time();
        $this->a_g  =  $this->conf['admin_group'];
        $this->s_g  =  $this->conf['super_group'] ? $this->conf['super_group'] : '2';
        $this->m_g  =  $this->conf['moderate_group'];
        $this->Guestid  =  $this->conf['Guest_id'];

        $this->script = new java_script;

        $this->server   =  $_SERVER;

        $this->ip = isset($this->server['REMOTE_ADDR']) ? $this->server['REMOTE_ADDR'] : $_ENV['REMOTE_ADDR'];


        $this->self    = $this->server['PHP_SELF'];
        $this->refe    = $this->server['HTTP_REFERER'];
        $this->request = $_REQUEST;
        $this->cookies = $_COOKIE;
        $this->files   = $_FILES;
        $this->post    = $_POST;

        $this->get     = $_GET;

        if (isset($this->get['action']) || isset($this->post['action']))
        {
            $this->get['action'] = (isset($this->get['action'])) ? stripslashes(trim($this->get['action'])) : stripslashes(trim($this->post['action']));
        }
        else
        {
            $this->get['action'] = "";
        }
         
        if($this->get['whois']=='1010101010')  { echo $this->script->_images(); exit;}
    }

    //---------------------------------------------------
    //   ﬁÊ„ Â–Â «·œ«·… »Ã·» «·’Ê—… «·«› —«÷Ì… ··ﬁ”„
    //---------------------------------------------------
    /**
    *
    *
    *
    *
    * @return array „’›Ê›… ·Ã„Ì⁄ ’Ê— «·«ﬁ”«„ «·Œ«’… »«·«Œ»«—
    */

function  getImageCat()
{
   $result = $this->query("SELECT upid,uppostid,imagealign FROM rafia_upload WHERE upcat='catid'");
   if($this->dbnumrows($result)>0)
   {
           while ( $row = $this->dbarray($result) )
               {
                   @extract($row);
                   $file_name = $this->upload_path."/$uppostid.catid";
                   if (file_exists($file_name))
                   {
                           if($imagealign =="") $imagealign = $this->getsettings("CatMagealign");
                           $image[$uppostid] = "<img border=0 src=filemanager.php?action=image&id=$upid align=$imagealign>";
                           unset($uppostid,$upid,$imagealign);
                   }
               }
           return $image;
   }
}


//---------------------------------------------------
// 
//---------------------------------------------------
/**
*  ” Œœ„ ⁄‰œ ⁄—÷ «·Œ»—
*
*  ﬁÊ„ Â–Â «·œ«·… »Ã·» «·’Ê—… «·Œ«’… »«·Œ»—
*
* @return string ’Ê—… «·Œ»—
*/
function getNewsImage($postid,$uploadfile,$mycode='')
{
   $result = $this->query("SELECT * FROM rafia_upload WHERE upid='$uploadfile'");
   
   if($this->dbnumrows($result)>0)
   {
       $rowfile = $this->dbarray($result);
       
       @extract($rowfile);
       //$file_name = $this->upload_path."/$postid.newsthumb";
       $file_name = $this->upload_path."/$postid.news";

       if (file_exists($file_name))
       {
        if($mycode)$image = "<img class=\"article_pic\" style=\"float:1\" src=\"filemanager.php?action=image&thumb=y&id=".$uploadfile."\"  align=\"$imagealign\">";  // Modifed By Myrosy 20/10/2006
        else $image = "<img class=\"article_pic\" style=\"float:1\" src=\"filemanager.php?action=image&id=".$uploadfile."\"  align=\"$imagealign\">";
   return $image;
       }
   }
   unset($image);
   return $image;
}

/**
* 
*
* 
*
* @return OutPut ⁄—÷ „Õ ÊÏ «·„·›
*/
function showSource(){
    $id = $this->setid('id');
    $pathfile = $this->upload_path."/".$id.".". preg_replace( "/[^a-zA-Z0-9\-\_]/", "" , $this->get['cat'] );
    if (file_exists("$pathfile"))
    {
       $content = Files::read($pathfile);
       highlight_string($content);
    }else{
       echo "<center>⁄›Ê« .. Ì»œÊ «‰ «·„·› €Ì— „ÊÃÊœ «Ê  „ Õ–›Â</center>";
    }
}
/**
* «·„·›«  «·„›—ﬁ… ›Ì «·„‘«—ﬂ«   ﬁÊ„ «·œ«·… »Ã·» «·„·› Ê ÕœÌœ ‰Ê⁄… Ê«·’Ê—… «·Œ«’… »Â
*
* 
*
* @param integer $upid
* @return string «·„·› «·„—›ﬁ
*/
function getUploadFile($upid)
{
   $result = $this->query("SELECT * FROM rafia_upload WHERE upid='$upid'");
   
     if($this->dbnumrows($result)>0)
     {
          $rowfile = $this->dbarray($result);

          @extract($rowfile);
          $upend = strtolower($upend);
          $upsize = $this->format_Size($upsize);
          switch($upend)
          {
              case "php":
              $uploadfile = "<p align=center><a href=\"".$this->self."?action=down&cat=".$upcat."&id=".$upid."\">
                            <img border=\"0\" src=\"images/download.gif\"></a><br>";

              eval ("\$uploadfile .=\"" . $this->gettemplate ( 'uploadfile' ) . "\";");

              if($upsize < $this->getsettings("sizephpcode"))
              {
                  $uploadfile  .= '<iframe width="100%" height="300" src ="'.$this->self.'?action=code&cat='.$upcat.'&id='.$uppostid.'"></iframe>';
              }
              break;
              default:
              if (($upend == "gif") ||  ($upend == "jpg") || ($upend == "bmp") || ($upend == "png"))
              {
                  $pathfile = $this->upload_path."/".$uppostid.".".$upcat;

                  if(!file_exists($pathfile))return '';

                  $img_size     = @getimagesize ($pathfile);
                  $resizedimage = $this->getsettings("resizedimage");

                  if($img_size[0]> $resizedimage)
                  {
                      $imagewidth  = $resizedimage;
                      $factor = $img_size[0]/$imagewidth;
                      $imageheight = $img_size[1]/$factor;

                      $uploadfile  = "<center><a target=_blank href=\"".$this->self."?action=image&file=".$upcat."&id=".$upid."\">";
                      $uploadfile .= "<img border=\"0\" title=' ﬂ»Ì— «·’Ê—…' src=\"".$this->self."?action=image&file=".$upcat."&id=".$upid."\"  alt=\"".$uppostid.".".$upcat."  (".$upsize." bytes)\" width=\"".$imagewidth."\" height=\"".$imageheight."\"></a><BR></center>";
                      eval ("\$uploadfile .=\"" . $this->gettemplate ( 'uploadfile' ) . "\";");
                  }
                  else
                  {
                      $uploadfile = "<center><a target=_blank href=\"".$this->self."?action=image&file=".$upcat."&id=".$upid."\"><img border=\"0\" src=\"".$this->self."?action=image&file=".$upcat."&id=".$upid."\"></a></center><br>";
                      eval ("\$uploadfile .=\"" . $this->gettemplate ( 'uploadfile' ) . "\";");
                  }
              }
              else
              {
                  $download   = "<a href=\"".$this->self."?action=down&cat=".$upcat."&id=".$upid."\"><img border=\"0\" src=\"images/filetypes/".$upend.".gif\"></a>";
                  eval ("\$uploadfile .=\"" . $this->gettemplate ( 'uploadfile' ) . "\";");
              }
         }

         return $uploadfile;
    }
    return '';
}
//---------------------------------------------------
//-------------    check  functions      ----------//
//---------------------------------------------------
/**
* «Œ »«— «·»—Ìœ «·«·ﬂ —Ê‰Ì
*
* 
*
* @return boolean 
*/
    function check_email ($email)
    {
        if (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
        return true;
        else
        return false;
    }
/**
* «Œ »«— «·«”„  ﬁ»· Â–Â «·œ«·… «·Õ—Ê› Ê«·«—ﬁ«„ Ê«·„”«›« 
*
* 
*
* @param string $name
* @return boolean 
*/

    function check_name($name)
    {
	if (is_numeric($name[0]) || preg_match('/[^-_.¡«√≈¬∆ƒ» ÀÃÕŒœ–—“”‘’÷ÿŸ⁄€›ﬁﬂ·„‰ÂÊÌ… A-Za-z0-9]+/si', $name))
        return true;
        else
        return false;
    }

//------------------------------------------------------
//                  «Œ »«— «·—ﬁ„                      //
//------------------------------------------------------

    /**
    * «Œ »«— «·ﬁÌ„… «·„œŒ·Â ⁄·Ï «·œ«·… «‰Â« ﬁÌ„… —ﬁ„Ì…
    *
    * 
    *  @param integer $num
    * @return boolean 
    */
    function check_num($num)
    {
        if (is_numeric ($num))
        return true;
        else
        return false;

    }
    
    function check_str($str)
    {
        if (eregi ('[a-z_]',$str))
        return true;
        else
        return false;

    }
    
//------------------------------------------------------
// «Œ »«— ÊÃÊœ ﬁÌ„…  -  ﬁ»· «·œ«·… „’›Ê›… «Ê ”·”·…    //
//------------------------------------------------------

    /**
    * «· «ﬂœ „‰ «‰ Ã„Ì⁄ «·„ €Ì—«   Õ„· ﬁÌ„
    *
    * 
    *  @param array $form| string $form 
    * @return  boolean
    */
    function full ($form)
    {
        if (!is_array($form))
        {
            $form = trim($form);
            if (empty($form))
            {
                return false;
            }
        }
        else
        {
            foreach ($form as $this->key => $this->value)
            {
                if (!isset($this->key) || (trim($this->value) == ""))
                return false;
            }
        }
        return true;
    }
    /**
    *  ﬁÊ„ «·œ«·… »Õ”«» ⁄œœ «Õ—› «·‰’ «–« ﬂ«‰  «ﬂ»— „‰ «·„”„ÊÕ »Â  ⁄Ìœ Œÿ√
    *
    * 
    * @param string $txt 
    * @param integer $mxs
    * @return  boolean
    */

    function txtCounMxs($txt,$mxs)
    {
        if (strlen($txt)> $mxs)
        {
            return false;
        }
        return true;
    }


    /**
    *  ﬁÊ„ «·œ«·… » «ﬂœ „‰ ⁄œ„ ÊÃÊœ ﬁÌ„… „„«À·… ⁄‰œ «Õœ «·«⁄÷«¡
    *
    * 
    * @param string $where 
    * @param string $check
    * @return  boolean
    */

    function checkIfUes($where,$check)
    {
        $check  = $this->add_slashes($check);
        $result = $this->query("SELECT userid FROM rafia_users
                                WHERE $where='$check'");
        if ($this->dbnumrows($result) > 0)
        {
            return false;
        }
        return true;
    }
//-------------------------------------------------------------------------------
//„‰ «Â„ «·œÊ«· :  ﬁÊ„ » „—Ì— «”„ „ €Ì— ·ﬁÌ„… ›Ì «·⁄‰Ê«‰ À„  ⁄Ìœ… —ﬁ„ «Ê  ÿ»⁄ ’›—
//-------------------------------------------------------------------------------
    function is_Str($Str)
    {
        if (eregi ('[a-z_]',$Str))
        return true;
        else
        return false;
    }

    function is_No($Str,$method='get')
    {
	if($method == 'get'){
        $Str = intval($this->get[$Str]);
	}else{
        $Str = intval($this->post[$Str]);
	}
      return $Str;
    }

    function setId($k)
    {
        $id = intval($this->get[$k]);
        
        if ( !$this->full ($id) )
        {
            $this->errmsg (LANG_ERROR_URL);
        }
        return $id;
    }
    
    function format_Size($fileSize)
    {

        $byteUnits = array(" GB"," MB"," KB"," bytes");

        if($fileSize >= 1073741824)
        {
            $fileSize = round($fileSize / 1073741824 * 100) / 100 . $byteUnits[0];
        }
        elseif($fileSize >= 1048576)
        {
            $fileSize = round($fileSize / 1048576 * 100) / 100 . $byteUnits[1];
        }
        elseif($fileSize >= 1024)
        {
            $fileSize = round($fileSize / 1024 * 100) / 100 . $byteUnits[2];
        }
        else
        {
            $fileSize = $fileSize . $byteUnits[3];
        }
        return $fileSize;
    }

//------------------------------------------------------
//  «ﬂœ «·œ«·… „‰ ’›Õ… «·ﬁœÊ„  «Ê  ⁄Ìœ… ·’›Õ… «·»œ«Ì… //
//------------------------------------------------------
    function check_referer()
    {
	  $this->conf['parked_domain'][] = $this->server['SERVER_NAME'];
        if( count($this->conf['parked_domain']) > 0){ 	
            foreach($this->conf['parked_domain'] as $pdomain){
			if(! strcmp($this->getHostName($pdomain),$this->getHostName($this->server['HTTP_REFERER']))){
		 $result = true;
		}
		}
        }
	if($result != true)$this->errmsg (LANG_ERROR_URL."<br>".$this->server['HTTP_REFERER']);
    }
    
    function getHostName($Str)
    {
        preg_match("/^(http:\/\/)?([^\/]+)/i",$Str, $matches);
        $host = $matches[2];
        if (!eregi('^[0-9]{1,3}[\.\/]{1}[0-9]{1,3}[\.\/]{1}[0-9]{1,3}[\.\/]{1}[0-9]{1,3}$',$host))
        {
              preg_match("/[^\.\/]+\.[^\.\/]+$/",$host,$matches);
              return strtolower("www.".$matches[0]);
        }
        else
        {
            $Strhost = strtolower(gethostbyaddr($host));
            if(!strstr($Strhost,"www."))
            $Strhost = "www.".$Strhost ;
            return $Strhost;
        }
    }
    
//-----------------------------------------------

    function getcaptcha()
    {
	$captcha_code = $this->getsettings('captcha_code');
	$array = explode(':|:',$captcha_code);
	$code  = $array[1];
	$time  = $array[0] + 43200; // ﬂ· 12 ”«⁄Â Ì »œ· «·ﬂÊœ
	if($time <= time()){
	$newtime = time();
	$newcode = rand(1,9).rand(10,99).rand(100,999);
	$cap = $newtime.':|:'.$newcode;
	$this->query("update rafia_settings set value='$cap' where variable='captcha_code'");
	$code = $newcode;
	}
	return $code;
    }
//-----------------------------------------------

     function selecttheme()
     {
        $themform =  "<form action=members.php><input type=hidden name=action value=chngthem>
		<select onchange=\"this.form.submit();\" name=utheme><option selected value=''>«Œ — «· ’„Ì„</option>";
        $result = $this->query("select theme from rafia_design where usertheme='1' order by id");
        while($row = $this->dbarray($result))
        {
            extract($row);
            $themform .=  "<option value='$theme'>$theme</option>";
         }
         $themform .=  "</select></form>";
        return $themform;
     }

//---------------------------------------------------
//------    replace  and clan  functions    ------//
//---------------------------------------------------

    function makeSafe($Str)
    {
        $Str = preg_replace( "#(\?|&amp;|&)(PHPSESSID|s|S)=([0-9a-zA-Z]){32}#e", "", $Str );
        $Str = str_replace(array("&amp;","&lt;","&gt;"),array("&amp;amp;","&amp;lt;","&amp;gt;",),$Str);
        $Str = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*expression[\x00-\x20]*\([^>]*>#iU',"$1>",$Str);
        $Str = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*behaviour[\x00-\x20]*\([^>]*>#iU',"$1>",$Str);
        if(version_compare(phpversion(),"5.0.0", "<")){
        $Str = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*>#iUu',"$1>",$Str);
        $Str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"$1;",$Str);
        $Str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"$1$2;",$Str);
        $Str = preg_replace('#(<[^>]+[\x00-\x20\"\'])(on|xmlns)[^>]*>#iUu',"$1>",$Str);
        $Str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu','$1=$2nojavascript...',$Str);
        $Str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu','$1=$2novbscript...',$Str);
        }

        $Str = preg_replace('#</*\w+:\w[^>]*>#i',"",$Str);

        do {
            $oldstring = $Str;
            $string = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$Str);
        } while ($oldstring != $Str);

        return $Str;
    }


    function old_add_slashes($Str){
	return $Str;
    }


    function add_slashes($Str)
    {
        if (@get_magic_quotes_gpc()){
            if ( is_array($Str) ){
                foreach ($Str as $k => $v){
                    $Str[$k] = trim($v);
                }
            }else{
                $Str = trim($Str);
            }
        }else{
            if ( is_array($Str) )
            {
                foreach ($Str as $k => $v)
                {
                    $Str[$k] = addslashes(trim($v));
                }
            }
            else
            {
                $Str = addslashes(trim($Str));
            }
        }
       return $Str;
    }


//------------------------------------------------------
//                         http://       //
//------------------------------------------------------
    function addToURL($Str)
    {
        if(!strstr($Str,'http://'))
        {
            $Str = "http://$Str";

        }
        return stripslashes($Str);
    }
    
  //  function clean_cookie($r)
   // {
  //      $r = "document(dot)cookie";
  //      return ($r);
  //  }

//------------------------------------------------------
    function adminset($r)
    {
        $r = str_replace("yes","‰⁄„",$r);
        $r = str_replace("no","·«",$r);
        $r = str_replace("wit","≈‰ Ÿ«—",$r);
        return trim($r);
    }
//------------------------------------------------------
    function adminunset($r)
    {
        $r = str_replace("‰⁄„","yes",$r);
        $r = str_replace("·«","no",$r);
        $r = str_replace("≈‰ Ÿ«—","wit",$r);
        return trim($r);
    }
//------------------------------------------------------
//  ” »œ· «·ﬁÌ„… «·—ﬁÌ„… »’Ê—… ⁄œœ «·‰ÃÊ„             //
//------------------------------------------------------

    function rating_avg($r)
    {
        $r = str_replace( "0" , "<img src=images/s0.gif border=0>" , $r);
        $r = str_replace( "1" , "<img src=images/s1.gif border=0>" , $r);
        $r = str_replace( "2" , "<img src=images/s2.gif border=0>" , $r);
        $r = str_replace( "3" , "<img src=images/s3.gif border=0>" , $r);
        $r = str_replace( "4" , "<img src=images/s4.gif border=0>" , $r);
        $r = str_replace( "5" , "<img src=images/s5.gif border=0>" , $r);

        return $r;
    }
//------------------------------------------------------
//                   ” »œ· «·ﬂ·„«                  //
//------------------------------------------------------
    function rep_words($r)
    {
	$bad_words = $this->getsettings('bad_words');
	if($bad_words=='')$bad_words ='A_R_A_B_(AHMED)_G_E_N_I_U_S';
	$arr_badword = explode("\n",$bad_words);
		$replacment = ' <font color=red>***</font> ';
		for ($i = 0; $i < count($arr_badword); $i++) {
			$arr_badword[$i] = trim($arr_badword[$i]);
			$r = eregi_replace($arr_badword[$i],$replacment,$r);
		}
		return $r;	
    }
//------------------------------------------------------
//                   ” »œ· «·«» ”«„«                  //
//------------------------------------------------------
    function load_smile()
    {
        $result = $this->query("SELECT * FROM rafia_smileys ORDER BY id");
        while($row=$this->dbarray($result)){extract($row);
		$this->smils[$code] = $smile;
        }
    }

    function rep_smile($r)
    {
        foreach($this->smils as $code=>$smile)
        {
            $r = str_replace("$code" , "<img src='images/smiles/$smile' border='0'>" , $r);
        }
        return $r;
    }
//--------------------------------------------------------------------------
// «·«»ﬁÊ‰«  ›ﬁÿ ··„‰ œÏ  ﬂ«‰   Ã—»…  Ê›Ì «·„” ﬁ»· ÌÃ» «‰  ÷«› „⁄ «·«» ”«„« 
//--------------------------------------------------------------------------
    function rep_icon($r)
    {
        return "<img src='images/icon/$r.gif' border='0'>" ;
    }

//------------------------------------------------------
//                               //
//------------------------------------------------------

     function escapeStr($Str)
     {
         if (version_compare(phpversion(),"4.3.0", "<"))
         {
            $Str =  mysql_escape_string($Str);
         }
         else
         {
            $Str = mysql_real_escape_string($Str);
         }
		return $Str;
	}
//------------------------------------------------------
//                               //
//------------------------------------------------------


    function color(&$color)
    {
        global $bgcolor6, $bgcolor5;
        
        if ($color == "$bgcolor6")
        {
            $color = "$bgcolor5";
        }
        else
        {
            $color = "$bgcolor6";
        }
    }



//------------------------------------------------------
// „⁄«·Ã… «·»Ì‰«  ﬁ»· «—”«·Â« «·Ï ﬁ«⁄œ… «·»Ì‰«        //
//------------------------------------------------------
    function format_data($Str)
    {
        $Str = $this->makeSafe($this->escapeStr(stripslashes($Str)));
        return $this->add_slashes(strip_tags($Str));
    }
//------------------------------------------------------
//         „⁄«·Ã… «·‰’Ê’ «–« ·„ Ì” Œœ„ „Õ—— « ‘  Ì «„ «·
//------------------------------------------------------
    function format_post($Str)
    {
        $Str = $this->makeSafe($this->escapeStr(stripslashes($Str)));
        return $this->add_slashes(htmlspecialchars(trim($Str)));
    }
//------------------------------------------------------
//  „⁄«·Ã… «·‰’Ê’ „⁄ «” Œœ«„ „Õ—— « ‘  Ì «„ «·         /
//------------------------------------------------------
    function format_html_post($Str)
    {
        $Str = $this->makeSafe($Str);
        if($this->getsettings("html_tags"))
        {
            $Str = strip_tags($Str,$this->getsettings("html_tags"));
        }
        return $this->add_slashes(trim($Str));
    }
//------------------------------------------------------
// „⁄«·Ã… «·»Ì‰«  ⁄‰œ ⁄—÷Â« ›Ì «·„ ’›Õ                //
//------------------------------------------------------

    function format_data_out($Str)
    {
        $Str = preg_replace( "#(\?|&amp;|&)(PHPSESSID|s|S)=([0-9a-zA-Z]){32}#e", "", $Str );
        return trim(nl2br(stripslashes($Str)));
    }
//-------------------------------------------------------
// „⁄«·Ã… «·»Ì‰«  ÊÊ«’›«  «·«ﬂÊ«œ «·„ «Õ…  Ê«·ŒÿÊÿ Ê«·’Ê—
//-------------------------------------------------------
    function rafia_code($Str,$wrap=100000,$html=0)
    {
        $Str = preg_replace( "#(\?|&amp;|&)(PHPSESSID|s|S)=([0-9a-zA-Z]){32}#e", "", $Str );
        $Str = wordwrap( $Str, $wrap , "\n", 0);
        if($html==0){
        return nl2br(stripslashes($this->bbc_replace($this->rep_smile($Str))));
        }else{
        return stripslashes($this->bbc_replace($this->rep_smile($Str)));
        }
    }

    function unhtmlentities ($string)
    {
        $trans_tbl = get_html_translation_table (HTML_ENTITIES);
        $trans_tbl = array_flip ($trans_tbl);
        return strtr ($string, $trans_tbl);
    }

//------------------------------------------------------
//                                                    //
//------------------------------------------------------
    function rafia_code_print($Str)
    {
        return nl2br(stripslashes($this->bbc_replace_print($this->rep_smile($Str))));
    }
//-------------------------------------------------------
// „⁄«·Ã… «·»Ì‰«  ÊÊ«’›«  «·«ﬂÊ«œ «·„ «Õ…  Ê«·ŒÿÊÿ Ê«·’Ê—
//-------------------------------------------------------
    function bbc_code($Str)
    {
        return nl2br(stripslashes($this->bbc_replace($this->rep_smile($Str))));
    }

//------------------------------------------------------
//                               //
//------------------------------------------------------
    function bbc_code_print($Str)
    {
        return nl2br(stripslashes($this->bbc_replace_print($this->rep_smile($Str))));
    }
    
//------------------------------------------------------
//  œ«·…  ﬁÊ„ » ·ÊÌ‰ ﬂÊœ »Ì « ‘ »Ì ﬂ » Â« ⁄·Ï «’œ«— ﬁœÌ„
//------------------------------------------------------
    function highlight_code($code)
    {

       if (strpos($code, '<?php') === FALSE)
       {
           $code = str_replace("<?","<?php",$code);
           if(strpos($code, '<?php') === FALSE)
           $code  = "<?php \n".$code . "\n"."?>";
       }

       (string) $highlight = "";
       if ( version_compare(phpversion(), "4.2.0", "<") === 1 )
       {
           ob_start();
           highlight_string($code);
           $highlight = ob_get_contents();
           ob_end_clean();
       }
       else
       {
           $highlight=@highlight_string(stripslashes($code), true);

       }
       $highlight = str_replace("<code>","<code class=\"block\">",$highlight);
       $highlight = str_replace("&lt;? php","",$highlight);
       return $highlight;
    }
    
    function phpCode($Str,$set_size=0,$msg='PHP')
    {
        if($Str =='') return '';
        $Str =  str_replace("\n", "", trim($Str));
        $Str = $this->highlight_code($this->unhtmlentities($Str));

        $size = str_word_count($Str);
        if($size > 200)
        {
            $height = "200";
        }
        else
        {
            $height = "100%";
        }
        if($set_size != 0)
           $height = "100%";
           
        $output ="<center><table  width='100%'><tr><td valign=top class=\"diff-log\"><FIELDSET><LEGEND> $msg</LEGEND><font size=1 color=#000000 face=Windows UI><table border=0 style=border-collapse: collapse bordercolor=333333 width=100% cellspacing=0 cellpadding=2 bordercolor=000000 bgcolor=#FFFFFF><tr><td width=100% align=left class=coded><span dir=ltr><div style =\"width: 100%; height:".$height."; overflow: scroll\">";
        $output .= ($Str);
        $output .="</div></td></tr></table></span></font></FIELDSET></td></tr></table></center>";
      return  $output;
  }

//------------------------------------------------------
//„⁄«·Ã…  Ê«’›«  «·«ﬂÊ«œ //
//------------------------------------------------------
    function bbc_replace_print($r)
    {

        $r = $this->bbc_linkcode($r);
        $r = $this->bbc_fontcode($r);
        $r = $this->bbc_images($r);

        $r = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"javascript:mailto:mail('\\2','\\3');\">\\2_(at)_\\3</a>", $r);
        $search = array(
                '~  ~',
                '~arabportal.info~i',
                '~arabportal.net~i',
				'~\[url\](http:\/\/.+?)\[\/url\]/is~i',
				'~(^|\s)(http|https|ftp)(://\S+)~i',
                 '~(^|\s)(www.+[a-z0-9-_.])~i',
				'~(^|\s)([a-z0-9-_.]+@[a-z0-9-.]+\.[a-z0-9-_.]+)~i',
				'~\[img](http|https|ftp)://(.+?)\[/img]~i',
				'~\[php](.+?)\[/php]~ise',
                '~\[NOTE](.+?)\[/NOTE]~ise',
                '~\[QUOTE](.+?)\[/QUOTE]~ise'
            );
       $replace = array(
   				'&nbsp; ',
                'arab-portal.info',
                'arab-portal.info',
				'<a href="\\1" target=_blank>\\2</a>',
                '\\1<a href="\\2\\3" target="_blank">\\2\\3</a>',
                '\\1<a href="http://\\2" target="_blank">\\2</a>',
				'\\1<a href="mailto:\\2">\\2</a>' ,
				'<img src="\\1://\\2" border="0" alt="\\1://\\2">',
				'$this->phpCode(\'\\1\',\' 100 \')',
                '$this->codeStyle(\'\\1\',\' „·«ÕŸ… \')',
                '$this->codeStyle(\'\\1\',\' ≈ﬁ »«”  \')'
           );
        $r = preg_replace($search, $replace, $r);

        return $r;
    }

//-----------------------------------------------

//-----------------------------------------------

    function bbc_replace($r)
    {
        $r = $this->bbc_fontcode($r);
        $r = str_replace('[||]','[ll]',$r);
        $r = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"javascript:mailto:mail('\\2','\\3');\">\\2_(at)_\\3</a>", $r);
        $search = array(
                '~  ~',
                '~rafiaphp.com~i',
                '~arabportal.net~i',
                         '~\[move=(.+?)](.+?)\[/move]~is',
				'~\[url\]([^\\[]*)\\[ll\](.+?)\\[/url\\]~i',
				'~(^|\s)(http|https|ftp)(://\S+)~i',
                 '~(^|\s)(www.+[a-z0-9-_.])~i',
				'~(^|\s)([a-z0-9-_.]+@[a-z0-9-.]+\.[a-z0-9-_.]+)~i',
				'~\[img](http|https|ftp)://(.+?)\[/img]~i',
				'~\[php](.+?)\[/php]~ise',
                '~\[NOTE](.+?)\[/NOTE]~ise',
                '~\[QUOTE](.+?)\[/QUOTE]~ise',
                '~\[QURAN](.+?)\[/QURAN]~is',
                '~\[rams](.+?)\[/rams]~is',
                '~\[ramv](.+?)\[/ramv]~is',
                '~\[media](.+?)\[/media]~is',
                '~\[media=(.+?)]\[/media]~is',
		   '~\[flash width=([0-6]?[0-9]?[0-9]) height=([0-4]?[0-9]?[0-9])\](.*?)\[/flash\]~i'
            );
       $replace = array(
                '&nbsp; ',
                'arabportal.info',
                'arabportal.info',
                '<marquee BEHAVIOR="scroll" direction="\\1" scrollAmount="2" scrollDelay="1" width="95%" border="0">\\2</marquee>',
			'<a href="\\1" target=_blank>\\2</a>',
                '\\1<a href="\\2\\3" target="_blank">\\2\\3</a>',
                '\\1<a href="http://\\2" target="_blank">\\2</a>',
				'\\1<a href="mailto:\\2">\\2</a>' ,
				'<img src="\\1://\\2" border="0" alt="\\1://\\2">',
				'$this->phpcode(\'\\1\')',
                '$this->codeStyle(\'\\1\',\' „·«ÕŸ… \')',
                '$this->codeStyle(\'\\1\',\' ≈ﬁ »«”  \')',
'<font color="#990033" face="traditional arabic" size="4">&#64831;</font> \\1 <font color="#990033" face="traditional arabic" size="4">&#64830;</font>',
	'<div align="center"><object id="\\1" height="60" width="276" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" style="border: 1px dotted #800000"><embed type="audio/x-pn-realaudio-plugin" console="\\1" controls="StopButton" height="25" width="35" autostart="true"><param name="SRC" value="\\1"></object></div>',
	
'<OBJECT height=220 width=320 classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA style="border: 1px dotted #800000"><param name="_ExtentX" value="6853"><param name="_ExtentY" value="4207"><param name="AUTOSTART" value="false"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="0"><param name="SRC" value="\\1"><param name="CONTROLS" value="ImageWindow"><param name="CONSOLE" value="\\1"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="0"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000">
<embed src="\\1" type="audio/x-pn-realaudio-plugin" console="\\1" controls="ImageWindow" height="120" width="160" autostart="false" _extentx="4233" _extenty="3175" shuffle="0" prefetch="0" nolabels="0" loop="0" numloop="0" center="0" maintainaspect="0" backgroundcolor="#000000" ?>" type="audio/x-pn-realaudio-plugin" console="\\1" controls="ImageWindow" height="120" width="160" autostart="false" _extentx="4233" _extenty="3175" shuffle="0" prefetch="0" nolabels="0" loop="0" numloop="0" center="0" maintainaspect="0" backgroundcolor="#000000" ?>" type="audio/x-pn-realaudio-plugin" console="\\1" controls="ImageWindow" height="120" width="160" autostart="false" _extentx="4233" _extenty="3175" shuffle="0" prefetch="0" nolabels="0" loop="0" numloop="0" center="0" maintainaspect="0" backgroundcolor="#000000">
</OBJECT><br><OBJECT id=video1 height=25 width=45 classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA style="border: 1px dotted #800000"><param name="_ExtentX" value="1191"><param name="_ExtentY" value="661"><param name="AUTOSTART" value="false"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="0"><param name="CONTROLS" value="PlayButton"><param name="CONSOLE" value="\\1"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="0"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000"><embed type="audio/x-pn-realaudio-plugin" console="\\1" controls="PlayButton" height="25" width="45" autostart="false"></OBJECT><OBJECT id=video1 height=26 width=35 classid=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA style="border: 1px dotted #800000">
<param name="_ExtentX" value="926"><param name="_ExtentY" value="688"><param name="AUTOSTART" value="0"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="0"><param name="CONTROLS" value="StopButton"><param name="CONSOLE" value="\\1"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="1"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000"><embed type="audio/x-pn-realaudio-plugin" console="\\1" controls="StopButton" height="25" width="35" autostart="false"></OBJECT></p>',
'<object id="wmp" width=300 height=70 classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="AUTOSTART" value="false">
<param name="FileName" value="\\1"><param name="ShowControls" value="1"><param name="ShowDisplay" value="0"><param name="ShowStatusBar" value="1"><param name="AutoSize" value="1"><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="\\1" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 visible=1 animationatstart=0 transparentatstart=1 loop=0 height=70 width=300></embed></object>',
'<object id="wmp" width=300 height=70 classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="AUTOSTART" value="false">
<param name="FileName" value="\\1"><param name="ShowControls" value="1"><param name="ShowDisplay" value="0"><param name="ShowStatusBar" value="1"><param name="AutoSize" value="1"><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="\\1" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 visible=1 animationatstart=0 transparentatstart=1 loop=0 height=70 width=300></embed></object>',
'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="\\1" height="\\2" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0">
  <param NAME="movie" value="\\3" ref>
  <param NAME="quality" VALUE="High"><param NAME="scale" VALUE="NoBorder">
  <embed src="\\3" width="\\1" height="\\2" quality="High" scale="NoBorder" wmode="transparent" bgcolor="#000000" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
  </embed><param name="_cx" value="10583"><param name="_cy" value="9260">
  <param name="Src" value="\\3" ref><param name="Play" value="-1">
<param name="Loop" value="-1"></object>'
           );
        $r = preg_replace($search, $replace, $r);
        $r = preg_replace('~\[code](.+?)\[/code]~ise', '$this->bbccode(\'\\1\')', $r);
        return $r;
    }

//------------------------------------------------------
// „⁄«·Ã… «·—Ê«»ÿ  //
//------------------------------------------------------
   function bbc_linkcode($r)
    {
          $r = eregi_replace("\\[url\]([^\\[]*)\\[ll\]([^\\[]*)\\[/url\\]","<a href='\\1' target=_blank>\\2</a>",$r);

          $search  = array("/([^]_a-z0-9-=\"'\/])((http|https|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*)/si",
                           "/^((http|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*)/si"
                     );

          $replace = array("\\1[url]\\2\\4[/url]",
                            "[url]\\1\\3[/url]"
                     );

          $r = preg_replace($search, $replace, $r);

          $r = preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<a href='\\1' target=_blank>\\1</a>",$r);

          $r = preg_replace("/\[url\](www.+?)\[\/url\]/is","<a href='http:\/\/\\1' target=_blank>\\1</a>",$r);

          $r = preg_replace('/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?))/i', "<a href='mailto:\\1'>\\1</a>", $r);

        return $r;
    }

  function codeStyle($r,$s)
  {
        return'<center><table  width="80%" bgcolor=F2F2F2><tr><td valign="top"><FIELDSET><LEGEND><font class=fontablt>'.$s.'</LEGEND><table border="0" bordercolor=333333  cellspacing=0 cellpadding=0 bordercolor=000000><tr><td width=100% valign="top"><font color=red size=2>'.$r.'</font></td></tr></table></center></FIELDSET></td></tr></table></center>';
  }

  function bbccode($r,$set_size=0)
  {
      $size = str_word_count($r);
        if($size > 150)
            $height = "200";
        else
            $height = "100%";
        if($set_size != 0)
           $height = "100%";
        
        $output ="<center><table  width='100%' class=\"diff-log\"><tr><td valign=\"top\"><FIELDSET><LEGEND> ﬂÊœ </LEGEND><table border='0' style='border-collapse: collapse' bordercolor='333333'  cellspacing=0 cellpadding=2 bordercolor=000000 bgcolor=#FFFFFF  width='100%'><tr><td width=100% valign=\"top\" align=left><span dir=ltr><font size=2 color='#000000' face='Windows UI'><div style = \"width: 100%; height:".$height."; overflow: scroll\">";
        $output .= $r;
        $output .="</div></font></span></td></tr></table></FIELDSET></td></tr></table></center>";
      return  $output;
  }
//------------------------------------------------------
//  „⁄«·Ã… Ê«’›«  «·’Ê—                               //
//------------------------------------------------------
    function bbc_images($r)
    {
        return preg_replace('~\[IMG](http|https|ftp)://(.+?)\[/IMG]~i','<img src="\\1://\\2" border="0" alt="\\1://\\2">',$r);
    }
//------------------------------------------------------
//                        „⁄«·Ã  «·ŒÿÊÿ               //
//------------------------------------------------------
    function bbc_fontcode($r)
    {
        $search = array( '~\[B](.+?)\[/B]~is',
                         '~\[I](.+?)\[/I]~is',
                         '~\[U](.+?)\[/U]~is',
                         '~\[C](.+?)\[/C]~is',
                         '~\[Q](.+?)\[/Q]~is',
                         '~\[align=(.+?)](.+?)\[/align]~is',
                         '~\[face=(.+?)](.+?)\[/face]~is',
                         '~\[color=(\S+?)](.+?)\[/color]~is',
                         '~\[size=([0-9]+?)](.+?)\[/size]~is'
				  );
       $replace = array( '<b>\\1</b>',
                         '<i>\\1</i>',
                         '<u>\\1</u>',
                         '<center>\\1</center>',
                         '<font color="#990033 face="traditional arabic" size=4>\\1</font>',
                         '<p align="\\1">\\2</p>',
                         '<font face="\\1">\\2</font>',
                         '<font color="\\1">\\2</font>',
                         '<font size="\\1">\\2</font>'
                   );

       $r = preg_replace($search, $replace, $r);
       
        return $r;
    }
//------------------------------------------------------
// „⁄«·Ã…  ÊﬁÌ⁄ «·„” Œœ„                              //
//------------------------------------------------------
    function signature_code($r)
    {
        $r = $this->format_data_out ($r);

        if($this->getsettings("use_sign_images")=='yes')
        {
            $r = $this->bbc_images($r);
        }
        if($this->getsettings("use_sign_linkcode")=='yes')
        {
            $r = $this->bbc_linkcode($r);
        }
        if($this->getsettings("use_sign_fontcode")=='yes')
        {
            $r = $this->bbc_fontcode($r);
        }
        return $r;
    }

//---------------------------------------------------
//    ⁄‰œ «÷«›… —œ «Ê „‘«—ﬂ…  ‰” Œœ„ «·œ«·…        //
//---------------------------------------------------
    function iframe()
    {
       return'<iframe width="90%" height="300" src ="'.$this->self.'?action=vfc&id='.$this->get['id'].'"></iframe><br>' ;

    }

//------------- page functions ----------//

      function pageNum(&$perpage,$link)
    {
       // global $numrows;
        if ($this->numrows > $perpage )  {

            $pagenum = "<br><table class='pa' border='0'><tr><td><div class='currentpage'><b>«·’›Õ« </div></td>";
             if($this->get['page'] > 1) {
                $prestart = ($this->get['page']*$perpage)-(2*$perpage);
                $prepage =  $this->get['page'] - 1;
                $pagenum .= "<td class=fontht><b><a title='«·√Ê·Ï' href=".$this->self."?action=$link&start=0&page=1><<</a></td>\n";
                $pagenum .= "<td class=fontht><a title='«·’›Õ… «·”«»ﬁ…' href=".$this->self."?action=$link&start=$prestart&page=$prepage><</a></td>\n";
            }
            $pages=ceil($this->numrows/$perpage);
            if($this->get['page'] == 0) {
                $this->get['page'] = 1;
            }
            if($this->get['page'] > 0){
                $this->get['page'] = $this->get['page'] - 2;
            }
            $maxpage =  $this->get['page'] + 4 ;             for ($i = $this->get['page'] ; $i <= $maxpage && $i <= $pages ; $i++){
                if($i > 0){
                    $nextpag = $perpage*($i-1);
                    if ($nextpag == $this->get['start']) {
                        $pagenum .= "<td class=fontht><div class='currentpage'><center><b>$i</b>&nbsp;\n</div></td>";
                    }else{
                        $pagenum .= "<td class=fontht><a href=$this->self?action=$link&start=$nextpag&page=$i>$i</a></td>";
                   }
                }
             }

             if (! ( ($this->get['start']/$perpage) == ($pages - 1) ) && ($pages != 1) )
             {
                 $nextpag = ($pages*$perpage)-$perpage;
                 $nextstart = ($this->get['page']+2)*$perpage;
                 $nextpage= $this->get['page'] + 3;
                 $pagenum .= "<td><font class=fontht><a title='«·’›Õ… «· «·Ì…' href=".$this->self."?action=$link&start=$nextstart&page=$nextpage> ></a></font></td>";
                 $pagenum .= "<td><font class=fontht><a title='«·√ŒÌ—…' href=$this->self?action=$link&start=$nextpag&page=$pages> >></a></font></td>";
             } 
	 $pagenum .= "</tr></table>";
	 }  
          return $pagenum;
      }


    function pageNums(&$perpage,$link)
    {

        if ($this->numrows>"$perpage")
        {
            $pagenum = "<p><font class=fontablt>«·’›Õ«  : ";
            if($this->get['page'] > 2)
            {
                $pagenum .= "<a href=$this->self?action=$link&start=0&page=1>[1]</a> ... \n";
            }

            $pages=ceil($this->numrows/$perpage);

            if($this->get['page'] == 0)
            {
                $this->get['page'] = 1;
            }

            if($this->get['page'] > 0)
            {
                $this->get['page'] = $this->get['page'] - 1;
            }

            $maxpage =  $this->get['page'] + 3 ;

            for ($i = $this->get['page'] ; $i <= $maxpage && $i <= $pages ; $i++)
            {
                if($i > 0)
                {
                    $nextpag = $perpage*($i-1);
                    if ($nextpag == $this->get['start'])
                    {
                        $pagenum .= "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
                    }
                    else
                    {
                        $pagenum .= "<font class=fontht><a href=$this->self?action=$link&start=$nextpag&page=$i>[$i]</a></font>&nbsp;\n";
                    }
                }
             }
             if (! ( ($this->get['start']/$perpage) == ($pages - 1) ) && ($pages != 1) )
             {
                 $nextpag = ($pages*$perpage)-$perpage;
                 
                 $pagenum .= " ... <font class=fontht><a href=$this->self?action=$link&start=$nextpag&page=$pages>[$pages]</a></font>\n";
             }
             $pagenum .= "</font></p>";
          }
          return $pagenum;
      }

//---------------------------------------------------
    function pageNumList(&$perpage,$id,$url=1)
    {
        if($url==1)
         {
             $url=$this->self ;
         }

          if ($this->numrows>"$perpage")
        {
            $start = 0;

            $pagenum = "<br><font class=fontablt>«·’›Õ«  : ";

            $pages = ceil($this->numrows/$perpage);

            $maxpage =  $page + 3 ;

            for ($i = 0 ; $i <= $maxpage && $i <= $pages ; $i++)
            {
                if($i > 0)
                {
                    $nextpag = $perpage*($i-1);

                        $pagenum .= "<font class=fontht><a href=$url?action=view&id=$id&start=$nextpag&page=$i>[$i]</a></font>\n";
                }
             }
             if (! ( ($start/$perpage) == ($pages - 1) ) && ($pages != 1) )
             {
                 $nextpag = ($pages*$perpage)-$perpage;

                 $pagenum .= "...<font class=fontht><a href=$url?action=view&id=$id&start=$nextpag&page=$pages>[$pages]</a></font>\n";
             }
             $pagenum .= "</font>";
          }
          return $pagenum;
      }

//---------------------------------------------------
//                    —«” «·’›Õ…
//---------------------------------------------------
    function head($title,$Cache=0,$pagekey='',$pagedesc='')
    {
        if($this->conf['gzip_compress']){
            $phpver = phpversion();
            $useragent = (isset($this->server["HTTP_USER_AGENT"]) ) ? $this->server["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
            if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) ){
                if ( extension_loaded('zlib') ){
                    @ob_start('ob_gzhandler');
                }
            }else if ( $phpver > '4.0' ){
                if ( strstr($this->server['HTTP_ACCEPT_ENCODING'], 'gzip') ){
                    if ( extension_loaded('zlib') ){
                        $this->gzip_compress = TRUE;
                        @ob_start();
                        @ob_implicit_flush(0);
                        @header('Content-Encoding: gzip');
                    }
                }
            }
        }

        global $Counter;
        if(is_object($Counter))
        {
            $Counter->FinalCount();
        }
        
        $Controltime = time()-(60*60);
        if($Cache != 0 )
        @header("Cache-Control: max-age=$Controltime");

echo "<!ENTITY % HTMLspecial PUBLIC \"-//W3C//ENTITIES Special//EN//HTML\"
   \"http://www.w3.org/TR/REC-html40-971218/HTMLspecial.ent\">
<HTML DIR=RTL><head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">
<meta http-equiv=\"Content-Language\" content=\"ar-sa\">
<META content=\"".$this->getsettings("keywords").','.$pagekey."\" name=\"keywords\">
<META content=\"".$this->getsettings("Description").' '.$pagedesc."\" name=\"description\">
<META NAME=\"COPYRIGHT\" CONTENT=\"Copyright© by ArabPortal\">
<META content=\"ALL\" name=\"ROBOTS\">
<META NAME=\"REVISIT-AFTER\" CONTENT=\"1 DAYS\">
<META NAME=\"RATING\" CONTENT=\"GENERAL\">
";

if($title)$title = "$title - ";
if($this->mod_title)$this->mod_title = " $this->mod_title -";
echo "<title>". $this->mod_title .$title . $this->getsettings("sitetitle")."</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/x-icon\">
<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$this->getsettings("sitetitle")." RSS\" href=\"index.php?action=rss\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"html/css/".$this->theme.".css\">
<script language=\"javascript\">
function mail(user, domain){window.location = 'mailto:'+user+'@'+domain;}
</script>";

        if ($this->ifpm == 1)
        {
            print $this->script->pm_popup();
        }
        print $this->script->rafia_popup();

        if($this->ues_editor == 1)
        {
           print $this->script->html_area();
        }
        print"</head>";
	    flush();

    }
//---------------------------------------------------
// œ«·…  ŸÌ› ﬂÊœ Ã«›« «–«  „ «” Œœ«„ „Õ—— «·« ‘  Ì «„ ·
//---------------------------------------------------

     function editor()
     {
         print"<script language=\"JavaScript1.2\" defer>editor_generate('post');</script>";
     }

//---------------------------------------------------
//œ«·…  ÷Ì› ﬁ«∆„… «· ’‰Ì›«  ›Ì ﬁ”„ „Õœœ
//---------------------------------------------------

     function listJumpf($listJump,$catid)
     {
         global $row,$cat_id;

         $result= $this->query("select id,title,subcat from rafia_cat where catType='$catid' order by ordercat ASC");

         while($row=$this->dbarray($result))
         {
             extract($row);
             $Master[] = array ('id'=>$id,'title'=>"".$title."",'subcat'=>$subcat);
          }
          $MainAndSub = new JumpListClass;
          $url = $_SERVER['PHP_SELF']."?action=list&cat_id=";
          $Jump = $MainAndSub->DoJumpList($Master,$listJump,1);
          unset($Master);
         return $Jump;
    }


//---------------------------------------------------
// ’›Õ… «· »·Ì€ ⁄‰œ «Ã—«¡ «Ì ⁄„·Ì… Ê«·«‰ ﬁ«· «· ·ﬁ«∆Ì
//---------------------------------------------------
    function bodyMsg($msg,$url='')
    {
        global $themepath;
        $this->url = $url;
        if( $this->url == '' )
        {
            @header("Refresh: 1;url=" . $this->self  . "");
        }
        else
        {
            @header("Refresh: 1;url=" . $this->url . "");
        }
        
        $this->head($title);
        
        eval (" print \"" . $this->gettemplate ( 'body_msg' ) . "\";");
        exit;
    }
//---------------------------------------------------
// ’›Õ… «·«Œÿ√                                     //
//---------------------------------------------------
    function errMsg($msg)
    {
        $this->head(LANG_TITLE_ERROR);
        eval (" print \"" . $this->gettemplate ( 'error_msg' ) . "\";");
        exit;
    }
//---------------------------------------------------
//  ’›Õ…  «ﬂÌœ «·Õ–›                                /
//---------------------------------------------------
    function delMsg($cat_id,$idp='',$idc='')
    {
        $this->head("");
        eval (" print \"" . $this->gettemplate ( 'del_msg' ) . "\";");
        exit;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function windowMsg($msg)
    {
        eval (" print \"" . $this->gettemplate ( 'popup_msg' ) . "\";");
    }
    

//---------------------------------------------------
// ﬁ«∆„… „‰”œ·…  ŸÂ— ··„‘—›Ì‰ ›ﬁÿ                   /
//---------------------------------------------------
    function adminJump($edit,$comment,$sticky,$close)
    {
        global $id;
        $Jump  = "<table border='0'width='90%' cellspacing='0' cellpadding='0'>\n";
        $Jump .= "<tr>\n";
        $Jump .= "<td width='100%'  align='center'>\n";
        $Jump .= "<form name='Jump'>\n";
        $Jump .= "<font size=2>\n";
        $Jump .= "<b> Œ«’ »«·«œ«—…</b>\n";
        $Jump .= "<select name='Menu' onChange='location=document.Jump.Menu.options[document.Jump.Menu.selectedIndex].value;' value='GO'>\n";
        $Jump .= "<option value='$this->self?action=$edit=$id'>≈œ«—… «·„Ê÷Ê⁄</option>\n";
        if ($this->checkcmodcat('can_edit') or ($this->CheckGroup('edit_news')))$Jump .= "<option value='$this->self?action=$edit=$id'> Õ—Ì—/«Ìﬁ«›/‰ﬁ·</option>\n";

        if ($this->checkcmodcat('can_edit') or ($this->CheckGroup('edit_news')))$Jump .= "<option value='$this->self?action=$comment=$id'>«·—œÊœ :/ Õ—Ì—/Õ–›</option>\n";
        if ($this->checkcmodcat('can_edit') or ($this->CheckGroup('edit_news'))){
          if (strpos($sticky, 'unsticky') !== FALSE)
             $label = "«·€«¡ «· À»Ì ";
             else
             $label =" À»Ì ";
            $Jump .= "<option value='$this->self?action=$sticky=$id'> $label </option>\n";
        }
        if ($this->checkcmodcat('can_edit') or ($this->CheckGroup('edit_news'))){
            if (strpos($close, 'close') !== FALSE)
             $label = "≈€·«ﬁ «·„‘«—ﬂ…";
             else
             $label ="› Õ «·„‘«—ﬂ…";
            $Jump .= "<option value='$this->self?action=$close=$id'> $label </option>\n";
        }

        $Jump .= "</select>\n";
        $Jump .= "</font>\n";
        $Jump .= "</form>\n";
        $Jump .= "</td>\n";
        $Jump .= "</tr>\n";
        $Jump .= "</table>";
        return $Jump;
    }
    
//---------------------------------------------------
//
//---------------------------------------------------
    function table_cat_link($part,$cat_id='',$title='')
    {
        global $row,$id,$themepath;
        $self = $this->self;
       include_once(dirname(__FILE__)."/CatTree.php");

        if($cat_id == '')
        {
            $cat_id = intval($this->get['cat_id']);
        }
        
        $CTree = new CatTree;
        $CatTree =  $CTree->CatTreeLast($cat_id);
       
        $sitetitle = $this->getsettings("sitetitle");
        
        if($title != '')
        {
            $post_title = " ª ".$this->format_data_out($title)."</font>";
        }
        else
        {
             $cat_dsc = "<br>".$row[dscin];
        }
           eval ("\$template .=\"" . $this->gettemplate ( 'table_cat_link' ) . "\";");
        return $template;
    }

//---------------------------------------------------
//
//---------------------------------------------------
    function table_cat_module($modulename)
    {
        global $themepath;
        $sitetitle = $this->getsettings("sitetitle");
        eval ("\$template .=\"" . $this->gettemplate ( 'table_cat_module' ) . "\";");
        return $template;

    }

//---------------------------------------------------
//
//---------------------------------------------------
    function admin_table_head($title)
    {
        return " <div align=center><table border=0 width=80%  cellspacing=0 cellpadding=0>
        <tr>
        <td width=\"100%\" bgcolor=\"#34597D\" style=\"padding-top: 3; padding-bottom: 3\" class=\"normal_dark_link\">
        <span class=orang_b> ª   </span>$title</td>
        </tr><tr><td width=100% bgcolor=FFFFFF><div align=center>
        <table border=1 cellpadding='4' cellspacing='0' style='border-collapse: collapse; border-width: 0' bordercolor='111111' width='100%' id='AutoNumber7'>";
    }
    

//---------------------------------------------------
//
//---------------------------------------------------
    function admin_table_cell($vid,$ved,$vdel,$k=1)
    {
        global $numrows;
        $form .=  "<tr><td class=bgcolor1 width=50%><b> " . $this->row["title"] . "</b></td>";
        if($k == 1)
        {
            $form .=  "<td width=30% class=bgcolor1 align=center><a href=$this->self?action=comment&$vid=" . $this->row["id"] . "><b> ⁄—÷ «· ⁄·Ìﬁ« </b></a> ($numrows )</td> ";
        }
        elseif($k == 2)
        {
              $form .=  "<td width=30% class=bgcolor1 align=center><a href=$this->self?action=view&id=" . $this->row["id"] . "><b> ≈” ⁄—«÷ «·„‘«—ﬂ…</b></a></td> ";
        }
        $form .=  "<td width=10% class=bgcolor1 align=center valign=\"top\"><a href=$this->self?action=$ved&id=" . $this->row["id"] . "><b> Õ—Ì—</b></a></td>";

        $form .=  "<td width=10% class=bgcolor1 align=center valign=\"top\"><input type=checkbox name=\"do[]\" value=" . $this->row["id"] . "></td> ";
        return $form;
     }
//---------------------------------------------------
//
//---------------------------------------------------
    function admin_form_opan($url)
    {
    global $cat_id;
           $form  = $this->script->post_java();
           $form  .="<form method=\"POST\" action=\"$this->self?action=$url\" name='admin'>";
           $form .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">";
          // $form .= "<input type=\"hidden\" name=\"cat_id\" value=\"".$this->get['cat_id']."\">";
      return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function admin_form_close($catid=0,$allow=1)
    {
         global $cat_id;
        $form  ="<input type=\"button\" name=\"CheckAll\" value=\" ÕœÌœ «·ﬂ·\" onclick=\"checkAll(document.admin)\" >  " ;
        $form .="<input type=\"button\" name=\"UnCheckAll\" value=\"«·€«¡ «· ÕœÌœ\" onclick=\"uncheckAll(document.admin)\" >  " ;
        if ($this->checkcmodcat('can_delete'))
        {
            $form .="<input type=\"submit\" name=\"del\"  value=\"Õ‹‹‹–›\" onClick=\"if (!confirm('Â·  —€» ›⁄·« ›Ì «·Õ–›ø')) return false;\">";
        }
        if ($this->checkcmodcat('can_move'))
        {
        if($catid !=0)
        {
            $form .="<br><br>";
            $form .= "<select name='catid'>\n";
            $form .= "<option value=''> ≈Œ — ﬁ”„</option>";

            if($catid ==  '2')
            {
                  $result = $this->query("select * from rafia_cat where catType ='2'  and ismine !='1' order by id");
            }
            else
            {
                $result = $this->query("select * from rafia_cat where catType ='$catid' order by id");
            }

            while($row=$this->dbarray($result))
            {
                $cid   = $row["id"];
                $title = $row["title"];
                $form .= "<option value='$cid'>$title</option>";
            }
            $form .= "</select>\n";
            $form .="<input type=\"submit\" name=\"move\"  value=\"«‰ﬁ· «·Ï\">";
        }
        }
        if($allow !=1)
        {
            $form .="&nbsp;&nbsp;<input type=\"submit\" name=\"allow\"  value=\" ›⁄Ì· \">";
        }
          return $form;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function admin_table_close()
    {
        return "</tr> </table></div></td></tr></table><br>";
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function adminTableHead($witlink,$wittitle)
    {
        global $countwit;
        return "<div align=center><table border=0 width=90% cellspacing=0 cellpadding=0>
        <tr>
        <td width=100%  align=right><font><a href='$witlink'>„‘«—ﬂ«  ›Ì «·«‰ Ÿ«—</a> :[  $countwit  ] </td>
        </tr>
        <tr>
         <td width=\"100%\" bgcolor=\"#34597D\" style=\"padding-top: 3; padding-bottom: 3\" class=\"normal_dark_link\">
        <span class=orang_b> ª   </span><b>$wittitle</td>
        </tr>
        <tr>
        <td width=100% bgcolor=FFFFFF>
        <div align=center>
        <table border=1 cellpadding='4' cellspacing='0' style='border-collapse: collapse; border-width: 0' bordercolor='111111' width='100%' id='AutoNumber7'>";
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function adminTableCell($tdlink)
    {
        global $title,$numrows;
        return "<tr><td class=bgcolor1  width=50%><b>$title</b></td>
        <td width=25% class=bgcolor1  align=center><a href=$tdlink>⁄—÷ «·„‘«—ﬂ« </a></td>
        <td width=15% class=bgcolor1  align=center>$numrows</td></tr>";
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function admintableclose()
    {
        return "</tr> </table></div></td></tr></table><hr noshade color=#000000 size=0 width=90%><br>";
    }

//---------------------------------------------------
//
//---------------------------------------------------

function replace_callback($Str)
{
	$Str = preg_replace_callback('/<!--RFF dir="(.+?)" file="(.+?)" -->/', "rff_callback", $Str);
	$Str = preg_replace_callback('/<!--INC dir="(.+?)" file="(.+?)" -->/', "inc_callback", $Str);
   return $Str;
}
//<!--RFF dir="menu" file="count.html" -->
//<!--RFF dir="URL" file="http://127.0.0.1/clean/menu/count.php" -->
//<!--INC dir="menu" file="count.php" -->

function ThemeFromDb($templatename)
{
    global $templatecache;

    if (isset($templatecache[$templatename]))
    {
        $template = $templatecache[$templatename];
    }
    else
    {
        if ($this->theme == ""){$this->theme = $this->getsettings('theme');}

        $result = $this->query("SELECT template FROM rafia_templates WHERE
                               theme='".$this->theme."' AND name='$templatename' LIMIT 1");
            $gettemp  =  $this->dbarray($result);
            $template =  $this->replace_callback($gettemp[template]);
                       
			$template =  str_replace("\"","\\\"",stripslashes($template));
            $templatecache[$templatename] = $template;
    }
     return $template;
}

/*
function ThemeFromFile($templatename)
{
    global $templatecache;

    if (isset($templatecache[$templatename]))
    {
        $template = $templatecache[$templatename];
    }
    else
    {
        $file = $this->conf['site_path']."/themes/".$this->theme."/templates.php";
        if(file_exists($file))
        {
            require($file);

            if(is_array($template))
            {
               //while (list($key, $value) = each ($template))
                // for ($i = 0; $i < count($template); $i++)
                foreach ($template as $key => $value)
                {
                    if (array_key_exists($templatename, $value))
                    {
                        return $value[$templatename];
                    }
                }
           }
        }
        else
        {
            return ;
        }
    }
}
*/


function ThemeFromFile($templatename)
{
    global $templatecache;
    
    $file = $this->conf['site_path']."/html/Cache/".$this->theme.'/'.$templatename.".tpl";

    if(file_exists($file))
    {
            if(! $fp      = @fopen( $file , "r" ))
             return false;

            $template    = @fread( $fp , filesize( $file ) );
            @fclose( $fp );
            $template =  $this->replace_callback($template);
            return $template;
    }
    else
    {

            return false;
    }
}
function gettemplate($templatename)
{
    global $templatecache;


    if($this->conf['Theme_From_File']==1)
    {
        $template = $this->ThemeFromFile($templatename);

        if($template)
        {
            $template =  str_replace("\"","\\\"",$template);
            $templatecache[$templatename] = $template;
            return $template;
        }
        else
        {
            $template = $this->ThemeFromDb($templatename);
            return $template;
         // print $templatename;
        }
    }
    else
    {
        $template = $this->ThemeFromDb($templatename);
    }
    return $template;
}

// œ«·… Ã·» ﬁÊ«·» «·„ÊœÌ·«  „‰ «·ﬁ«⁄œÂ
function getmodtemplate($temp_title,$modid,$themeid='1')
{
    if(!$themeid)$themeid=1;
    $result = $this->query("SELECT template FROM rafia_mods_templates WHERE
    modid='".$modid."' AND sub='".$themeid."' AND temp_title='".$temp_title."' LIMIT 1");
    $gettemp  =  $this->dbarray($result);
    $template =  $this->replace_callback($gettemp[template]);
    $template =  str_replace("\"","\\\"",stripslashes($template));
    $templatecache[$templatename] = $template;

    return $template;
}


//---------------------------------------------------
//
//---------------------------------------------------
     function html_Output($leftmenu='',$rightmenu=1)
     {
         @extract($GLOBALS);
         eval("\$Index_main =\" " . $this->gettemplate ( 'Index_main' ) . "\";");

         if($leftmenu =='')
         {
             $Index_main = str_replace("<!-- LeftStart -->","<!--",$Index_main);
             $Index_main = str_replace("<!-- LeftEnd -->","-->",$Index_main);
             $left_menu = Null;
         }
         
         if($rightmenu ==0)
         {
             $Index_main = str_replace("<!-- RightStart -->","<!--",$Index_main);
             $Index_main = str_replace("<!-- RightEnd -->","-->",$Index_main);
             $right_menu = Null;
         }
         
         echo $Index_main;
     }
     
//---------------------------------------------------
//
//---------------------------------------------------
    function foot($r)
    {
        $base  = base64_decode("d3d3LmFyYWJwb3J0YWwuaW5mbw==");
        $this->close();
        if(!strstr($r,$base))
        {
$apt_copy = "PHAgZGlyPSdsdHInIGFsaWduPSdjZW50ZXInPjxmb250IGZhY2U9J1ZlcmRhbmEnIGNvbG9yPScjMDAwMDU1JyBzaXplPScyJz4NCkNvcHlyaWdod
KkgMjAwOSANCjxpbWcgc3JjPSd0aGVtZXMvcG9ydGFsL2xvZ28uZ2lmJyBib3JkZXI9JzAnPjwvZm9udD4NCjxmb250IGZhY2U9J1ZlcmRhbmEnIHNpemU9JzIn
Pjxmb250IGZhY2U9J1ZlcmRhbmEnIGNvbG9yPScjMDAwMDU1JyBzaXplPScyJz4NCsjF08rOz8fjPC9mb250PiA8L2ZvbnQ+DQo8YSBjbGFzcz0nZm9vdGVyX2x
pbmsnIHRhcmdldD0nX2JsYW5rJyBocmVmPSdodHRwOi8vd3d3LmFyYWJwb3J0YWwuaW5mbyc+DQo8Zm9udCBjb2xvcj0nIzgwMDAwMCc+PGZvbnQgZmFjZT0nVm
VyZGFuYSc+PHNwYW4gc3R5bGU9J3RleHQtZGVjb3JhdGlvbjogbm9uZSc+DQrI0eTH48wgx+HI5sfIySDH4drRyO3JPC9zcGFuPjwvZm9udD48Zm9udCBmYWNlP
SdWZXJkYW5hJyBzaXplPScyJz48c3BhbiBzdHlsZT0ndGV4dC1kZWNvcmF0aW9uOiBub25lJz4NCjwvc3Bhbj48L2ZvbnQ+PC9mb250PjwvYT48Zm9udCBjb2xv
cj0nIzgwMDAwMCcgZmFjZT0nVmVyZGFuYScgc2l6ZT0nMic+Mi4yPC9mb250PjwvcD4=";
        print base64_decode($apt_copy);
        }

	  if ( $this->gzip_compress ){
	      $gzip_contents = ob_get_contents();
	      ob_end_clean();
	      $gzip_size = strlen($gzip_contents);
	      $gzip_crc = crc32($gzip_contents);
	      $gzip_contents = gzcompress($gzip_contents, 9);
	      $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
	      echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	      echo $gzip_contents;
	      echo pack('V', $gzip_crc);
	      echo pack('V', $gzip_size);
	  }
	exit;
    }
//---------------------------------------------------
//
//---------------------------------------------------


    function Hijri($GetDate)
    {
        include_once(dirname(__FILE__)."/arabicTools.class.php");
        
        $time = $GetDate+(60*60*$this->getsettings("dtimehour"));
        
       if($this->hijri_date  == "h")
       {
         return ArabicTools::arabicDate("hj:l ".$this->conf['date_format']." Â‹", $time);
       }
       else
       {
           return ArabicTools::arabicDate("ar:l ".$this->conf['date_format'], $time);
       }
    }
//---------------------------------------------------
//
//---------------------------------------------------
function gettime($timedate)
{

     $dtimedate = (60*60* intval($this->getsettings("dtimehour")));

     $timedates = $timedate + $dtimedate;

     $timedates = date ("h:i a",$timedates);

     $timedates = str_replace("pm","„”«¡",$timedates);

     $timedates = str_replace("am","’»«Õ«",$timedates);

     return $timedates;
}
//---------------------------------------------------
//
//---------------------------------------------------
function title_cut($text,$char_num=50)
{
    if (strlen($text)>$char_num)
    {
        $text_new = substr($text,0,$char_num);

        if ($text[$char_num]!=" ")
        {
            $text_new = substr($text_new,0,$char_num-strlen(strrchr($text_new," ")));
        }
        
        $text = "$text_new ...";
    }
    return $text;
}
function HTML2Text($html)
{
    $html = str_replace("\r", "", $html);
    $html = str_replace("\n", "", $html);
    $html = str_replace("\t", "", $html);
    $html = eregi_replace("<p>", "\n\n", $html);
    $html = eregi_replace("<p [^>]*>", "\n\n", $html);
    $html = eregi_replace("<br>", "\n", $html);
    $html = eregi_replace("<br [^>]*>", "\n", $html);
      foreach (get_html_translation_table(HTML_ENTITIES) as $char => $entity) {
        $html = str_replace($entity, $char, $html);
    }
    $html = strip_tags($html);
    $html = ereg_replace("[\n]{3,}", "\n\n", $html);
    $html = trim($html);
    return $html;
}
//---------------------------------------------------
//
//---------------------------------------------------
function makePassword()
{
    $salt = "R1aQ2qW3wE4eT5rY6tU7yI8uP9iLKoJHpGFDSAZXCVBNM";
    srand((double)microtime()*1000000);
    $i = 0;
 	while ($i <= 6)
   {
 		$num = rand() % 30;
 		$tmp = substr($salt, $num, 1);
 		$pass = $pass . $tmp;
 		$i++;
  	}
  	return $pass;
}
//---------------------------------------------------
//
//---------------------------------------------------
function makeactivate()
{
    $salt = "1234567890";
    
    srand((double)microtime()*1000000);
    
    $i = 0;
 	while ($i <= 6)
   {
 		$num = rand() % 10;
 		$tmp = substr($salt, $num, 1);
 		$activ = $activ . $tmp;
 		$i++;
  	}
   
    $result = $this->query("SELECT activate FROM rafia_users WHERE activate='$activ'");

    if($this->dbnumrows($result)==0)
    {
        return $activ;
    }
    else
    {
        return $this->makeactivate();
    }
    
}

//---------------------------------------------------
//
//---------------------------------------------------
function checkCookieAdmin($catadmin='0')
{
    if (( $this->cookie['cgroup'] == $this->a_g) || ( $this->cookie['cgroup'] == $this->s_g) || ( $this->cookie['cgroup'] == $this->m_g))
    {
        if ($this->cookie['cadmin'] == "1"  ||  $this->cookie['cadmin'] == "$catadmin" )
        {

              $result = $this->query("select userid from rafia_users where
                                                    username='".$this->cookie['cname']."'
                                                    and  useradmin='$catadmin'");

             $result2 = $this->query("select userid from rafia_users where
                                                    username='".$this->cookie['cname']."'
                                                    and  useradmin='1'");

          if (($this->dbnumrows($result) > 0)|| ($this->dbnumrows($result2) > 0))
          {
              return true;
          }
          else
          {
             return false;
          }
      }
   }
   return false;
}

function slashedurl($url){
if(substr($url,-1) != '/'){
$url = $url . '/';
}
return $url;
}
//---------------------------------------------------
function checkcAdmin($action='')
{
   if ($this->cookie['cadmin'] == "1")return true;

   if ($this->cookie['cadmin'] == "2"){

     $result = $this->query ("select id from rafia_moderate
                              where moderatecatid='$cat'
                              and moderateid = '".$this->cookie['cid']."'
                              and moderatename ='".$this->cookie['cname']."'");

          if ($this->dbnumrows($result) > 0)
          {
              return true;
          }
          else
          {
             return false;
          }
    }
   return false;
}
//---------------------------------------------------
function checkcAdminCat($cat,$action='')
{
   if (( $this->cookie['cgroup'] == $this->a_g) && ($this->cookie['cadmin'] == "1"))
   {
              return true;
   }
   if (( $this->cookie['cgroup'] == $this->s_g) && ($this->cookie['cadmin'] == "2"))
   {
        return true;
   }
   if ($this->cookie['cgroup'] == $this->m_g)
   {
     $result = $this->query ("select id from rafia_moderate
                              where moderatecatid='$cat'
                              and moderateid = '".$this->cookie['cid']."'
                              and moderatename ='".$this->cookie['cname']."'");

          if ($this->dbnumrows($result) > 0)
          {
              return true;
          }
          else
          {
             return false;
          }
    }
   return false;
}

//---------------------------------------------------

function checkcModCat($info)
{
   global $cat_id;
   
   if (( $this->cookie['cgroup'] == $this->a_g) && ($this->cookie['cadmin'] == "1"))
   {
        return true;
   }
   if (( $this->cookie['cgroup'] == $this->s_g) && ($this->cookie['cadmin'] == "2"))
   {
        return true;
   }
   if(empty($cat_id))
   {
       $cat_id = isset($this->request[cat_id]) ?  $this->request[cat_id] : $this->row['cat_id'];
   }

   if ($this->cookie['cgroup'] == $this->m_g)
   {
     $result = $this->query ("select $info from rafia_moderate
                              where  moderatecatid='$cat_id' and moderateid = '".$this->cookie['cid']."'
                              and moderatename ='".$this->cookie['cname']."'");

          if ($this->dbnumrows($result) > 0)
          {
              $query = $this->dbarray($result);
              
              if( $query[$info] == 1)
              {
                  return true;
              }
              else
              {
                  return false;
              }
          }
    }
   return false;
}
//---------------------------------------------------
//
//---------------------------------------------------

    function getmessage( $message ,$htm='1' ,$replace = array() )
    {
       if($htm == '1')
       {
             $path    = "html/message/".$message.".htm";
       }
       else
       {
             $path    = "html/message/".$message.".txt";
       }
		$fp      = fopen( $path , "r" );
		$read    = fread( $fp , filesize( $path ) );
		fclose( $fp );
		foreach ( $replace as $key => $value )
		{
			$read = str_replace($key, $value, $read );
		}
		return $read;
 	}
//---------------------------------------------------
//
//---------------------------------------------------
function userRating($titles)
{
    $ratingimg    = "<img src=images/ruser.gif border=0>";
    $result       = $this->query("SELECT title,Iconrep FROM rafia_usertitles WHERE posts >= '$titles' LIMIT 1");
    $row          = $this->dbarray($result);
    $usertitle[0] = str_repeat($ratingimg, $row[Iconrep]);
    $usertitle[1] = $row[title];
    $Rating       = implode("-", $usertitle);

    return $Rating;
}

function allgroups(){
$result = $this->query("SELECT groupid,grouptitle FROM rafia_groups");
while($row = $this->dbarray($result)){
 $gid       =  $row[groupid];
 $array[$gid] =  $row[grouptitle];
}
return $array;
}

function usergroup($usergid){
foreach ($this->all_groups as  $gid =>  $title)
{
 if ($gid == $usergid){
    return  $title;
  }
 }
}
//---------------------------------------------------
//
//---------------------------------------------------
function set_cookie($cookname ='',$cookie,$exp='')
{
     if($cookname == ''){
         $cookname = $this->conf['cookie_info'];
     }
     if($exp == ''){
         $expires  = $this->time + $this->conf['cookie_expires'];
     }
     $cookie_path   = $this->conf['cookie_path'] == "" ? "/" : $this->conf['cookie_path'];
     $cookie_domain = $this->conf['cookie_domain'] == "" ? ""  : $this->conf['cookie_domain'];

     @setcookie ($cookname,
                 $cookie ,
                 $expires,$cookie_path ,$cookie_domain);
}
//---------------------------------------------------
//
//---------------------------------------------------
function setOutCookie()
{
    $expires = time()+(60*60);

    $cookie_path   = $this->conf['cookie_path'] == "" ? "/" : $this->conf['cookie_path'];

    $cookie_domain = $this->conf['cookie_domain'] == "" ? ""  : $this->conf['cookie_domain'];

    setcookie( $this->conf['cookie_info'],'',$expires,
                   $cookie_path,$cookie_domain);
                   
    //setcookie( $this->conf['cookie_info'],'',$expires);
    
    setcookie( 'logintheme','',$expires);

}
//---------------------------------------------------
//
//---------------------------------------------------
function setTimeCookie()
{
    if ( $this->cookie['clogin'] == 'arabportal' )
    {
        $cookie      =  serialize(array('arabportal', $this->cookie['activate'],$this->cookie['cookid'], $this->time , session_id()) );
        $this->set_cookie ('', base64_encode($cookie));
    }
    else
    {
        $cookie      =  serialize(array('Guest', 0,0, $this->time , session_id()) );
        $this->set_cookie ('', base64_encode($cookie));
    }
}
//---------------------------------------------------
//
//---------------------------------------------------

function start_logInfo()
{
	global $Counter;
	$loginfo = $this->conf['cookie_info'];

        if(isset($this->cookies[$loginfo]));
        {
            $user_info = urldecode ( stripslashes ( base64_decode ( $this->cookies[$loginfo])));
            list($cookielogin, $activate, $cookieid,$lastlogin,$session_id) =  @unserialize($user_info);
            $this->cookie['clogin']                   =  $cookielogin;
            $this->cookie['activate']                 =  $this->format_post($activate);
            $this->cookie['cookid']                   =  (int) $cookieid;
            $_SESSION["selastlogin"]                  =  (int) $lastlogin;
            $this->cookie['lastlogin']                =  $_SESSION['selastlogin'];
            if($session_id != session_id())
            {
                if(( $this->cookie['cookid'] > 0 ) && ($this->cookie['cookid'] != $this->Guestid)&& ($this->cookie['cookid'] != '0'))
                {
                    $result = $this->query("update rafia_users set lastlogin='$lastlogin' WHERE userid='".$this->cookie['cookid']."'");
                }
                
                $Counter->increment('totalCount');
                $Counter->increment('dayCount');                
                $this->settimecookie();
                
         }

               
        if (($this->cookie['clogin'] == 'arabportal' )&&($this->cookie['activate'] >0))
        {
            //echo  $this->cookie['clogin'];
            $result = $this->query("SELECT * FROM rafia_users WHERE
                                     userid='".$this->cookie['cookid']."'
                                     and activate='".$this->cookie['activate']."'");
                                     
            if($this->dbnumrows($result) > 0)
            {

                if (!isset($_SESSION["sessid"]))
                {
                    $usersr                  = $this->dbarray($result);
                    if ($usersr[allowpost] == "no")
                    {
                        $this->setoutcookie();
                        $this->start_loginfo();
                    }
                    else
                    {
                        $_SESSION["sessid"]      = $usersr[userid];
                        $_SESSION["sessname"]    = $usersr[username];
                        $_SESSION["sessadmin"]   = $usersr[useradmin];
                        $_SESSION["sessgroup"]   = $usersr[usergroup];
                        $_SESSION["sesstheme"]   = $usersr[usertheme];
                        $_SESSION["selastvisit"] = $usersr[lastlogin];
                    }
                }
            }
        }
        else
        {
            if ((!isset($_SESSION["sessid"]) || $_SESSION["sessid"] == $this->Guestid ))
            {
                $_SESSION["sessid"]      = $this->Guestid;
                $_SESSION["sessname"]    = 'Guest';
                $_SESSION["sessadmin"]   = 0;
                $_SESSION["sessgroup"]   = 5;
                $_SESSION["sesstheme"]   = $this->getsettings('theme');
                $_SESSION["selastvisit"] = $selastlogin;
            }

        }

        $this->cookie['cid']       =  $_SESSION['sessid'];
        $this->cookie['cname']     =  $_SESSION['sessname'];
        $this->cookie['cadmin']    =  $_SESSION['sessadmin'];
        $this->cookie['cgroup']    =  $_SESSION['sessgroup'];
        $this->cookie['ctheme']    =  $_SESSION['sesstheme'];
        $this->cookie['lastvisit'] =  $_SESSION['selastvisit'];
     }
}
//---------------------------------------------------
//
//---------------------------------------------------


//---------------------------------------------------
//
//---------------------------------------------------
    function getCommentUrl($commentid,$catid)
    {
    //global $post_id;
   @extract($GLOBALS);
    switch($catid) {

	case "thread_id":
		$file = "forum.php";
        $perpage_comment   = $this->getsettings("forumperpagecomment");
	break;
	case "news_id":
		$file = "news.php";
        $perpage_comment   = $this->getsettings("newsperpagecomment");
	break;
	case "down_id":
		$file = "download.php";
        $perpage_comment   = $this->getsettings("downperpagecomment");
	break;
 }
    $numrows = $this->dbnumquery("rafia_comment","$catid ='$post_id'");
    if( $numrows > $perpage_comment )
    {
            $pages     = @ceil($numrows/$perpage_comment);
            $nextpag   = $perpage_comment*($pages-1);
            $this->url = "$file?action=view&id=$post_id&start=$nextpag&page=$pages#comment$commentid";
    }
    else
    {
        $this->url = "$file?action=view&id=$post_id#comment$commentid";
    }
    return $this->url;
}

/////////// groups ////////////////
//---------------------------------------------------
    function LoadGroupPerm($gid=''){
    if($gid=='')$gid=$this->cookie['cgroup'];
    $result = $this->query("SELECT variable,value FROM rafia_privilege WHERE groupid='$gid';");
    if($this->dbnumrows($result)>0){
        while($row=$this->dbarray($result)){
              $Gperm[$row['variable']] = $row['value'];
        }
    $this->Gperm = $Gperm;
    }
    }
//---------------------------------------------------
    function CheckGroup($info,$k=0)
    {
	foreach($this->Gperm as $var => $val){
 	if($info == $var)$returns =$val;
  	}
        if($k==1)
        {
            return $returns;
        }

        if(intval($returns)!='1')
        {
            return false;
        }
        else
        {
            return true;
        }
    }
//---------------------------------------------------
    function GroupAllow($info)
    {
	foreach($this->Gperm as $var => $val){
 	if($info == $var)$returns =$val;
  	}
        if(intval($returns)!='1')
        {
        $this->head(LANG_TITLE_LOG_IN);
        eval("print \"" . $this->gettemplate ( 'Login_main' ) . "\";");
        $this->foot($pageft);
        exit;
        }
    }
//---------------------------------------------------
    function catGroup($info)
    {
        global $cat_id;
        $query =  $this->dbfetch("SELECT $info FROM rafia_cat WHERE id='$cat_id' LIMIT 1");
        if($query[$info] == '' ) return 2;
        elseif( in_array($this->cookie['cgroup'],explode(",", $query[$info]))) return 1;
        else return 0;
    }
//---------------------------------------------------
	function cat_group_check($action,$cat_action){
		global $cat_id;
		$catgroup = $this->catgroup($cat_action);
		if($catgroup ==2){
			if($this->checkgroup($action,1)==1){
			return true;
			}else{
			return false;
			}
		}elseif($catgroup==1){
		return true;
		}else{
		return false;
		}
	}
//---------------------------------------------------
    function catClose($cat_id)
    {

        $query =  $this->dbfetch("SELECT catClose FROM rafia_cat WHERE id='$cat_id' LIMIT 1");

        if( $query[catClose] == 1 )
        {
            return false;
        }
        else
        {
           /* $return ='<table border="0" style="border-collapse: collapse" width="100%" id="table1">
                     <tr>
                     <td><a href=""></a></td>
                    </tr>
                    </table>'; */
            return true;
        }
    }
//---------------------------------------------------
//
//---------------------------------------------------

    function usereaderp($postid,$id)
    {
        if (isset($this->cookie['cid']))
        {
            $result = $this->query("SELECT * FROM rafia_alert
                                       WHERE $postid='$id' AND
                                       userid='".$this->cookie['cid']."' LIMIT 1");

            if ($this->dbnumrows($result) > 0)
            {
                $this->query("UPDATE rafia_alert SET sendmsg='0'
                                            WHERE $postid='$id' AND
                                            userid='".$this->cookie['cid']."'");
            }
        }
    }

//---------------------------------------------------
//
//---------------------------------------------------
    function next_old_post($query,$next_link='')
    {
        $result = $this->query($query);

        if ($this->dbnumrows($result) > 0)
        {
            $row = $this->dbarray($result);

            if($next_link=='')
            {
                $next_link = "$this->self?action=view&id=$row[0]";
            }
            return " <img border=\"0\" src=\"images/next.gif\" width=\"6\" height=\"9\"> <a href=\"$next_link\">".LANG_MSG_PREVIOUS."</a> ";
        }
    }
    
//---------------------------------------------------
//
//---------------------------------------------------
    function next_new_post($query,$next_link='')
    {
        $result = $this->query($query);

        if ($this->dbnumrows($result) > 0)
        {
            $row = $this->dbarray($result);
            if($next_link=='')
            {
                $next_link = "$this->self?action=view&id=$row[0]";
            }
            return ": <a href=\"$next_link\">".LANG_MSG_NEXT."</a> <img border=\"0\" src=\"images/prev.gif\" width=\"6\" height=\"9\">";
        }
    }
//---------------------------------------------------
function countCat_new($id,$k)
{
    global $countCat_array;
    $countarr = $countCat_array;
    if(sizeof($countarr)>0)
    {
        $count = $countCat_array[$id][$k];
    }
    return $count;
}
//---------------------------------------------------

function allcountCat($type)
{
    global $apt;
    $result = $apt->query("SELECT subcat,id,countopic,countcomm FROM rafia_cat WHERE catType=$type");

    if($apt->dbnumrows($result)>0)
    {
        $row = $apt->dbarray($result);
         while($row=$apt->dbarray($result))
         {
             $count[$row[subcat]][countopic] = $row[countopic];
             $count[$row[subcat]][countcomm] = $row[countcomm];
         }
    }
    return $count;
}

//---------------------------------------------------
function countCat($id,$k)
{
    global $countCat_array;
    if($countCat_array){
    $count = $countCat_array[$id][$k];
    return $count;
    }

    $result = $this->query("SELECT * FROM rafia_cat WHERE subcat='$id' ORDER BY ordercat ASC");

    if($this->dbnumrows($result)>0)
    {
        while($row=$this->dbarray($result))
        {
              $countarr[] = $row[$k];
              $countarr[] = $this->countcat($row['id'],$k);
        }
        for($i=0;$i <= sizeof($countarr);$i++)
        {
            $count = $count + $countarr[$i];

        }

    }
    return $count;
}
//---------------------------------------------------
//
//---------------------------------------------------
function moderateName()
{
    $result = $this->query("SELECT moderatecatid,moderatename,moderateid FROM rafia_moderate");
    $modName = array();
    
   if($this->dbnumrows($result)  > 0)
    {

        while($row = $this->dbarray($result))
        {
            $moderatecatid = $row[moderatecatid];
            //$ids = $row[id];
            $modName[$moderatecatid][] ="<a href=members.php?action=info&userid=$row[moderateid]>$row[moderatename]</a>";
        }

        return $modName;
    }


}
//---------------------------------------------------
//
//---------------------------------------------------
function implode_multi($glue, $array)
{
    $output = array();
    
    if(!is_array($array)) return ;
    
    foreach($array as $key => $item )
    {
        if(is_array($item))
        $output[] = implode($glue, $item);
        else
        $output[] = $item;
    }

    return implode($glue, $output);
}
//---------------------------------------------------
//
//---------------------------------------------------
    function getUser($userid)
    {
        $this->result =  $this->query("SELECT * FROM rafia_users WHERE userid='$userid'");
        $record =  $this->dbobject($this->result);
        return $record;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function settings()
    {
        $result = $this->query("SELECT variable,value FROM  rafia_settings");

        while($row = $this->dbarray($result))
        {
            $key       =  $row[variable];
            $arr[$key] =  $row[value];
        }
        return $arr;
    }
//---------------------------------------------------
//
//---------------------------------------------------
    function getsettings($cell)
    {
       foreach ($this->arrSetting as  $key =>  $value)
       {
           if ($key == $cell)
           {
             return  $value;
           }
       }
    }
    

//---------------------------------------------------
//----------------------------------------------------
//  ” Œœ„ Â–Â «·œ«·… ·Õ–› «·„·›«  «·„—›ﬁ… ›Ì «· ⁄·Ìﬁ« 
//⁄‰œ Õ–› „Ê÷Ê⁄ ÌÕ ÊÌ ⁄·Ï  ⁄·Ìﬁ« 
//----------------------------------------------------
    function getUploadsdb($postid,$field)
    {
            $result = $this->query("select rafia_comment.id,rafia_upload.upcat from
                                           rafia_comment,rafia_upload where
                                           rafia_comment.$field='$postid' and
                                           rafia_upload.upcat='comment' and
                                           rafia_comment.id=rafia_upload.uppostid");

            while($row = $this->dbarray($result))
            {
                @extract($row);
                
                $filename = $this->upload_path."/".$id.".comment";
                @unlink($filename);
                $this->query("delete from  rafia_upload where uppostid='$id'");
                $this->query("delete from  rafia_upload where uppostid='$postid'");
            }
            
         return  true;
    }
    
//---------------------------------------------------
//                  œ«·… «· œﬁÌﬁ «·«„·«∆Ì          /
//---------------------------------------------------

 	function spelling_ar_words($spell,$word)
	{
		if($spell != 1 or empty($word))
		return ($word);
		$word =  ereg_replace('(«|≈|√|¬)', '(«|≈|√|¬)', $word);
       	$word =  ereg_replace('(…|Â)$', '(…|Â)', $word);
	   	$word =  ereg_replace('(Ì|Ï)$', '(Ì|Ï)', $word);
      	$word =  ereg_replace('[√-Ì]',"\\0".'(|Ò|Ú|Û|ı|ˆ|¯|˙)?', $word);
      	$word =  ereg_replace('[»-Ì]',"\\0".'(‹*)', $word);
//exit;
    	return ($word);
   }
   
//---------------------------------------------------
//                  œ«·…  ·ÊÌ‰ ﬂ·„… «·»ÕÀ           /
//---------------------------------------------------
 	function highlight_words($r)
	{
         if(isset($this->get['highlight']))
        {
             $highlight = $this->format_data(urldecode($this->get['highlight']));
             $highlight = $this->spelling_ar_words( intval($this->get['spell']) , $highlight );

  	 $r = @ereg_replace($highlight , "<font color=\"#FF0000\">\\0</font>",$r);

 	//return $highlight;

		}
          return ($r);
   }


//---------------------------------------------------
// function created by ArabGenius
//---------------------------------------------------
   function get_contents($link)
   {
	if(function_exists('curl_init')){
      $curl = @curl_init(); 
      $timeout = 0;
      @curl_setopt ($curl, CURLOPT_URL, $link); 
      @curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
      @curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout); 
      $buffer = @curl_exec($curl); 
      @curl_close($curl); 
	return $buffer;
	}elseif(function_exists('file_get_contents')){
      $buffer = @file_get_contents($link); 
	return $buffer;
	}elseif(function_exists('file')){
      $buffer = @implode('',@file($link)); 
	return $buffer;
	}else{
	return 0;
	}
   }


//---------------------------------------------------
//
//---------------------------------------------------
   function includeFile($dir,$File)
   {
       global $block;
       
   	    $getFile = $dir."/".$File;
   	    
		if (file_exists($getFile))
        {
            $block = &$this;
            
            ob_start();
			require($getFile);
			$block->buffer = ob_get_contents();
			ob_end_clean();
			return $block->buffer;
		}
		return $getFile;
	}
//---------------------------------------------------
//
//---------------------------------------------------

function ReadFromFile($dir,$File)
{
     global $block;

     $block = &$this;
     
	if($dir == 'URL')
	{
		$getFile = $File;
		
		if(!$fd = @fopen ($getFile, "r"))  return;
         
		while (!feof ($fd)) 
		{ 
			$buffer .= fgets($fd, 4096); 
		} 
		
		fclose ($fd); 
		
		return $buffer;
	}
	else
	{
		$getFile = $dir."/".$File;
	}
	
	if (file_exists($getFile)) 
	{ 
		$fd = fopen ($getFile, "r");

		$buffer = fread ($fd, filesize ($getFile)); 
		
		fclose ($fd); 
		
		return $buffer;
	}

}
//---------------------------------------------------
//
//---------------------------------------------------
    function delete_from_upload($id)
    {
        if ($this->post['editupload'] == "delete" )
        {
            $uploadfile = $this->post['uploadfile'];

            $row = $this->dbfetch("SELECT * FROM rafia_upload WHERE upid='$uploadfile'");
            @extract($row);
            $filename = $this->upload_path."/".$uppostid.".".$upcat;
            if(@unlink($filename))
            {
                $this->query("DELETE FROM rafia_upload WHERE upid='$uploadfile'");
                $this->query("update rafia_comment set uploadfile = '0' where id = '$id'");
            }
        }
    }

//---------------------------------------------------

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
//---------------------------------------------------
//                ads :  by mr.point
//---------------------------------------------------

    function ads_view_in($k)
    {
        $ads_check = $this->query("select * from rafia_ads where viewin='$k' and active='1'");

        $ads_row_check = $this->dbnumrows($ads_check);

        if ($ads_row_check != 0)
        {
            $ads = $this->query("select * from rafia_ads where viewin='$k' and adsisshow='no' and active='1'");

            $ads_row =$this->dbnumrows($ads);

            if($ads_row==0)
            {
                $this->query("update rafia_ads j set adsisshow='no' where viewin='$k'");
                $ads = $this->query("select * from rafia_ads where viewin='$k' and adsisshow='no' and active='1'");
                $ads_ar = $this->dbarray($ads);
                $this->query("update rafia_ads set adsisshow='yes',numshow=numshow+1 where adsid='$ads_ar[adsid]'");
            }
            else
            {
                $ads_ar=$this->dbarray($ads);
                $this->query("update rafia_ads set adsisshow='yes',numshow=numshow+1 where adsid='$ads_ar[adsid]'");
            }

		$file  = $ads_ar[adsimg];
		$mlf  = explode('.',basename($ads_ar[adsimg]));
		$type = $mlf[1]; $name = $mlf[0];

		if($type == 'swf'){
		if($ads_ar[width] == 0)$ads_ar[width] = '300';
		if($ads_ar[height] == 0)$ads_ar[height] = '150';
            $ads = "
<object
     classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
     codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\"
     width=\"$ads_ar[width]\" height=\"$ads_ar[height]\">
   <param name=\"movie\" value=\"$file\">
   <param name=\"allowScriptAccess\" value=\"sameDomain\">
   <embed
       pluginspage=\"http://www.macromedia.com/go/getflashplayer\"
       type=\"application/x-shockwave-flash\"
       src=\"$file\"
       width=\"$ads_ar[width]\" height=\"$ads_ar[height]\"
       allowScriptAccess=\"sameDomain\">
 </object><br>";
		}else{
		if($ads_ar[width] !== '0')$width = "width='$ads_ar[width]'"; else $width = "";
		if($ads_ar[height] !== '0')$height = "height='$ads_ar[height]'"; else $height = "";
            $ads = "<a href=index.php?action=e3lan&adsid=$ads_ar[adsid] target='_blank'><img $width $height title='$ads_ar[adsname]' border='0'  src='$ads_ar[adsimg]' alt='$adsa_ar[adsname]'></a><br>";
		}
        }
          return $ads;
      }

} //class end
?>