<?php

/*
 * 74cms ��ҵ��Ա����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
    $p = "���м�ֵ&nbsp;<b style='color:red;font-size:36px;'>100Ԫ</b>&nbsp;�Ļ�����δ��ȡ";
    $span = "С���֣������ã����ؼ������鿴��ϵ��ʽ����Ҫ���Ļ���Ŷ";
    $str = "�������ϣ�������ȡ";
    $url = "/user/company/company_info.php?act=company_profile";
} elseif (empty($result['license']) && $log['num'] == 0) {
    $p = "���м�ֵ&nbsp;<b style='color:red;font-size:36px;'>100Ԫ</b>&nbsp;�Ļ�����δ��ȡ";
    $span = "С���֣������ã����ؼ������鿴��ϵ��ʽ����Ҫ���Ļ���Ŷ";
    $str = "��ִ֤�գ�������ȡ";
    $url = "/user/company/company_info.php?act=company_auth";
} elseif (($result['audit'] == 2 || (!empty($result['license']) && empty($matches))) && $log['num'] == 0) {
    $p = "������Ϣ�Ѿ��ύ�������ĵȴ���ˣ���&nbsp;<b style='color:red;font-size:36px;'>����ְλ</b>&nbsp;����";
    $span = "";
    $str = "����ְλ";
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
            write_memberslog(intval($_SESSION['uid']), 1, 9001, $user['username'], " ��ҵ����ְλ��ʱ����({$t}100)��(ʣ�����:{$points}��ʣ����ʱ����:{$user_limit_points})����ע��" . "��ҵ����ְλ��ʱ����", 1, 1012, "��ҵ����ְλ��ʱ����", "{$t}100", "{$points}");
            header("Location: /user/company/company_jobs_limit_point.php");
        }
    } else {
        $p = "���м�ֵ&nbsp;<b style='color:red;font-size:36px;'>100Ԫ</b>&nbsp;�Ļ�����δ��ȡ";
        $span = "С���֣������ã����ؼ������鿴��ϵ��ʽ����Ҫ���Ļ���Ŷ";
        $str = "������ȡ";
        $url = "/user/company/company_jobs_limit_point.php?act=get_piont";
    }
} elseif ($log['num'] > 0) {
    $p = "���ѳɹ���ȡ&nbsp;<b style='color:red;font-size:36px;'>100Ԫ</b>&nbsp;����";
    $span = "";
    $str = "��������ְλ";
    $url = "/user/company/company_jobs.php?act=addjobs";
}
$smarty->assign('p', $p);
$smarty->assign('span', $span);
$smarty->assign('str', $str);
$smarty->assign('url', $url);
$smarty->display('member_company/company_jobs_limit_point.htm');
unset($smarty);
?>