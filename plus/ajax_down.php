<?php

/*
 * 74cms ajax ��ϵ��ʽ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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
            $html = "��ҵע�Ტ��¼��������ظ�������";
        }
        $uid = $_SESSION['uid'];
        if ($uid > 0 && $resume_uid > 0 && $show) {
            $apply = $db->getone("SELECT * FROM " . table('personal_jobs_apply') . " WHERE company_uid = '" . $uid . "' AND personal_uid = '" . $resume_uid . "'");
            $down = $db->getone("SELECT * FROM " . table('company_down_resume') . " WHERE company_uid = '" . $uid . "' AND resume_uid = '" . $resume_uid . "'");
            if (empty($apply) && empty($down)) {
                $show = false;
                $html = "��û������Ȩ�ޣ��������ظü���";
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