<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright � 2006   |
|   -------------------------------------   |
|            By Rafia AL-Otibi              |
|                    And                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Net       |
|   -------------------------------------   |
|  Last Updated: 09/01/2009 Time: 02:09 AM  |
+===========================================+
*/

// <!--INC dir="block" file="today.php" -->

if (TURN_BLOCK_ON !== true)
{
    die ("<center><h3>���� .. �� ����� ��� ������ ������</h3></center>");
}

include_once('func/arabicTools.class.php');

$diff = 0; //��� ������� ��������

$time = time() + $diff;
$al_saah      =  '������ '.$apt->gettime($date_time);
$tareekhH 	  =  ArabicTools::arabicDate("hj:l ".$apt->conf['date_format']." ��", $time);
$tareekhM 	  =  ArabicTools::arabicDate($apt->conf['date_format'], $time);

$date = "����� ".$tareekhH .' ������� '.$tareekhM.' �';

echo $date;
?>