<?php
/*
 * 74cms ��ͷ��Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/hunter_common.php');
$smarty->assign('leftmenu',"recruitment");
if ($act=='down_resume_list')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$perpage=10;
	$joinsql=" LEFT JOIN  ".table('resume')." as r ON d.resume_id=r.id ";
	$wheresql=" WHERE  d.user_uid='{$_SESSION['uid']}' ";
	$settr=intval($_GET['settr']);
	if($settr>0)
	{
	$settr_val=strtotime("-{$settr} day");
	$wheresql.=" AND d.down_addtime>{$settr_val} ";
	}
	$total_sql="SELECT COUNT(*) AS num FROM ".table('user_down_talent_resume')." as d".$wheresql;
	$total_val=$db->get_total($total_sql);
	$page = new page(array('total'=>$total_val, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$smarty->assign('title','�����صļ��� - ��ͷ���ʻ�Ա���� - '.$_CFG['site_name']);
	$smarty->assign('act',$act);
	$smarty->assign('list',get_down_manager_resume($offset,$perpage,$joinsql.$wheresql));
	$smarty->assign('page',$page->show(3));
	$smarty->display('member_hunter/hunter_down_resume.htm');
}
elseif ($act=='del_down')
{
	$yid =!empty($_REQUEST['y_id'])?$_REQUEST['y_id']:showmsg("��û��ѡ�����ؼ�¼��",1);
	if ($n=del_down_manager($yid,$_SESSION['uid']))
	{
	showmsg("ɾ���ɹ�����ɾ�� {$n} ��",2);
	}
	else
	{
	showmsg("ʧ�ܣ�",0);
	}
}
unset($smarty);
?>