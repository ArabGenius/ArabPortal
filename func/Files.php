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

class Files
{

    /**
    *
    * echo Files::read("filename.txt");
    * or
    * $content =Files::read("filename.txt");
    *
    */
    function read ($file)
    {
        if (!is_readable($file))
        {
            Files::exit_Error("����� ��� ���� ������� readable: ".$file);
        }

        if(!$fp = @fopen ($file, 'rb'))
        {
            Files::exit_Error("�� ���� ��� �����  fopen: ".$file);
        }

        $content = fread ($fp, filesize($file));

        fclose ($fp);

        return $content;
    }


    /**
    *
    *  if(Files::write("filename.txt"))
    *  echo '<br>����� ��� ����';
    *  -----------------------
    *
    *  if(Files::write("filename1.txt","���� ��� ����"))
    *  echo '<br>����� ��� �������� ���';
    *  -----------------------
    *
    * if(Files::write("filename1.txt","\n ��� ��� ���� ",'a'))
    * echo '<br>������� �� ��� ����� �� ��� �� ����� ��� ����';
    * ------------------------
    *
    * if(Files::write("filename1.txt","\n ��� ��� ���� ",'a',true))
    * echo '<br> ������� �� ��� ����� �� ���  ������� ��� ���� ������� ������ �� ���';
    *
    */
    function write ($file,$content='',$mode ='w' ,$writable = false )
    {
        if (! $writable == 'NoError'){
        if (($writable !== false) and (!is_writable($file))){
            Files::exit_Error("����� ��� ���� �������   , writable:".$file);
        }
        }

        if (!$fp = @fopen($file, $mode))
        {
            Files::exit_Error("�� ���� ��� ����� �������  , fopen:".$file);
        }

        if($content =='')
        {
            @fclose($fp);
            return true;
        }

        @flock($fp,LOCK_EX);

        @fwrite($fp, $content);

        @flock($fp,LOCK_UN);

        @fclose($fp);
        return true;
    }


    /**
    *   ����� ���
    *  Files::download("filename1.txt");
    */
	function download( $file )
    {
        //if (file_exists($file))
        //{
                @header( "Pragma: public");
                @header( "Expires: 0");
                @header( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
                @header( "Accept-Ranges: none");
                @header( "Content-Type: application/force-download");
          		@header( "Content-Type: application/octet-stream");
                @header( 'Content-Disposition: attachment; filename="'. basename($file) .'"');
                @header( "Content-Length: ". filesize($file));
                @header( "Content-Transfer-Encoding: binary");
		        @header( "Content-Description: File Transfert");
                @readfile( $file );
                exit;
         // }
         // else
         // {
         //     Files::exit_Error("�� ���� ��� ���� �����  ,Error Download :".$file);
        //  }
    }


	function exit_Error($error,$Title='')
    {
        if($Title == '') $Title = "����� ������";
         print ('<div dir=rtl style="padding: 2%; border: 1px solid red; font-size: 125%">');
         printf("%20s", "$Title :- ");
         printf("%15s", $error);
         print ('</div>');
        exit;
	}
}
?>