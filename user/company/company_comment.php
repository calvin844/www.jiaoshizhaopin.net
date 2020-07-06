<?php
/*
 * 74cms 职位评论
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/company_common.php');
if ($act=='comment_list')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$id=intval($_GET['jobsid']);
	$wheresql=" WHERE c.jobs_id='{$id}'";
	$joinsql=" LEFT JOIN   ".table('members')." AS m ON c.uid=m.uid LEFT JOIN  ".table('jobs')." AS j ON c.jobs_id=j.id ";
	$perpage=15;
	$total_sql="SELECT COUNT(*) AS num FROM ".table('comment')." AS c ".$wheresql;
	$total_val=$db->get_total($total_sql);
	$page = new page(array('total'=>$total_val, 'perpage'=>$perpage));
	$offset=($page->nowindex-1)*$perpage;
	$smarty->assign('title','职位评论 - 企业会员中心 - '.$_CFG['site_name']);
	$smarty->assign('list',get_comment_list($offset, $perpage,$joinsql.$wheresql));
	$smarty->assign('page',$page->show(3));
	$smarty->display('member_company/company_comment.htm');
}
elseif ($act=='comment_del')
{
	$id =!empty($_POST['id'])?$_POST['id']:$_GET['id'];
	$jobs_id=intval($_POST['jobs_id']);
	if (empty($id))
	{
	showmsg("你没有选择项目！",1);
	}
	if($n=del_company_comment($id,$jobs_id,$company_profile['id']))
	{
	showmsg("删除成功！共删除 {$n} 行",2);
	}
	else
	{
	showmsg("删除失败！",0);
	}
}

elseif($act == 'comment_audit')
{
	if ($_CFG['open_commentaudit']!="1" ||  $_CFG['com_commentaudit']!="1") exit('error'); 
	$id =!empty($_REQUEST['id'])?$_REQUEST['id']:showmsg("你没有选择职位评论！",1);
	$audit=intval($_REQUEST['audit']);
	$num=comment_audit($id,$audit);
	if ($num>0){showmsg("审核成功！共审核".$num."行",2);}else{showmsg("审核成功!共影响{$num}行",0);}
}

unset($smarty);
?>