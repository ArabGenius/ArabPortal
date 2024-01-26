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

if (stristr($_SERVER['SCRIPT_NAME'], "smiles.php")) {
    die ("<center><h3>ÚİæÇ åĞå ÇáÚãáíÉ ÛíÑ ãÔÑæÚÉ</h3></center>");
}

class smiles
{
    function smiles ($action)
    {
        global $apt,$admin;

        $action = $apt->format_data($action);


        if($action=="")
        {
        }
        else if ($action=="list")
        {
            $admin->head();
            $admin->opentable("ÚÑÖ ÇáÇÈÊÓÇãÇÊ","5");
            echo "<tr> ";
    
              $admin->td_msgh("ÇáÕæÑÉ");
              $admin->td_msgh("ÇáÇÓã");
              $admin->td_msgh("ÃÎÊÕÇÑ");
              $admin->td_msgh(" ÊÍÑíÑ");
              $admin->td_msgh("ÍĞİ ");

        	echo "</tr>";
            $result = $apt->query("SELECT * FROM rafia_smileys ORDER BY id");
            while($row=$apt->dbarray($result))
            {
                @extract($row);

                  $imgsmile = "<img src=\"../images/smiles/$smile\" alt=\"$smilename\" border=\"0\">";
                   echo "<tr> ";
                  $admin->td_msg("$imgsmile");
                  $admin->td_msg("$smilename");
                  $admin->td_msg("$code");
                  $admin->td_url("$PHP_SELF?cat=smiles&act=edit&id=$id&".$admin->get_sess(), "ÊÍÑíÑ");
                  $admin->td_url("$PHP_SELF?cat=smiles&act=del&id=$id&".$admin->get_sess(), "ÍĞİ");
                  echo "</tr>";
        
            }
        }
        else if ($action=="add")
        {
             $admin->head();
            print "ÇĞÇ ŞãÊ ÈäŞá ÇáÕæÑ ÈæÇÓØÉ ftp İŞØ ÇßÊÈ ÇÓã ÇáÕæÑÉ  ÈÏæä ÊæÖíÍ ÇáãÌáÏ  <br> ãÚ ÇáÇäÊÈÇå Çä ÊÑİÚ ÇáÕæÑ İí ãÌáÏ smiles
            <br> æÇĞÇ Êã ÑİÚ ÇáÕæÑÉ ãä áæÍÉ ÇáÊÍßã íÌÈ Çä ÊÚØí ÇáãÌáÏ smiles ÊÑÎíÕ 777 Ëã Şã ÈÑİÚ ÇáÕæÑÉ æÊÑß ÎÇäÉ ÇÓã ÇáÕæÑÉ İÇÑÛ ";
            $admin->openformdata("$PHP_SELF?cat=smiles&act=insert&".$admin->get_sess());
            $admin->opentable("ÇÖÇİÉ ÇÈÊÓÇãÇÊ");
            $admin->inputtext('ÇÓã ÇáÇíŞæäÉ','smilename','');
            $admin->inputtext('ÇÎÊÕÇÑ ÇáÇÈÊÓÇãÉ','code','');
            $admin->inputtext('ÇÓã Çáãáİ ÈÇáÇãÊÏÇÏ','smile','');
            $admin->inputfile("Çæ Şã ÈÑİÚ ÇáÇÈÊÓÇãÉ áãÌáÏ ÇáÇÈÊÓÇãÇÊ");
            $admin->closetable();
            $admin->submit("ÇÖİ");
            $admin->closeform ();
        }
        else if ($action=="insert")
        {
            $upload = $_FILES["name_file"];
            @extract($_POST);
            if (is_uploaded_file($upload['tmp_name']))
            {
                $tmp_name =  $upload["tmp_name"];
                $filename  = "../images/smiles/".$upload["name"];
                if(move_uploaded_file($tmp_name, $filename))
                {
                   $smile = $upload["name"];
                }
            }

           $result = $apt->query("INSERT INTO rafia_smileys
                                  (smilename,code,smile) VALUES
                                  ('$smilename','$code','$smile')");
             if ($result)
             {
                 $admin->windowmsg("ÊãÊ ÚãáíÉ ÇáÇÖÇİÉ ÈäÌÇÍ");
             }
        }
        else if ($action=="edit")
        {

            $id = $apt->setid('id');
            @extract($_POST);
            $admin->head();
            $result=$apt->query("select * from rafia_smileys where id='$id'");
            $smiles=$apt->dbarray($result);
            extract($smiles);
            $admin->openform("$PHP_SELF?cat=smiles&act=update&id=$id&".$admin->get_sess());
            $admin->opentable("ÊÍÑíÑ ");
            $admin->inputtext('ÇÓã ÇáÇíŞæäÉ','smilename',$smilename);
            $admin->inputtext('ÅÎÊÕÇÑ ÇáÇíŞæäÉ','code',$code);
            $admin->inputtext('Çáãáİ','smile',$smile);
            $admin->closetable();
            $admin->submit  ("ÊÍÑíÑ");

        }
        else if ($action=="del")
        {
            $id = $apt->setid('id');
            
            $result = $apt->query("delete from  rafia_smileys where id='$id'");

            if ($result)
            {
               $admin->windowmsg("<p>Êã ÍĞİ ÇáÇÈÊÓÇãÉ </p>");
            }
        }
        else if($action=="update")
        {
            $id = $apt->setid('id');
            @extract($_POST);
            $result = $apt->query("update rafia_smileys set smilename='$smilename',
                                                code='$code',
                                                smile='$smile'
                                                where id='$id'");

            if ($result)
            {
               $admin->windowmsg("<p>Êã ÇáÊÍÑíÑ ÈäÌÇÍ </p>");
            }
        }
    }
}
?>
