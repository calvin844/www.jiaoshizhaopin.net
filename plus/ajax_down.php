<?php

/*
 * 74cms ajax 联系方式
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : '';
if ($act == 'attachment_resume_down') {
    $show = TRUE;
    $resume_uid = trim($_GET['uid']);
    if ($resume_uid > 0) {
        if (!($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '1')) {
            $show = false;
            $html = "企业注册并登录后才能下载附件简历";
        }
        $uid = $_SESSION['uid'];
        if ($uid > 0 && $resume_uid > 0 && $show) {
            $apply = $db->getone("SELECT * FROM " . table('personal_jobs_apply') . " WHERE company_uid = '" . $uid . "' AND personal_uid = '" . $resume_uid . "'");
            $down = $db->getone("SELECT * FROM " . table('company_down_resume') . " WHERE company_uid = '" . $uid . "' AND resume_uid = '" . $resume_uid . "'");
            if (empty($apply) && empty($down)) {
                $show = false;
                $html = "您没有下载权限，请先下载该简历";
            }
        }
        if ($show) {
            exit("1");
        } else {
            exit($html);
        }
    }
}
?>