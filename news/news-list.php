<?php

/*
 * 74cms 资讯列表页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
$alias = "QS_newslist";
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
//$url_arr = explode("=", $_SERVER['QUERY_STRING']);
//$py = end($url_arr);
$district_py = $_GET['parent_py'];
$sdistrict_py = isset($_GET['district']) ? $_GET['district'] : "";

if (browser() == "mobile" && $_GET['is_wap'] == "") {
    header("location:" . $_CFG['wap_domain'] . "/article/district_py/" . $district_py . "/" . $sdistrict_py);
}
//var_dump($sdistrict_py);
//$page =intval($_GET['page'])>0?$_GET['page']:0;
if (empty($sdistrict_py) && $district_py != 'internship') {
    $sql = "select parentid from " . table('category_district') . " where pinyin='" . $district_py . "' limit 1;";
    $district = $db->getone($sql);
    if ($district['parentid'] == 0) {
        $mypage['tpl'] = "2019_article_district_list.htm";
    }
}
if ($mypage['caching'] > 0) {
    $smarty->cache = true;
    $smarty->cache_lifetime = $mypage['caching'];
} else {
    $smarty->cache = false;
}
$cached_id = $_CFG['subsite_id'] . "|" . $alias . (isset($_GET['id']) ? "|" . (intval($_GET['id']) % 100) . '|' . intval($_GET['id']) : '') . (isset($_GET['page']) ? "|p" . intval($_GET['page']) % 100 : '');
if (!$smarty->is_cached($mypage['tpl'], $cached_id)) {
    $smarty->display($mypage['tpl'], $cached_id);
    $db->close();
} else {
    $smarty->display($mypage['tpl'], $cached_id);
}
unset($smarty);
?>