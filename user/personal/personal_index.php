<?php

/*
 * 74cms 个人会员中心
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
header('Location:/user/personal/personal_user.php?act=userprofile');
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/personal_common.php');
$smarty->assign('leftmenu', "index");
if ($act == 'index') {
    $uid = intval($_SESSION['uid']);
    $smarty->assign('title', '个人会员中心 - ' . $_CFG['site_name']);
    $smarty->assign('user', $user);
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    $smarty->assign('loginlog', get_loginlog_one($uid, '1001'));
    $wheresql = " WHERE uid='" . $_SESSION['uid'] . "' ";
    $sql = "SELECT * FROM " . table('resume') . $wheresql;
    $smarty->assign('rand', rand(1, 100));
    $smarty->assign('my_resume', get_resume_list($sql));
    $smarty->assign('count_resume', count_resume($uid));
    $smarty->assign('count_interview', count_interview($uid));
    $smarty->assign('count_apply', count_personal_jobs_apply($uid));
    $smarty->assign('count_attention_me', count_personal_attention_me($uid));
    $smarty->assign('msg_total1', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='1' AND `replyuid`<>'{$uid}' AND msgtype=1"));
    $smarty->assign('msg_total2', $db->get_total("SELECT COUNT(*) AS num FROM " . table('pms') . " WHERE (msgfromuid='{$uid}' OR msgtouid='{$uid}') AND `new`='2' AND `replyuid`<>'{$uid}' AND msgtype=1"));
    $smarty->display('member_personal/personal_index.htm');
} elseif ($act == 'ajax_get_interest_jobs') {
    global $_CFG;
    $uid = intval($_SESSION['uid']);
    $pid = intval($_GET['pid']);
    $html = "";
    $interest_id = get_interest_jobs_id_by_resume($uid, $pid);
    $jobs_list = get_interest_jobs_list($interest_id);
    if (!empty($jobs_list)) {
        foreach ($jobs_list as $k => $v) {
            $jobs_url = url_rewrite("QS_jobsshow", array("id" => $v['id']), true, $v['subsite_id']);
            $company_url = url_rewrite("QS_companyshow", array("id" => $v['company_id']));
            $html.='<div class="listtxt">
			  <div class="listc1"><a href="' . $jobs_url . '" target="_blank">' . $v["jobs_name"] . '</a></div>
			  <div class="listc2"><a href="' . $company_url . '" target="_blank">' . $v["companyname"] . '</a></div>
			  <div class="listc3">' . $v["district_cn"] . '</div>
			  <div class="listc4">' . $v["wage_cn"] . '</div>
			  <div class="listc5">' . date("Y-m-d", $v["refreshtime"]) . '</div>';
            $html.='<div class="clear"></div></div>';
        }
    }
    exit($html);
}
unset($smarty);
?>