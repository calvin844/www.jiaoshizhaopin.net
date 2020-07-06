<?php
/*
 * 74cms 个人会员中心(课程申请)
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu',"apply");
if ($act=='apply_course')
{
	require_once(QISHI_ROOT_PATH.'include/page.class.php');
	$wheresql=" WHERE personal_uid='{$_SESSION['uid']}' ";
	$aetlook=intval($_GET['aetlook']);
	if($aetlook>0)
	{
	$wheresql.=" AND personal_look='{$aetlook}'";
	}	
	$perpage=10;
	$total_sql="SELECT COUNT(*) AS num FROM ".table('personal_course_apply')." AS a {$wheresql} ";
	$total_val=$db->get_total($total_sql);
	$page = new page(array('total'=>$total_val, 'perpage'=>$perpage));
	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$smarty->assign('title','已申请的课程 - 个人会员中心 - '.$_CFG['site_name']);
	$smarty->assign('course_apply',get_apply_course($offset,$perpage,$wheresql));
	$smarty->assign('act',$act);
	$applycour_num=get_now_applycour_num($_SESSION['uid']);
	$smarty->assign('count_apply',array(30,$applycour_num,30-$applycour_num));
	$smarty->assign('page',$page->show(3));
	$count[0]=count_personal_cour_apply($_SESSION['uid'],1);
	$count[1]=count_personal_cour_apply($_SESSION['uid'],2);
	$count[2]=$count[0]+$count[1];
	$smarty->assign('count',$count);
	$smarty->display('member_personal/personal_apply_course.htm');
}
elseif ($act=='del_course_apply')
{
	$yid =!empty($_REQUEST['y_id'])?$_REQUEST['y_id']:showmsg("你没有选择项目！",1);
	$delete =trim($_POST['delete']);
	$delete?(!del_apply($yid,$_SESSION['uid'])?showmsg("删除失败！",0):showmsg("删除成功！",2)):'';
}
unset($smarty);
?>