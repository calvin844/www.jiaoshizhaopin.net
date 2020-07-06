<?php

/*
 * 74cms 支付方式
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
require_once(ADMIN_ROOT_PATH . 'include/admin_pay_fun.php');
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';
check_permissions($_SESSION['admin_purview'], "site_payment");
$smarty->assign('pageheader', "支付方式");
if ($act == 'list') {
    get_token();
    $smarty->assign('payment', get_payment());
    $smarty->display('pay/admin_payment_list.htm');
} elseif ($act == 'uninstall_payment') {
    check_token();
    uninstall_payment($_GET['id']) ? adminmsg('成功卸载', 2) : adminmsg('卸载失败', 1);
} elseif ($act == 'action_payment') {
    get_token();
    $api_path = "";
    $payment = get_payment_one($_GET['name']);
    //var_dump($payment);exit;
    $payment_url = $payment['typename'];
    if (!$payment) {
        adminmsg('获取失败', 1);
    }
    if (strstr($payment['typename'], 'alipayapi-')) {
        $api_path = "alipayapi/";
        $payment_url = "alipayapi";
    }
    require_once("../include/payment/" . $api_path . $payment_url . ".php");
    if (!function_exists("pay_info")) {
        $pay_info['p_introduction'] = "简短描述：";
        $pay_info['notes'] = "详细描述：";
        $pay_info['partnerid'] = "合作者身份(Partner ID)：";
        $pay_info['ytauthkey'] = "安全校验码(Key)：";
        $pay_info['fee'] = "交易手续费：";
        $pay_info['parameter1'] = "帐号：";
        $pay_info['parameter2'] = "参数2：";
        $pay_info['parameter3'] = "参数3：";
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
    $link[0]['text'] = "返回支付方式列表";
    $link[0]['href'] = '?';
    if ($setsqlarr['id'] > 0) {
        $wheresql = " id=" . $setsqlarr['id'] . " ";
        !updatetable(table('payment'), $setsqlarr, $wheresql) ? adminmsg('保存失败！', 1) : adminmsg('保存成功！', 2, $link);
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
        !inserttable(table('payment'), $setsqlarr) ? adminmsg('保存失败！', 1) : adminmsg('保存成功！', 2, $link);
    }
}
?>