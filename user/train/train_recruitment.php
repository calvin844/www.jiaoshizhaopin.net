<?php
/*
 * 74cms 培训课程申请操作中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/train_common.php');
$smarty->assign('leftmenu',"recruitment");
if ($act=='apply_course')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$wheresql=" WHERE train_uid='{$_SESSION['uid']}' ";
	$look=intval($_GET['look']);
	if($look>0)$wheresql.=" AND personal_look='{$look}'";
	$courseid=intval($_GET['courseid']);
	if($courseid>0)$wheresql.=" AND course_id='{$courseid}' ";
	$perpage=10;
	$total_sql="SELECT COUNT(*) AS num FROM ".table('personal_course_apply')." {$wheresql} ";
	$page = new page(array('total'=>$db->get_total($total_sql), 'perpage'=>$perpage));
	$offset=($page->nowindex-1)*$perpage;
	$smarty->assign('act',$act);
	$smarty->assign('title','收到的课程申请 - 培训会员中心 - '.$_CFG['site_name']);
	$smarty->assign('course_apply',get_apply_course($offset,$perpage,$wheresql));
	$smarty->assign('page',$page->show(3));
	$smarty->assign('count',count_course_apply($_SESSION['uid']));
	$smarty->assign('count1',count_course_apply($_SESSION['uid'],1));
	$smarty->assign('count2',count_course_apply($_SESSION['uid'],2));
	$smarty->assign('course',get_auditcourse($_SESSION['uid']));	
	$smarty->display('member_train/train_apply_course.htm');
}
elseif ($act=='set_apply_course')
{
	$yid =!empty($_REQUEST['y_id'])?$_REQUEST['y_id']:showmsg("你没有选择任何项目！",1);
	set_apply($yid,$_SESSION['uid'],2)?showmsg("设置成功！",2):showmsg("设置失败！",0);
}
elseif ($act=='del_apply')
{
	$yid =!empty($_REQUEST['y_id'])?$_REQUEST['y_id']:showmsg("你没有选择任何项目！",1);
	$delete =$_GET['delete'];
	$delete?(!del_apply($yid,$_SESSION['uid'])?showmsg("删除失败！",0):showmsg("删除成功！",2)):'';
}
unset($smarty);
?>