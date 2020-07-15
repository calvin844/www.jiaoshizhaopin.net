<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
if (browser() == "mobile") {
    header("location:" . $_CFG['wap_domain'] . "/article/article_jobs?article_job_id=" . intval($_GET['id']));
}
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$other_job = "";
$job_id = !empty($_GET['id']) ? trim($_GET['id']) : 0;
if (intval($job_id) < 1) {
    header("Location:/");
}
$news_job_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE id='" . $job_id . "' limit 1";
$news_job = $db->getone($news_job_sql);
$other_job_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE article_id='" . $news_job['article_id'] . "'";
$other_job = $db->getall($other_job_sql);
$news_sql = "SELECT * FROM " . table('article') . " WHERE id='" . $news_job['article_id'] . "' limit 1";
$news = $db->getone($news_sql);
if (strstr($news['district_cn'], "/")) {
    $district_arr = explode("/", $news['district_cn']);
    $news['district_cn'] = $district_arr[0];
    $news['sdistrict_cn'] = $district_arr[1];
} else {
    $news['sdistrict_cn'] = $news['district_cn'];
}
$news['overdue'] = 1;
if ($news['endtime'] > 0 && time() > $news['endtime']) {
    $news['overdue'] = 2;
}
$sql = "select parentid,pinyin from " . table('category_district') . " where id = " . $news['sdistrict'];
$pinyin_res = get_mem_cache($sql, "getone");
$sql = "select pinyin from " . table('category_district') . " where id = " . $pinyin_res['parentid'];
$parent_pinyin_res = get_mem_cache($sql, "getone");
if (!empty($news['is_url']) && $news['is_url'] != 'http://') {
    $news['url'] = $news['is_url'];
} else {
    if (!empty($parent_pinyin_res)) {
        $news['url'] = "/" . $parent_pinyin_res['pinyin'] . "/" . $pinyin_res['pinyin'] . "/jobshow_" . $news['id'] . ".html";
    } else {
        $news['url'] = "/" . $pinyin_res['pinyin'] . "/jobshow_" . $news['id'] . ".html";
    }
}

$smarty->assign('news_job', $news_job);
$smarty->assign('other_job', $other_job);
$smarty->assign('news', $news);
$smarty->display('news-jobs-show.htm');
?>