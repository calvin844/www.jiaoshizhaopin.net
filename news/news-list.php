<?php

/*
 * 74cms ��Ѷ�б�ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
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