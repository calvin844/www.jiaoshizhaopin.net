<?php

/*
 * 74cms 企业会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/company_common.php');
$smarty->assign('leftmenu', "index");
if ($act == 'index') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('title', '企业会员中心 - ' . $_CFG['site_name']);

    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $smarty->assign('loginlog', get_loginlog_one($uid, '1001'));
    //<-- 2018红包活动 
    /**
      $smarty->assign('spring2018_total1', $db->getone("SELECT SUM(coupons_value) AS total FROM " . table('act_spring2018') . " WHERE uid = " . $uid . " AND coupons_type = 2 AND state = 1"));
      $smarty->assign('spring2018_total2', $db->getone("SELECT SUM(coupons_value) AS total FROM " . table('act_spring2018') . " WHERE uid = " . $uid . " AND coupons_type = 3 AND state = 1"));
      $smarty->assign('spring2018_img', $db->getone("SELECT * FROM " . table('act_spring2018_img') . " WHERE uid = " . $uid . " AND utype = 1"));
     * 
     */
    //2018红包活动 -->

    $smarty->assign('user', $user);
    $limit_points = get_user_limit_points($uid);
    $smarty->assign('limit_points', $limit_points);
    $smarty->assign('points', get_user_points($uid));
    $smarty->assign('concern_id', get_concern_id($uid));
    $smarty->assign('company', $company_profile);
    if ($_CFG['operation_mode'] == '2' || $_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($uid);
        $smarty->assign('setmeal', $setmeal);
        if ($setmeal['endtime'] > 0) {
            $setmeal_endtime = sub_day($setmeal['endtime'], time());
            if (empty($setmeal_endtime)) {
                $smarty->assign('meal_min_remind', "已经到期");
            } else {
                $smarty->assign('meal_min_remind', $setmeal_endtime);
            }
        } else {
            $smarty->assign('meal_min_remind', "无限期");
        }
    }
    $smarty->assign('msg_total1', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='2' AND `replyuid`<>'{$uid}'"));
    $smarty->assign('msg_total2', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1' AND `replyuid`<>'{$uid}'"));
    $smarty->assign('total_noaudit_jobs', $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid=" . $uid . " AND audit=2"));
    $smarty->assign('total_nopass_jobs', $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid=" . $uid . " AND audit=3"));
    $smarty->assign('total_stop_jobs', $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs_tmp') . " WHERE uid=" . $uid . " AND display=2"));
    $smarty->assign('total_audit_jobs', $db->get_total("SELECT COUNT(*) AS num FROM " . table('jobs') . " WHERE uid=" . $uid));
    $smarty->assign('total_nolook_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('personal_jobs_apply') . " WHERE company_uid=" . $uid . " AND personal_look=1 AND resume_name !='' AND resume_id IN(SELECT id FROM " . table('resume') . " WHERE sex_cn !='' OR attachment_resume !='' )"));
    $smarty->assign('total_down_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_down_resume') . " WHERE company_uid=" . $uid));
    $smarty->assign('total_view_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('view_resume') . " WHERE uid=" . $uid));
    $smarty->assign('total_audit_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('resume')));
    $smarty->assign('total_favorites_resume', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_favorites') . " WHERE company_uid=" . $uid));
    $smarty->assign('total_interview', $db->get_total("SELECT COUNT(*) AS num FROM " . table('company_interview') . " WHERE company_uid=" . $uid));
    $date = strtotime("-1 day");
    $date = date("Y-m-d", $date);
    $sql = "select count  from " . table('jiaoshi_statistics_day') . " where name = 'USER_DAU' and date = '" . $date . "'";
    $resume_num = get_mem_cache($sql, "getone");
    $resume_num = $resume_num['count'] * 123 + rand(1000, 2500);
    $smarty->assign('resume_num', $resume_num);
    $smarty->display('member_company/company_index.htm');
}
//<-- 2018红包活动_上传收款码 
/**
  elseif ($act == 'spring2018_save') {
  $uid = intval($_SESSION['uid']);
  !$_FILES['wechat_img']['name'] ? exit('请上传图片！') : "";
  if ($uid < 1) {
  exit("-7");
  }
  require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
  $photo_dir = "../../data/spring2018_img/" . date("Y/m/d/");
  make_dir($photo_dir);
  $setsqlarr['path'] = _asUpFiles($photo_dir, "wechat_img", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
  $setsqlarr['path'] = date("Y/m/d/") . $setsqlarr['path'];
  if ($_REQUEST['ajax_path']) {
  exit($setsqlarr['path']);
  }
  if (!empty($setsqlarr['path'])) {
  $setsqlarr['uid'] = $uid;
  $setsqlarr['utype'] = 1;
  $setsqlarr['addtime'] = time();
  inserttable(table("act_spring2018_img"), $setsqlarr);
  exit($setsqlarr['path']);
  } else {
  exit("-10");
  }
  }
 * 
 */
//2018红包活动_上传收款码 -->
unset($smarty);
?>