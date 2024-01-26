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

/*##########################################

«”„ «·ﬂ«∆‰ : «·√ﬁ”«„ «·›—⁄Ì… «·„ ⁄œœ…
—ﬁ„ «·≈’œ«— : 1
«·„»—„Ã : ⁄»œ «·—Õ„‰ »‰ ‰«’— «·”⁄Ìœ
«·„Êﬁ⁄ : http://www.toarab.ws - http://www.toarab.org
«·»—Ìœ «·≈·ﬂ —Ê‰Ì : webmaster@toarab.ws - ababab90@yahoo.com
 √—ÌŒ «·»—„Ã… : ÌÊ„ «·√Õœ «·„Ê«›ﬁ 14/—Ã»/1425 Â‹
-------------------------------------------------------------------

@Name :	JumpListClass
@Versoin : 1
@Author : Abdurrhman bin Nasir Assaeed
@Web Site : http://www.toarab.ws - http://www.toarab.org
@E-mail : webmaster@toarab.ws - ababab90@yahoo.com
@Date : 29/08/2004
##########################################*/

class JumpListClass {

	var $Main = array();
	var $Sub = array();
	var $Url;
	Var $Type;
function DoJumpList ($Master,$Url,$Type=1) {
	$this->Main =	$this->SetMain($Master);
	$this->Sub	=	$this->SetSub($Master);
	$this->Url	=	$Url;
	$this->Type =	$Type;
	return $this->Build();
}
function SetMain($Master) {
	$Main= array();
	for($i=0;$i<sizeof($Master);$i++) {
		if($Master[$i]['subcat']==0) {
			$Main[]=$Master[$i];
		}
	}
return $Main;
}
function SetSub($Master) {
	$Sub = array();
	for($i=0;$i<sizeof($Master);$i++) {
		if($Master[$i]['subcat']!==0) {
			$Sub[]=$Master[$i];
		} 
	}
return $Sub;
}
function Build () {
	$Form = "<form name='menu' method='post'>
			<select name='url' onchange=\"window.location.href=this.options[this.selectedIndex].value\">";
    $Form .= "<option>›÷·« Õœœ ﬁ”„« „„« Ì·Ì</option>";
    $Form .= "<option>- - - - - - - - - - - - - - - - - -</option>";
    $Mn = 1;
	$size = sizeof($this->Main);
	for($i=0;$i<$size;$i++) {
		$Form .= "<option style=\"color: #34597D\" value='".$this->Url.$this->Main[$i]['id']."'>".$Mn.")->".$this->Main[$i]['title']."</option></style>";
        $Form .= $this->SubList($this->Main[$i]['id'],$Mn);
		if($i<($size-1)) {
        $Form .= "<option> ----------------------------</option>";
		}
        $Mn++;
	}
   $Form .= "</select><input type=\"submit\" name=\"submit\" value=\"«‰ ﬁ·\"></form>";
   //Free Memory
   unset($this->Main);
   unset($this->Sub);
   return $Form;
}
function SubList($id,$Mn,$Sn=""){
	$b_id = array();
	$b_title = array();
	for($i=0;$i<sizeof($this->Sub);$i++) {
		if($id==$this->Sub[$i]['subcat']) {
			$b_id[]= $this->Sub[$i]['id'];
			$b_title[] = $this->Sub[$i]['title'];
		}
	}
	if (empty($b_id)) {
		return;
	} else {
	$Sn=1;
	}
	
	if (count($b_id) > 1 ) {
		$Form ="";
		for($i=0;$i<sizeof($b_id);$i++) {
			$Form .= "<option value=\"".$this->Url.$b_id[$i]."\"> ".$Mn." ) ª ".$Sn." ) ".$b_title[$i]."</option>";
			$Mn2 = $Mn." ) ª ".$this->ListType($Sn,$b_title[$i]);
			$Form .=$this->SubList($b_id[$i],$Mn2);
			$Sn++;
		}
	} else {
		$Form = "<option value=\"".$this->Url.$b_id[0]."\"> ".$Mn." ) ª ".$Sn." ) ".$b_title[0]."</option>";
		$Mn2 = $Mn." ) ª ".$this->ListType($Sn,$b_title[0]);
		$Form .=$this->SubList($b_id[0],$Mn2,$Sn);
	}
	//Free Memory
	unset($b_id);
	unset($b_title);
	return $Form;
}
function ListType($Sn,$b_title) {
	if($this->Type>2) $this->Type =1;
	if($this->Type==1) {
		return $Sn;
	} else if($this->Type==2) {
		return $b_title;
	} 
}
}// End class
?>
