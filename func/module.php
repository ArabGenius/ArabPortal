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

class module
{
    var $module   = "";
    var $modfile  = "index";
    var $modInfo  = array();
    var $mod_set_arr = array();

    function module()
	{
		global $apt;

        $this->module = $this->module_name($apt->get['mod']);

        if ( $this->module == "" )
        {
            header("Location: index.php");
            exit();
		}

        if($apt->get['modfile'])
        {
            $this->modfile = $this->module_name($apt->get['modfile']);
        }
        else
        {
             $this->modfile = "index";
        }

        if ( ! @file_exists('modules/' . $this->module . '/'.$this->modfile . '.php') )
        {
            $apt->errmsg (_MOD_FILE_NOT_FOUND);
        }

        $this->load_modInfo();

        $this->module_activist();
	}

    function mods_settings($modid)
    {
        global $apt;
        $result = $apt->query("SELECT set_var,set_val FROM rafia_mods_settings where set_mod='$modid'");
        while($row = $apt->dbarray($result)){
            $key       =  $row[set_var];
            $arr[$key] =  $row[set_val];
        }
        return $arr;
    }

    function getmodsettings($cell)
    {
	 if(is_array($this->mod_set_arr)){
       foreach ($this->mod_set_arr as  $key =>  $value){
           if ($key == $cell){
             return  $value;
           }
       }
	 }
    }

    function load_modInfo()
    {
        global $apt;

        $result = $apt->query("SELECT * FROM rafia_mods WHERE mod_name='".$this->module."'");

        $num_row = $apt->dbnumrows($result);

        if ($num_row > "0" )
        {
            $this->modInfo = $apt->dbarray($result);

             return  $this->modInfo;
        }
        else
        {
            $apt->errmsg (_MOD_NOT_FOUND);
        }
    }

	function module_OutPut()
	{
        global $apt;

        if(!$this->modfile) return;

        ob_start();

        extract ($this->modInfo, EXTR_IF_EXISTS);

        global $mod;
        require_once( 'modules/' . $this->module . '/' . $this->modfile . '.php');

        $OutPut = ob_get_contents();

        ob_end_clean();

		return $OutPut ;
	}

	function module_name($name)
	{
		return preg_replace( "/[^a-zA-Z0-9\-\_]/", "" , $name );
	}

	function module_activist()
    {
        global $apt;

        if(($apt->cookie['cgroup'] !== '1') and ($apt->cookie['cgroup'] !== '2'))
        {
            if ($this->modInfo[mod_sys] == 0)
            {
                $apt->errmsg (_MOD_DISABLE);
            }
        }

        if ($this->modInfo[mod_user] == 1)
        {
            checkcookie();
        }
    }
}
?>