<?php
 /*
 * 74cms �����˼����б�
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
*/
define('IN_QISHI', true);
$alias="QS_manager_resumelist";
require_once(dirname(__FILE__).'/../include/common.inc.php');
if($mypage['caching']>0){
        $smarty->cache =true;
		$smarty->cache_lifetime=$mypage['caching'];
	}else{
		$smarty->cache = false;
	}
$cached_id=$_CFG['subsite_id']."|".$alias.(isset($_GET['id'])?"|".(intval($_GET['id'])%100).'|'.intval($_GET['id']):'').(isset($_GET['page'])?"|p".intval($_GET['page'])%100:'');
if(!$smarty->is_cached($mypage['tpl'],$cached_id))
{
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
unset($dbhost,$dbuser,$dbpass,$dbname);
	if ($_SESSION['uid']=='' || $_SESSION['username']=='' || intval($_SESSION['uid'])===0)
	{
	header("Location:".url_rewrite('QS_login')."?url=".$_SERVER["REQUEST_URI"]);
	}
	elseif ($_SESSION['utype']!='1' && $_SESSION['utype']!='3') 
	{
	$link[0]['text'] = "��Ա����";
	$link[0]['href'] = url_rewrite('QS_login');
	showmsg('ֻ����ͷ���ʻ���ҵ���Բ鿴�����˼�����',1,$link);
	}
	$user=$db->getone('select * from '.table('members')." where uid={$_SESSION['uid']} limit 1");
	if($_SESSION['utype']=='1'){
		$url0 = 'company_user.php?act=user_status';
		$url1 = 'company_index.php?act=';
	}elseif($_SESSION['utype']=='3'){
		$url0= 'hunter_user.php?act=user_status';
		$url1 = 'hunter_index.php?act=';
	}
	if ($user['status']=="2" && $act!='index' && $act!='user_status'  && $act!='user_status_save') 
	{
		$link[0]['text'] = "�����˺�״̬";
		$link[0]['href'] = $url0;
		$link[1]['text'] = "���ػ�Ա������ҳ";
		$link[1]['href'] = $url1;
	exit(showmsg('�����˺Ŵ�����ͣ״̬��������Ϊ��������в�����',1,$link));	
	}
	elseif (empty($user))
	{
	unset($_SESSION['utype'],$_SESSION['uid'],$_SESSION['username']);
	header("Location:".url_rewrite('QS_login')."?url=".$_SERVER["REQUEST_URI"]);
	}

$smarty->display($mypage['tpl'],$cached_id);
$db->close();
}
else
{
$smarty->display($mypage['tpl'],$cached_id);
}
unset($smarty);
?>