<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright © 2006   |
|   -------------------------------------   |
|           by Arab Portal Team             |
|   -------------------------------------   |
|     Web: http://www.ArabPortal.info       |
|   -------------------------------------   |
|  Last Updated: 22/10/2006 Time: 10:00 PM  |
+===========================================+
*/
class CatTree {

    var $Master = array();

    function CatTreeLast($ForumId)
    {
        global $apt;
        $result =$apt->query("SELECT id,subcat,title FROM rafia_cat ORDER BY id ASC");
        $Master = array();

        while(@extract($apt->dbarray($result)))
        {
            $Master[] = array ('id'=>$id,'subcat'=>$subcat,'title'=>$title);
        }
        $this->Master = $Master;
        $CatTreeResult = $this->RemoveLast($this->SubList($ForumId),",");
        $StrToArray = explode(",",$CatTreeResult);
        $NewArray = array();

        for($i=0;$i < sizeof($StrToArray);$i++)
        {
            $GetInfo = explode("=",$StrToArray[$i]);
            $NewArray[] = array('id'=>$GetInfo[0],'title'=>$GetInfo[1]);
        }

        $NewArray = array_reverse($NewArray);
        for($i=0;$i<sizeof($NewArray);$i++)
        {
            $Last .='<a href="'.$apt->self.'?action=list&cat_id='.$NewArray[$i]['id'].'"> '.$NewArray[$i]['title'].' </a > » ';
        }
         $Last  = substr ($Last,0,strlen($Last)-4);
        return $Last;
    }

    function SubList($id)
    {
        for($i=0;$i<sizeof($this->Master);$i++)
        {
            if($id==$this->Master[$i]['id'])
            {
                $InId .= $this->Master[$i]['id']."=".$this->Master[$i]['title'].",";
                if($this->Master[$i]['subcat']) $InId .= $this->SubList($this->Master[$i]['subcat']);
            }
        }
        return $InId;
    }

    function RemoveLast($s,$c,$n="")
    {
        if(empty($n)) $n=1;
        if(substr($s,strlen($s)-$n,$n)==$c){
            return substr($s,0,strlen($s)-$n);
        } else {
            return $s;
        }
    }

}
?>