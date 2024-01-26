<?php
/*
+===========================================+
|      ArabPortal V2.2.x Copyright © 2008   |
|   -------------------------------------   |
|                     BY                    |
|              Arab Portal Team             |
|   -------------------------------------   |
|      Web: http://www.ArabPortal.Info      |
|   -------------------------------------   |
|  Last Updated: 08/08/2008 Time: 06:00 AM  |
+===========================================+
*/

if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

error_reporting  (E_ERROR | E_WARNING | E_PARSE);

if(is_file("./../install/index.php")){
$open = @fopen("./../install/SysMsg.tpl",r);
$data = @fread($open,@filesize("./../install/SysMsg.tpl"));
@fclose($open);
$data = str_replace('themes/portal','./../themes/portal',$data);
$data = str_replace('install/','./../install/',$data);
echo $data;
exit;
}

require_once("../func/protection.php");
require_once('conf.php');
if($CONF['class_folder'] == '')$CONF['class_folder']='aclass';
if(! @fopen($CONF['class_folder']."/index.html",r)){
exit("<br><br><center dir=rtl><b>⁄›Ê« ,,,  √ﬂœ «‰ «”„ „Ã·œ ⁄„·Ì«  «·„‘—› «·⁄«„ ÂÊ $CONF[class_folder]</b></center>");
}

require_once("lang/arabic.php");
require_once('../html/JavaScript.php');
require_once("../func/info.php");
require_once("../func/mysql.php");
require_once("../func/functions.php");
require_once('../func/email.php');
require_once('../func/counter.php');
require_once($CONF['class_folder']."/admin_func.php");
require_once('../func/Cache.php');
require_once('../func/Files.php');
require_once("../func/protection.php");

$apt = new func;

$apt->arrSetting  = $apt->settings();
$apt->upload_path = $apt->conf['upload_path'];

$admin = new admin_func;

if(count($_POST) > 0)
{
    $apt->check_referer();
}

function check_if_admin()
{
    global $apt,$admin;
    
   if(!$admin->is_login() === true)
   {
       $admin->login_form();
   }
}
?>