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
//$wheresql = " WHERE uid = " . $_SESSION['uid'] . " audit = 1 AND license  REGEXP BINARY '^a_'";
$sql = "SELECT * FROM " . table('company_profile') . " WHERE uid = " . $_SESSION['uid'];
$result = $db->getone($sql);
$subject = !empty($result['license']) ? $result['license'] : "";
$pattern = '/^a_/';
preg_match($pattern, $subject, $matches);
$sql = "SELECT COUNT(*) AS num FROM " . table('company_jobs_limit_point_log') . " WHERE uid = " . $_SESSION['uid'];
$log = $db->getone($sql);
if (empty($result)) {
    $p = "您有价值&nbsp;<b style='color:red;font-size:36px;'>100元</b>&nbsp;的积分尚未领取";
    $span = "小积分，大作用，下载简历，查看联系方式都需要消耗积分哦";
    $str = "完善资料，立即领取";
    $url = "/user/company/company_info.php?act=company_profile";
} elseif (empty($result['license']) && $log['num'] == 0) {
    $p = "您有价值&nbsp;<b style='color:red;font-size:36px;'>100元</b>&nbsp;的积分尚未领取";
    $span = "小积分，大作用，下载简历，查看联系方式都需要消耗积分哦";
    $str = "认证执照，立即领取";
    $url = "/user/company/company_info.php?act=company_auth";
} elseif (($result['audit'] == 2 || (!empty($result['license']) && empty($matches))) && $log['num'] == 0) {
    $p = "您的信息已经提交，请耐心等待审核，先&nbsp;<b style='color:red;font-size:36px;'>发布职位</b>&nbsp;看看";
    $span = "";
    $str = "发布职位";
    $url = "/user/company/company_jobs.php?act=addjobs";
} elseif (($result['audit'] == 1 || (!empty($result['license']) && !empty($matches))) && $log['num'] == 0) {
    if (!empty($_GET['act']) && $_GET['act'] == "get_piont") {
        $setsqlarr['uid'] = $_SESSION['uid'];
        $setsqlarr['addtime'] = time();
        $insert_id = inserttable(table('company_jobs_limit_point_log'), $setsqlarr, true);
        if ($insert_id > 0) {
            set_members_limit_points($_SESSION['uid'], 1, 100, 30);
            $user = get_user_info($_SESSION['uid']);
            $points = get_user_points($user['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            write_memberslog(intval($_SESSION['uid']), 1, 9001, $user['username'], " 企业发布职位限时积分({$t}100)，(剩余积分:{$points}，剩余限时积分:{$user_limit_points})，备注：" . "企业发布职位限时积分", 1, 1012, "企业发布职位限时积分", "{$t}100", "{$points}");
            header("Location: /user/company/company_jobs_limit_point.php");
        }
    } else {
        $p = "您有价值&nbsp;<b style='color:red;font-size:36px;'>100元</b>&nbsp;的积分尚未领取";
        $span = "小积分，大作用，下载简历，查看联系方式都需要消耗积分哦";
        $str = "立即领取";
        $url = "/user/company/company_jobs_limit_point.php?act=get_piont";
    }
} elseif ($log['num'] > 0) {
    $p = "您已成功领取&nbsp;<b style='color:red;font-size:36px;'>100元</b>&nbsp;积分";
    $span = "";
    $str = "立即发布职位";
    $url = "/user/company/company_jobs.php?act=addjobs";
}
$smarty->assign('p', $p);
$smarty->assign('span', $span);
$smarty->assign('str', $str);
$smarty->assign('url', $url);
$smarty->display('member_company/company_jobs_limit_point.htm');
unset($smarty);
?>