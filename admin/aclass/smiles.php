<?php
/*
+===========================================+
|      ArabPortal V2.1.x Copyright � 2006   |
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
    die ("<center><h3>���� ��� ������� ��� ������</h3></center>");
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
            $admin->opentable("��� ����������","5");
            echo "<tr> ";
    
              $admin->td_msgh("������");
              $admin->td_msgh("�����");
              $admin->td_msgh("������");
              $admin->td_msgh(" �����");
              $admin->td_msgh("��� ");

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
                  $admin->td_url("$PHP_SELF?cat=smiles&act=edit&id=$id&".$admin->get_sess(), "�����");
                  $admin->td_url("$PHP_SELF?cat=smiles&act=del&id=$id&".$admin->get_sess(), "���");
                  echo "</tr>";
        
            }
        }
        else if ($action=="add")
        {
             $admin->head();
            print "��� ��� ���� ����� ������ ftp ��� ���� ��� ������  ���� ����� ������  <br> �� �������� �� ���� ����� �� ���� smiles
            <br> ���� �� ��� ������ �� ���� ������ ��� �� ���� ������ smiles ����� 777 �� �� ���� ������ ���� ���� ��� ������ ���� ";
            $admin->openformdata("$PHP_SELF?cat=smiles&act=insert&".$admin->get_sess());
            $admin->opentable("����� ��������");
            $admin->inputtext('��� ��������','smilename','');
            $admin->inputtext('������ ���������','code','');
            $admin->inputtext('��� ����� ���������','smile','');
            $admin->inputfile("�� �� ���� ��������� ����� ����������");
            $admin->closetable();
            $admin->submit("���");
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
                 $admin->windowmsg("��� ����� ������� �����");
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
            $admin->opentable("����� ");
            $admin->inputtext('��� ��������','smilename',$smilename);
            $admin->inputtext('������ ��������','code',$code);
            $admin->inputtext('�����','smile',$smile);
            $admin->closetable();
            $admin->submit  ("�����");

        }
        else if ($action=="del")
        {
            $id = $apt->setid('id');
            
            $result = $apt->query("delete from  rafia_smileys where id='$id'");

            if ($result)
            {
               $admin->windowmsg("<p>�� ��� ��������� </p>");
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
               $admin->windowmsg("<p>�� ������� ����� </p>");
            }
        }
    }
}
?>
