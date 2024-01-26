<?php

/**
* @version 3.1.0 Hijriah Date Module
* @package Mohd Farhan Rahmat based on Zumaidi Zainuddin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

//  ⁄œÌ· «· «—ÌŒ »≈÷«›… ÌÊ„ «Ê «‰ﬁ«’ ÌÊ„
$modification = 0;

$b_01 = "„Õ—„";
$b_02 = "’›—";
$b_03 = "—»Ì⁄ «·«Ê·";
$b_04 = "—»Ì⁄ «·À«‰Ì";
$b_05 = "Ã„«œÏ «·«Ê·";
$b_06 = "Ã„«œÏ «·À«‰Ì";
$b_07 = "—Ã»";
$b_08 = "‘⁄»«‰";
$b_09 = "—„÷«‰";
$b_10 = "‘Ê«·";
$b_11 = "–Ê «·ﬁ⁄œ…";
$b_12 = "–Ê «·ÕÃ…";

///////// Days //////////
$h_01 = "«·«Õœ";  // Sunday
$h_02 = "«·«À‰Ì‰"; // Monday
$h_03 = "«·À·«À«¡"; // Tuesday
$h_04 = "«·«—»⁄«¡"; // Wednesday
$h_05 = "«·Œ„Ì”";	// Thursday
$h_06 = "«·Ã„⁄…";	// Friday
$h_07 = "«·”» ";	// Saturday

?>



<div align="center" id="hijriah">

<style type="text/css">
<!--
.bulan {
	color:#ffffff;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight:bold;
	text-transform: uppercase;
	padding-left:3px;
}

.hari {
	color:#000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 45px;
	font-weight: bold;
}

.namahari {
	color:#000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-transform: uppercase;
}

.tahun {
	color:#000000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}

-->

</style>

<?php

$ztoday = getdate(time() + (3600 * 4)); 
$zyr = $ztoday[year];
$zd=$ztoday[mday]+ $modification;
$zm=$ztoday[mon];
$zy=$zyr;



if (($zy>1582)||(($zy==1582)&&($zm>10))||(($zy==1582)&&($zm==10)&&($zd>14)))
	{
	
	
        $zjd=(int)((1461*($zy + 4800 + (int)( ($zm-14) /12) ))/4) + (int)((367*($zm-2-12*((int)(($zm-14)/12))))/12)-(int)((3*(int)(( ($zy+4900+(int)(($zm-14)/12))/100)))/4)+$zd-32075;
        }
 else
	{
        $zjd = 367*$zy-(int)((7*($zy+5001+(int)(($zm-9)/7)))/4)+(int)((275*$zm)/9)+$zd+1729777;
	}
		
$zl=$zjd-1948440+10632;
$zn=(int)(($zl-1)/10631);
$zl=$zl-10631*$zn+354;
$zj=((int)((10985-$zl)/5316))*((int)((50*$zl)/17719))+((int)($zl/5670))*((int)((43*$zl)/15238));
$zl=$zl-((int)((30-$zj)/15))*((int)((17719*$zj)/50))-((int)($zj/16))*((int)((15238*$zj)/43))+29;
$zm=(int)((24*$zl)/709);
$zd=$zl-(int)((709*$zm)/24);
$zy=30*$zn+$zj-30;

if($zm==1){ $bulan = $b_01;}
if($zm==2){ $bulan = $b_02;}
if($zm==3){ $bulan = $b_03;}
if($zm==4){ $bulan = $b_04;}
if($zm==5){ $bulan = $b_05;}
if($zm==6){ $bulan = $b_06;}
if($zm==7){ $bulan = $b_07;}
if($zm==8){ $bulan = $b_08;}
if($zm==9){ $bulan = $b_09;}
if($zm==10){ $bulan = $b_10;}
if($zm==11){ $bulan = $b_11;}
if($zm==12){ $bulan = $b_12;}

if($ztoday[wday]==0){ $hari = $h_01;}
if($ztoday[wday]==1){ $hari = $h_02;}
if($ztoday[wday]==2){ $hari = $h_03;}
if($ztoday[wday]==3){ $hari = $h_04;}
if($ztoday[wday]==4){ $hari = $h_05;}
if($ztoday[wday]==5){ $hari = $h_06;}
if($ztoday[wday]==6){ $hari = $h_07;}

?>
<TABLE WIDTH=125 height="142" BORDER=0 align="center" CELLPADDING=0 CELLSPACING=0 background="images/bg_hijriah.gif" style="background-repeat:no-repeat; background-position:center;">
	<TR>
		<TD height="49" align="center" valign="bottom"><span class="bulan"><? echo "$hari"; ?></span></TD>
	</TR>
	<TR>
		<TD height="64" align="center" valign="top"><span class="hari"><? echo "$zd"; ?></span><br />
		  <span class="namahari"><? echo "$bulan"; ?></span>
</TD>
	</TR>
	<TR>
		<TD height="29" align="center" valign="top"><span class="tahun"><? echo "$zy"; ?> ··ÂÃ—… </span></TD>
	</TR>
</TABLE>
</div>