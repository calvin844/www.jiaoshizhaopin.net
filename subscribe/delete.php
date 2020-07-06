<?php

/*
 * 74cms 职位订阅
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
unset($dbhost, $dbuser, $dbpass, $dbname);
$email = trim($_GET['email']) ? strtolower($_GET['email']) : showmsg("请填写接收邮箱！", 1);
$ck_email = get_user_email($email);
if ($ck_email) {
    $where = " email='" . $email . "' ";
    deletetable(table("jobs_subscribe"), $where, 1);
    $href[] = array('text'=>"进入网站",'href'=>$_CFG["site_domain"] . $_CFG["site_dir"]);
    showmsg("退订成功！", 1,$href);
    //echo "<script type='text/javascript' language='javascript'> window.location.href='". $_CFG["site_domain"] . $_CFG["site_dir"] ."'</script>";
}else{
    showmsg("没找到相关订阅记录！", 1);
}

function get_user_email($email) {
    global $db;
    $ck_email = $db->getone("select 1 from " . table('jobs_subscribe') . " where email='" . $email . "'");
    return $ck_email;
}

?>