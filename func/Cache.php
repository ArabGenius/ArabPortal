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
class Cache
{
   var $CacheDir = '';
    
    function Cache ()
    {
        global $apt;

        $this->CacheDir = $apt->conf['site_path']."/html/Cache";

         if($this->mk_Dir($this->CacheDir) === false)
         {
            $apt->errmsg("ÌÃ» «‰ ÌﬂÊ‰  ’—ÌÕ «·„Ã·œ html = 0777 <br>".$this->CacheDir);
         }
         if($this->mk_Dir($this->CacheDir.'/'.$apt->theme) === false)
         {
            $apt->errmsg("ÌÃ» «‰ ÌﬂÊ‰  ’—ÌÕ «·„Ã·œ html = 0777");
         }
    }
    
    function mkCache ($theme)
    {
        global $apt;

         $result =  $apt->query("SELECT * FROM rafia_templates WHERE theme='".$theme."' ORDER BY name ASC");

         //$themeDir = $theme;
         
         while($temp = $apt->dbarray($result))
         {
             extract($temp);
             $this->write($theme."/$name.tpl",$template);
         }
    }
    
    function mkCache_update ($id)
    {
        global $apt;

            $result =  $apt->query("SELECT * FROM rafia_templates WHERE id='$id'");
            $temp = $apt->dbarray($result);
            extract($temp);
            $this->write($theme."/$name.tpl",$template);

    }
    function isCache($fileCache = 'Index_main')
    {
        global $apt;
        
        $Path = $this->CacheDir ."/".$apt->theme;

        $filename = $Path.'/'.$fileCache.".tpl";
        
        if (file_exists($filename))
        {
            return TRUE;
        }
        else
        {
            return false;
        }

 	}
  
    function mk_Dir($Path,$mode=0775)
    {
        if(!is_dir($Path))
		{
              $__oldumask = umask(0);
              
            if(!@mkdir($Path, $mode))
		    {
		       return false;
		    }
              umask($__oldumask);
		}
    }

    function write($file,$content)
    {
        $path = $this->CacheDir ."/".$file;
        
        if (! $fp   = @fopen( $path , "w" ))
        return false;
        
        if (!@fwrite($fp, $content))
        {
           @fclose( $fp );
            return TRUE;
        }
        else
        {
            return false;
        }
 	}
}
