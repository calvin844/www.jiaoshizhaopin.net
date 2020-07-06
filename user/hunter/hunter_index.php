<?php
/*
 * 74cms 猎头会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(__FILE__).'/hunter_common.php');
$smarty->assign('leftmenu',"index");

if ($act=='index')
{
	$uid=intval($_SESSION['uid']);
	$smarty->assign('title','猎头会员中心 - '.$_CFG['site_name']);
	require_once(QISHI_ROOT_PATH.'include/fun_user.php');
	$smarty->assign('loginlog',get_loginlog_one($uid,'1001'));
	$smarty->assign('user',$user);
	$smarty->assign('points',get_user_points($uid));
	$smarty->assign('concern_id',get_concern_id($uid));
	$smarty->assign('hunter',$hunter_profile);
	if ($_CFG['operation_hunter_mode']=='2')
	{
		$setmeal=get_user_setmeal($uid);
		$smarty->assign('setmeal',$setmeal);
		if ($setmeal['endtime']>0)
		{
					$setmeal_endtime=sub_day($setmeal['endtime'],time());
					if (empty($setmeal_endtime))
					{
						$setmeal_endtime_days="已到期,请您重新申请";
					}
					 else
					 {
						 $setmeal_endtime_days="还有".$setmeal_endtime."到期";
					 }
					$smarty->assign('setmeal_endtime_days',$setmeal_endtime_days);
					if (time()>$setmeal['endtime'])
					{
						$smarty->assign('meal_min_remind',"已经到期");
					}
					else
					{
						$smarty->assign('meal_min_remind',$setmeal_endtime);
						
					}
		}else{
			$smarty->assign('meal_min_remind',"无限期");
		}
	}
	$smarty->assign('msg_total1',$db->get_total("SELECT COUNT(*) AS num FROM ".table('pms')." WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1' AND `replyuid`<>'{$uid}' AND msgtype=1"));
	$smarty->assign('msg_total2',$db->get_total("SELECT COUNT(*) AS num FROM ".table('pms')." WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1' AND `replyuid`<>'{$uid}' AND msgtype=2"));
	$smarty->assign('total_audit_jobs',$db->get_total("SELECT COUNT(*) AS num FROM ".table('hunter_jobs')." WHERE uid=".$uid." AND audit=1"));
	$smarty->assign('total_down_resume',$db->get_total("SELECT COUNT(*) AS num FROM ".table('user_down_talent_resume')." WHERE user_uid=".$uid));
	$smarty->display('member_hunter/hunter_index.htm');
}
unset($smarty);
?>