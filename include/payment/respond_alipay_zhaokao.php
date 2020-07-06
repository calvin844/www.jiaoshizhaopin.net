<?php

/*
 * 74cms 支付响应页面
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
require_once(QISHI_ROOT_PATH . 'include/fun_zhaokao.php');
require_once(QISHI_ROOT_PATH . "include/payment/alipay.php");
if (respond()) {
    $link[0]['text'] = "查看课程";
    $link[0]['href'] = "/user/personal/personal_user.php?act=online_course";
    showmsg("付款成功！", 1, $link);
} else {
    $link[0]['text'] = "返回首页";
    $link[0]['href'] = "/zhaokao";
    showmsg("付款失败！请联系网站管理员", 0, $link);
}
?>
