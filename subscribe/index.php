<?php

/*
 * 74cms 资讯首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
$alias = "QS_subscribe";
require_once(dirname(__FILE__) . '/../include/common.inc.php');
if ($_PLUG['subscribe']['p_install'] == 1) {
    $link[0]['text'] = "返回首页";
    $link[0]['href'] = $_CFG['main_domain'];
    showmsg("管理员已关闭此模块!", 1, $link);
}
if ($mypage['caching'] > 0) {
    $smarty->cache = true;
    $smarty->cache_lifetime = $mypage['caching'];
} else {
    $smarty->cache = false;
}
$cached_id = $_CFG['subsite_id'] . "|" . $alias . (isset($_GET['id']) ? "|" . (intval($_GET['id']) % 100) . '|' . intval($_GET['id']) : '') . (isset($_GET['page']) ? "|p" . intval($_GET['page']) % 100 : '');
if (!$smarty->is_cached($mypage['tpl'], $cached_id)) {
    require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
    $db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
    $smarty->display($mypage['tpl'], $cached_id);
    $db->close();
} else {
    $smarty->display($mypage['tpl'], $cached_id);
}
unset($smarty);
?>