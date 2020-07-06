<?php
 /*
 * 74cms 高级简历详细页面
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
$alias="QS_manager_resumeshow";
require_once(dirname(__FILE__).'/../include/common.inc.php');
if($mypage['caching']>0){
        $smarty->cache =true;
		$smarty->cache_lifetime=$mypage['caching'];
	}else{
		$smarty->cache = false;
	}
$cached_id=$_CFG['subsite_id']."|".$alias.(isset($_GET['id'])?"|".(intval($_GET['id'])%100).'|'.intval($_GET['id']):'').(isset($_GET['page'])?"|p".intval($_GET['page'])%100:'');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
	if ($_SESSION['uid']=='' || $_SESSION['username']=='' || intval($_SESSION['uid'])===0)
	{
	header("Location:".url_rewrite('QS_login')."?url=".$_SERVER["REQUEST_URI"]);
	}
	elseif ($_SESSION['utype']!='1' && $_SESSION['utype']!='3') 
	{
	$link[0]['text'] = "会员中心";
	$link[0]['href'] = url_rewrite('QS_login');
	showmsg('只有猎头顾问或企业可以查看经理人简历！',1,$link);
	}
	$user=$db->getone('select * from '.table('members')." where uid={$_SESSION['uid']} limit 1");
	if($_SESSION['utype']=='1'){
	
		$url0 = get_member_url(1,true).'company_user.php?act=user_status';
		$url1 = get_member_url(1,true).'company_index.php?act=';
	}elseif($_SESSION['utype']=='3'){
		$url0= get_member_url(3,true).'hunter_user.php?act=user_status';
		$url1 = get_member_url(3,true).'hunter_index.php?act=';
	}
	if ($user['status']=="2" && $act!='index' && $act!='user_status'  && $act!='user_status_save') 
	{
		$link[0]['text'] = "设置账号状态";
		$link[0]['href'] = $url0;
		$link[1]['text'] = "返回会员中心首页";
		$link[1]['href'] = $url1;
	exit(showmsg('您的账号处于暂停状态，请先设为正常后进行操作！',1,$link));	
	}
	elseif (empty($user))
	{
	unset($_SESSION['utype'],$_SESSION['uid'],$_SESSION['username']);
	header("Location:".url_rewrite('QS_login')."?url=".$_SERVER["REQUEST_URI"]);
	}

unset($dbhost,$dbuser,$dbpass,$dbname);
$smarty->display($mypage['tpl'],$cached_id);
$db->close();
unset($smarty);
?>