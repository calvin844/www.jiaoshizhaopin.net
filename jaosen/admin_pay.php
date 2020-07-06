<?php

/*
 * 74cms ֧����ʽ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH . 'include/admin_pay_fun.php');
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';
check_permissions($_SESSION['admin_purview'], "site_payment");
$smarty->assign('pageheader', "֧����ʽ");
if ($act == 'list') {
    get_token();
    $smarty->assign('payment', get_payment());
    $smarty->display('pay/admin_payment_list.htm');
} elseif ($act == 'uninstall_payment') {
    check_token();
    uninstall_payment($_GET['id']) ? adminmsg('�ɹ�ж��', 2) : adminmsg('ж��ʧ��', 1);
} elseif ($act == 'action_payment') {
    get_token();
    $api_path = "";
    $payment = get_payment_one($_GET['name']);
    //var_dump($payment);exit;
    $payment_url = $payment['typename'];
    if (!$payment) {
        adminmsg('��ȡʧ��', 1);
    }
    if (strstr($payment['typename'], 'alipayapi-')) {
        $api_path = "alipayapi/";
        $payment_url = "alipayapi";
    }
    require_once("../include/payment/" . $api_path . $payment_url . ".php");
    if (!function_exists("pay_info")) {
        $pay_info['p_introduction'] = "���������";
        $pay_info['notes'] = "��ϸ������";
        $pay_info['partnerid'] = "���������(Partner ID)��";
        $pay_info['ytauthkey'] = "��ȫУ����(Key)��";
        $pay_info['fee'] = "���������ѣ�";
        $pay_info['parameter1'] = "�ʺţ�";
        $pay_info['parameter2'] = "����2��";
        $pay_info['parameter3'] = "����3��";
    } else {
        $pay_info = pay_info();
    }
    $smarty->assign('show', $payment);
    $smarty->assign('pay', $pay_info);
    $smarty->display('pay/admin_payment_action.htm');
} elseif ($act == 'add_payment') {
    get_token();
    $smarty->display('pay/admin_payment_action.htm');
} elseif ($act == 'save_payment') {
    check_token();
    $setsqlarr['id'] = !empty($_POST['id']) ? intval($_POST['id']) : 0;
    $setsqlarr['listorder'] = intval($_POST['listorder']);
    $setsqlarr['p_introduction'] = trim($_POST['p_introduction']);
    $setsqlarr['notes'] = trim($_POST['notes']);
    $setsqlarr['partnerid'] = trim($_POST['partnerid']);
    $setsqlarr['ytauthkey'] = trim($_POST['ytauthkey']);
    $setsqlarr['fee'] = floatval($_POST['fee']);
    $setsqlarr['parameter1'] = trim($_POST['parameter1']);
    $setsqlarr['parameter2'] = trim($_POST['parameter2']);
    $setsqlarr['parameter3'] = trim($_POST['parameter3']);
    $setsqlarr['p_install'] = 2;
    $link[0]['text'] = "����֧����ʽ�б�";
    $link[0]['href'] = '?';
    if ($setsqlarr['id'] > 0) {
        $wheresql = " id=" . $setsqlarr['id'] . " ";
        !updatetable(table('payment'), $setsqlarr, $wheresql) ? adminmsg('����ʧ�ܣ�', 1) : adminmsg('����ɹ���', 2, $link);
    } else {
        $setsqlarr['typename'] = trim($_POST['typename']);
        $setsqlarr['byname'] = trim($_POST['byname']);
        if (strstr($setsqlarr['typename'], 'alipayapi-')) {
            $payment = get_payment_one("alipay");
            $setsqlarr['partnerid'] = trim($payment['partnerid']);
            $setsqlarr['ytauthkey'] = trim($payment['ytauthkey']);
            $setsqlarr['fee'] = floatval($payment['fee']);
            $setsqlarr['parameter1'] = trim($payment['parameter1']);
        }
        !inserttable(table('payment'), $setsqlarr) ? adminmsg('����ʧ�ܣ�', 1) : adminmsg('����ɹ���', 2, $link);
    }
}
?>