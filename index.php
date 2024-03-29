<?php

/*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!file_exists(dirname(__FILE__) . '/data/install.lock')) {
    header("Location:install/index.php");
}
define('IN_QISHI', true);
$alias = "QS_index";
require_once(dirname(__FILE__) . '/include/common.inc.php');

/*
  if (browser() == "mobile") {
  if (!empty($_SERVER['HTTP_REFERER']) && $_GET['is_wap'] == "") {
  if (strstr($_SERVER['HTTP_REFERER'], "http://")) {
  $referer_url = substr($_SERVER['HTTP_REFERER'], 7);
  }
  if (strstr($referer_url, "/")) {
  $referer_url = substr($referer_url, 0, strpos($referer_url, "/"));
  }
  $_CFG['main_domain'] = "http://" . $referer_url;
  } elseif ($_GET['is_wap'] == "") {
  header("location:" . $_CFG['wap_domain']);
  }
  }
 * 
 */
if (browser() == "mobile" && $_GET['is_wap'] == "") {
    header("location:" . $_CFG['wap_domain']);
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
    unset($dbhost, $dbuser, $dbpass, $dbname);
    $smarty->display($mypage['tpl'], $cached_id);
} else {
    $smarty->display($mypage['tpl'], $cached_id);
}
unset($smarty);
?>