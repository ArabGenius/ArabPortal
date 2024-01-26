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

class Counter
{
    var $CountArray  = Array();
    var $NewCount    = Array();
    var $CountName   = Null;
    var $Counter     = 0;

    function Counter()
    {
        $this->loadCounter();
	}

    function loadCounter()
    {
        global $apt;
        
        $result = $apt->query("SELECT * FROM rafia_counter");
        
		$this->CountArray = $apt->dbassoc($result);      
	}
	
    function update($fieldArray)
    {
		global $apt;
		
        foreach($fieldArray as $key => $value)
        {
            $fields .= $key."='".$value."',";
        }
        $fields = substr ($fields,0,strlen($fields)-1);
        
	   return  $apt->query("update rafia_counter Set $fields where conterID='1'");
   }      
   
    function SetCount($Name,$value)
    {
        $this->NewCount[$Name] = $value;
    }
 
    function getValue($Name)
    {
        if($this->NewCount[$Name] < $this->CountArray[$Name])
         return $this->CountArray[$Name];
         else
         return $this->NewCount[$Name];
    }
    
    function increment($Name)
    {
        if($this->NewCount[$Name] < $this->CountArray[$Name])
        {
			$this->NewCount[$Name]    = $this->CountArray[$Name]+1;
        }
        else
        {
			$this->NewCount[$Name]    = $this->NewCount[$Name]+1;
        }
    }	

    function decrement($Name)
    {
        if($this->NewCount[$Name] > $this->CountArray[$Name])
        {
			$this->NewCount[$Name]    = $this->CountArray[$Name]-1;
        }
        else
        {
			$this->NewCount[$Name]    = $this->NewCount[$Name]-1;
        }
    }	

    function addCount($Name,$value)
    {
        if($this->NewCount[$Name] < $this->CountArray[$Name])
        $this->NewCount[$Name]    = $this->CountArray[$Name]+$value;
        else
        $this->NewCount[$Name]    = $this->NewCount[$Name]+$value;
    }
    
    function dropFrom($Name,$value)
    {
        if($this->NewCount[$Name] < $this->CountArray[$Name])
        $this->NewCount[$Name]    = $this->CountArray[$Name]-$value;
        else
        $this->NewCount[$Name]    = $this->NewCount[$Name]-$value;
    }    
 
    function FinalCount()
    {
        global $apt;
       // echo "<pre>";
        // print_r($this->CountArray);
    	if( count($this->NewCount) == 0)
    	{
            return ;
    	}
    	elseif(count(@array_diff_assoc($this->CountArray,$this->NewCount)) > 0 )
		{ 
			$result = array_merge ($this->CountArray, $this->NewCount);
            //$hour   = $apt->getsettings("dtimehour");
	        $today  = mktime (0+$hour,0,0,date("m")  ,date("d") ,date("Y"));

			if($result[timetoday] < $today )
			{
				$result[timetoday] = $today;
				$result[dayCount]  = 1; 
			}
			elseif( $result[dayCount] > $result[mostCount] )
			{
				$result[mostCount] = $result[dayCount];
				$result[mosttime]  = $today; 
			}

			$this->update($result); 
		}
		//return $result;
    }    
}
?>
