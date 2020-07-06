<?php
 /*
 * 74cms 会员登录
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
$alias="QS_login";
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
unset($dbhost,$dbuser,$dbpass,$dbname);
$smarty->caching = false;
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'login';
if($act == 'logout')
{
	unset($_SESSION['uid']);
	unset($_SESSION['username']);
	unset($_SESSION['utype']);
	setcookie("QS[uid]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie("QS[username]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie("QS[password]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie("QS[utype]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	unset($_SESSION['activate_username']);
	unset($_SESSION['activate_email']);
	header("location:index.php"); 
}
elseif(!$_SESSION['uid'] && !$_SESSION['username'] && !$_SESSION['utype'] &&  $_COOKIE['QS']['username'] && $_COOKIE['QS']['password'] )
{
	if(check_cookie($_COOKIE['QS']['username'],$_COOKIE['QS']['password']))
	{
	update_user_info($_COOKIE['QS']['username'],false,false);
			if($_SESSION['utype']==2)	header("location:personal/wap_user.php");
			if($_SESSION['utype']==1)	header("location:company/wap_user.php");
	}
	else
	{
	setcookie("QS[uid]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie('QS[username]',"", time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie('QS[password]',"", time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	setcookie("QS[utype]","",time() - 3600,$QS_cookiepath, $QS_cookiedomain);
	header("location:index.php"); 
	}
}
elseif ($_SESSION['username'] && $_SESSION['utype'] )
{
			if($_SESSION['utype']==2)	header("location:personal/wap_user.php");
			if($_SESSION['utype']==1)	header("location:company/wap_user.php");
}
elseif ($act=='login')
{
	$smarty->display('wap/wap_login.html');
}
elseif ($act == 'do_login')
{
	require_once(QISHI_ROOT_PATH.'include/fun_wap.php');
	$username=isset($_POST['username'])?trim($_POST['username']):"";
	$password=isset($_POST['password'])?trim($_POST['password']):"";
	$expire=isset($_POST['expire'])?intval($_POST['expire']):"";
	if ($username && $password)
	{
		if (wap_user_login($username,$password))
		{
			if($_SESSION['utype']==2)	header("location:personal/wap_user.php");
			if($_SESSION['utype']==1)	header("location:company/wap_user.php");
		}
		else
		{
			$smarty->caching = false;
			$smarty->assign('err',"<script>popbox.error('用户登录失败，用户名或密码错误。')</script>");
			$smarty->display("wap/wap_login.html");
		}		
	}
}

unset($smarty);
?>