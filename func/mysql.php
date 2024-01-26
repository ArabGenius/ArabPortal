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
REPAIR TABLE rafia_online
*/
define("MYSQLERROR_ERROR","Error");
define("MYSQLERROR_NUMBER","Error No");

class mysql extends info
{
    var $dbserver  ='';
    var $dbconntype='';
    var $dbuser    ='';
    var $dbpword   ='';
    var $dbname    ='';
    var $link      = NULL;
    var $queryNum  = 0;
    var $result    = false;
    
    function mysql()
    {
    	$this->info();
        $this->dbconntype  = $this->conf['dbconntype'];
        $this->dbserver    = $this->conf['dbserver'];
        $this->dbuser      = $this->conf['dbuser'];
        $this->dbpword     = $this->conf['dbpword'];
        $this->dbname      = $this->conf['dbname'];
        $this->connect();
	}
                                                                                                                                                                                 // SQL
    function connect()
    {
	  if($this->dbconntype == 0)
        $this->link = @mysql_connect($this->dbserver, $this->dbuser, $this->dbpword);
        else
        $this->link = @mysql_pconnect($this->dbserver, $this->dbuser, $this->dbpword);

        if(!$this->link)
        {
            $this->print_error ("ÛíÑ ŞÇÏÑ Úáì ÇáÅÊÕÇá ÈÓíÑİÑ $this->dbserver");
        }
        
        if(! @mysql_select_db($this->dbname,$this->link) )
        {
            $this->print_error ("($this->dbname)   ÛíÑ ŞÇÏÑ Úáì ÅÎÊíÇÑ ŞÇÚÏÉ ÇáÈíÇäÇÊ ");
        }
	
         return $this->link;
    }
    
    function close()
    {
	if($this->dbconntype == 0){
        if(! @mysql_close($this->link) )
        return false;
	}
    }
//-----------------------------------------------

//-----------------------------------------------

    function freeResult($ret = '')
    {
        if($ret)$this->result = $ret;
        return (@mysql_free_result($this->result));
    }
    
    
    function query($query,$repair = '')
    {
        $this->result = mysql_query($query,$this->link);

        if (!$this->result)
        {
            if($repair)
            {
                mysql_query('REPAIR TABLE '.$repair,$this->link);
            }
              $this->print_error("\n". $query);
        }
        $this->queryNum++;
        $this->query .=$this->queryNum . "  " . $query."\n\n";

        return $this->result;
        
    }
    
    function dbassoc($ret ="" )
    {
        if($ret)
        $this->result = $ret;
        
        return (@mysql_fetch_assoc($this->result));
    }
    
    function dbarray( $result )
    {
        return mysql_fetch_array($result);
    }

   	function dbfetch($query)
	{
       if($result = $this->query($query))
		return $this->dbarray($result);
     }

    function numRows($ret ="" )
    {
        if($ret)$this->result = $ret;
        return (@mysql_num_rows($this->result));
    }
    
    function dbnumrows( $ret ="" )
    {
        if($ret)
        $this->result = $ret;
        $resultNum = mysql_num_rows($this->result);
        return $resultNum;
    }

    function dbobject($result)
    {
        $result = mysql_fetch_object($result);

        return $result;
    }

    function dbnumquery($table,$where="",$field='')
    {
	  $field = $field ? $field : '*';
        if ($where =="")
        {
            $result = $this->query("SELECT $field FROM $table");
        }
        else
        {
            $result = $this->query("SELECT $field FROM $table WHERE $where");
        }

        $res = $this->dbnumrows($result);

        return $res;
    }

    function insertid()
    {
        if (mysql_affected_rows()>0)
        {
            $id = mysql_insert_id();
        }
        return $id;
    }

    function deleteid($table,$id)
    {
        $this->table = $table;
        $this->result = $this->query("DELETE FROM $this->table WHERE $id");

        return $this->result;
    }

    function fetchField($result,$FieldN = FALSE)
    {
        $i =0;
        while ($i < @mysql_num_fields($this->result))
        {
            $name              = mysql_field_name($result, $i);
            $Fieldarray[type]  = mysql_field_type($result, $i);
            $Fieldarray[len]   = mysql_field_len($result, $i);
            $Fieldarray[flags] = mysql_field_flags($result, $i);
            if(strpos($Fieldarray[flags], 'primary')  !== FALSE )
            $Fieldarray[primary] = $name;
            else
            unset($Fieldarray[primary]);

            $Fieldinfo[$name] = $Fieldarray;

            if (($FieldN !== FALSE) && $FieldN == $name)
            return $Fieldinfo[$name];
            $i++;

        }
       return $Fieldinfo;
    }
    
    function print_error($err = "")
    {
        $this->error = mysql_error();
        $this->errno = mysql_errno();
       	$_error  = MYSQLERROR_ERROR." : ".mysql_error()."\n";
    	$_error .= MYSQLERROR_NUMBER.": ".$this->errno."\n";
    	$_error .= "Date: ".date("l dS of F Y h:i:s A")."\n";
    	$_error .= "\n---------------Query---------------";
    	$output = "<html dir=rtl><head><meta http-equiv=Content-Type content=text/html; charset=windows-1256>
                  <title>Arab Portal Database Error</title>
    		      <style>P,BODY{ font-family:Windows UI,arial; font-size:12px; }</style>
                  </head>
                  <body>
    		      <br><br><blockquote><b>áã íßä ŞÇÏÑ Úáì ÇÌÑÇÁ ÇáÇÓÊÚáÇã ãä ŞÇÚÏÉ ÇáÈíäÇÊ.</b><br>
    		      íÑÌì ÇáãÍÇæáÉ ÈÊÍÏíË ÇáÕİÍÉ ÈÇÖÛØ Úáì åĞÇ ÇáÑÇÈØ <a href=\"javascript:window.location=window.location;\">ÇÖÛØ åäÇ</a>
    		      ÇĞÇ áã íÌÑí ÇáÇÓÊÚáÇã ÇáÑÌÇÁ ÇÈáÇÛ ÇÏÇÑÉ ÇáãæŞÚ  <a href='mailto:".$this->conf['site_mail']."?subject=ÎØÃ İí ŞÇÚÏÉ ÇáÈíäÇÊ'>ãÑÇÓáÉ ÇáãæŞÚ </a>
    		      <br><br><b>ÇáÇÓÊÚáÇã ÇáĞí ÊÓÈÈ İí ÇáÎØÃ</b><br>
    		      <form name='mysql'><textarea dir=\"ltr\" rows=\"15\" cols=\"70\">".$_error."\n$err</textarea></form></blockquote><br>
                  <p><b><a href=\"http://www.arabportal.info/\" target=\"_blank\">ÈÇÚÊãÇÏ ÈÑäÇãÌ ÇáÈæÇÈÉ ÇáÚÑÈíÉ</a></b></p></body></html>";
        print $output;
        exit;
    }

}
?>