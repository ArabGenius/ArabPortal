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

if (stristr($_SERVER['SCRIPT_NAME'], "backup.php")) {
    die ("<center><h3>⁄›Ê« Â–Â «·⁄„·Ì… €Ì— „‘—Ê⁄…</h3></center>");
}

class backup
{
    function backup ($action)
    {
        global $apt,$admin;
        @set_time_limit(900);
        $action = $apt->format_data($action);

        $not_import = array('rafia_admin_login',
                            'rafia_admin_sess',
                             'rafia_online',
                             'rafia_spam',
                             'rafia_sessions',
                             'rafia_search_res');
        if ($action=="")
        {
             

            $admin->head();
            $admin->openformdata("$PHP_SELF?cat=backup&act=reset&".$admin->get_sess());
            $list = array("„·› ﬁ«⁄œ… «·»Ì«‰« ",
            '<input type="file" name="backupFile" size="30">');
            $admin->Rows = $admin->TableCell($list);
            echo $admin->Table(array("" ,""),'≈⁄«œ… ﬁ«⁄œ… «·»Ì«‰« ');
            $admin->submit(_UPDATE);

            $admin->openformdata("$PHP_SELF?cat=backup&act=reset&".$admin->get_sess());
            $list = array("MYSQL",
            '<textarea dir=ltr name="backupLINE" cols="70" rows=4></textarea>');
            $admin->Rows = $admin->TableCell($list);
            echo $admin->Table(array("" ,""),'„Õ—— «Ê«„— ﬁ«⁄œ… «·»Ì«‰« ');
            $admin->submit(_UPDATE);

            $admin->openform("$PHP_SELF?cat=backup&act=dump&".$admin->get_sess());


            $result = $apt->query("show tables");
            $admin->Rows = '';
            while($row = $apt->dbarray($result))
            {
                if (in_array ($row[0], $not_import))
                {

                    $list = array($row[0], "<input type=\"checkbox\" value=\"$row[0]\"  name=\"table[]\">");
                }
                else
                {
                    $list = array($row[0], "<input type=\"checkbox\" value=\"$row[0]\"  name=\"table[]\" checked>");

                }

                $admin->Rows .= $admin->TableCell($list);
            }

            $admin->Rows .= '<tr><td align=center colSpan=2 bgColor=#dbe7f9 class=TableBodyNumeric>
			<input onclick="checkAll(document.admin)" type="button" value=" ÕœÌœ «·ﬂ·" name="CheckAll">
			<input onclick="uncheckAll(document.admin)" type="button" value="≈·€«¡  ÕœÌœ «·ﬂ·" name="UnCheckAll">
		<br><input type="submit" name="submit" value="  ‰”Œ  ">
		<input type="submit" name="OPTIMIZE" value=" Õ”Ì‰">
		<input type="submit" name="repair" value="«’·«Õ"></td></tr>';
            $admin->ColWidth = array(40,20);
            echo $admin->Table(array("Table" ,"check"),_SELECT_TABLES_TO_COPY);
		echo "<br><br></center></FORM>";
            //$admin->submit(_COPY);//**BY ANS**//
            echo $this->_Javascript();
        }
        elseif ($action=="dump")
        {
		if($apt->post[submit]){
            @set_time_limit ( 0 );
            @ini_alter ( "memory_limit", "1024M" );
            $contents ='';
           	while (list($key,$value)=each($apt->post[table]))
            {
                    $contents .= $this->get_table($value);
                    if (!in_array ($value, $not_import))
                    $contents .= "\n".$this->get_table_data($value);
                    $contents .=  "\n";

            }
           $Files = $apt->upload_path.'/'.$apt->conf['dbname'].'-'.gmdate('d-M-Y').'.sql';

           if(Files::write($Files,$contents)===true)
           Files::download($Files);
		}elseif($apt->post[repair]){
		$admin->head();
           	foreach($apt->post[table] as $table){
		$apt->query("REPAIR TABLE `$table`");
		$cont .= "<br> „ »‰Ã«Õ «’·«Õ «·ÃœÊ· ". $table;
		}
		@header("Refresh: 5;url=".$apt->refe);
		$admin->windowmsg("<p align=right>$cont</p>");
		}elseif($apt->post[OPTIMIZE]){
		$admin->head();
           	foreach($apt->post[table] as $table){
		$apt->query("OPTIMIZE TABLE `$table`");
		$cont .= "<br> „ »‰Ã«Õ  Õ”Ì‰ «·ÃœÊ· ". $table;
		}
		@header("Refresh: 5;url=".$apt->refe);
		$admin->windowmsg("<p align=right>$cont</p>");
		}
        }
        elseif ($action=="reset")
        {

           $copyFiles = $apt->upload_path.'/reset-'.gmdate('d-M-Y').'.sql';

           if (is_uploaded_file($_FILES["backupFile"]['tmp_name']))
           {
               if (move_uploaded_file($_FILES["backupFile"]['tmp_name'], $copyFiles ))
               {
                   $this->install_Sql($copyFiles);
                   
               }
           }elseif(!$_POST[backupLINE]==''){
		$lines = stripslashes(trim($_POST[backupLINE]));
		if(substr($lines, -1, 1) !== ';')$lines = stripslashes($lines).';';
		$this->do_Sql($lines);
           }
        }
     }
     
    
     function get_table($table)
     {
         global $apt;
         $br = "\r\n";
         $return .= "$br#------------------- TABLE $table -------------------$br";
         $return .= "DROP TABLE IF EXISTS $table; $br";
         $return .= $br;
         $result = $apt->query("SHOW CREATE TABLE ". $table);
         $row = $apt->dbarray($result);
         $row[1] = str_replace("ENGINE=MyISAM DEFAULT CHARSET=latin1",'',$row[1]);
         $return .=  $row[1].';';
         $return  .= $br;
         return (stripslashes($return));
    }

    function get_table_data($table)
    {
        global $apt;
        $br = "\n";
        $result = $apt->query('SELECT * FROM ' . $table) or die();

        if ($result != false)
        {
            @set_time_limit(1200);
            for ($j = 0; $j < mysql_num_fields($result); $j++)
            {
                $field_set[$j] = mysql_field_name($result, $j);
                $type          = mysql_field_type($result, $j);
                if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                $type == 'bigint'  || $type == 'timestamp') {
                    $field_num[$j] = true;
                }else {
                    $field_num[$j] = false;
                }
            }
            $fields        = implode(', ', $field_set);
            $schema_insert = "INSERT INTO $table VALUES ";
            $field_count   = mysql_num_fields($result);
            
            $search  = array("\x0a","\x0d","\x1a"); //\x08\\x09, not required
            $replace = array("\\n","\\r","\Z");

            while ($row = $apt->dbarray($result))
            {
                for ($j = 0; $j < $field_count; $j++)
                {
                    if (!isset($row[$j]))
                    {
                        $values[]     = 'NULL';
                    }
                    else if (!empty($row[$j]))
                    {
						if ($field_num[$j])
                        {

                            $values[] = $row[$j];
                        }
                        else
                        {
                            $values[] = "'" . str_replace($search, $replace, addslashes($row[$j])) . "'";
                        }
                    }
                    else
                    {
                        if ($field_set[$j] == 'modulname')
                        $values[] = "0";
                        else
                        $values[] = "''";

                    }
                }
                $insert_lines[] = $schema_insert. '('. implode(',', $values) . ');';
                unset($values);
            }
            if(count($insert_lines)>0)
            {
                $insert_all .= implode("$br", $insert_lines);
                $insert_all  .= $br;
            }
            return ($insert_all);
        }
    }

    function do_Sql($SqlLINE)
    {
        global $apt,$admin;
	  $Sql	=  explode ("\n", $SqlLINE);
	foreach($Sql as $query) {

		$query = trim($query);

		if($query[0] == '#') {
			continue;
		}
		if(!$query) {
			continue;
		}
		$cur_query .= $query. ' ';
		if(substr($query, -1, 1) == ';') {
			$query_statements[] = substr(trim($cur_query), 0, -1);
			$cur_query = '';
		}
	}

	if(count($query_statements)) {
		foreach($query_statements as $l => $query_statement) {
			$apt->query($query_statement);
		}
	}
    @header("Refresh: 1;url=".$apt->refe);
    $admin->windowmsg("<p>&nbsp; „ «· ÕœÌÀ</p>");
    }




    function install_Sql($SqlFile)
    {
        global $apt,$admin;
        $SqlFile = Files::read ($SqlFile);
		$Sql	=  explode ("\n", $SqlFile);
	foreach($Sql as $query) {

		$query = trim($query);

		if($query[0] == '#') {
			continue;
		}
		if(!$query) {
			continue;
		}
		$cur_query .= $query. ' ';
		if(substr($query, -1, 1) == ';') {
			$query_statements[] = substr(trim($cur_query), 0, -1);
			$cur_query = '';
		}
	}

	if(count($query_statements)) {
		foreach($query_statements as $l => $query_statement) {
			$apt->query($query_statement);
		}
	}
    @header("Refresh: 1;url=".$apt->refe);
    $admin->windowmsg("<p>&nbsp; „ «· ÕœÌÀ</p>");
    }

				
  function _Javascript()
  {
return <<<EOF
<script language="JavaScript">
<!--
function checkAll(field)
{
  for(i = 0; i < field.elements.length; i++)
     field[i].checked = true ;
}

function uncheckAll(field)
{
 for(i = 0; i < field.elements.length; i++)
    field[i].checked = false ;
}
// --></script>
EOF;
}
}
?>