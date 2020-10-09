<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
if (browser() == "mobile") {
    header("location:" . $_CFG['wap_domain'] . "/article/article_jobs?article_job_id=" . intval($_GET['id']));
}
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$timestamp = time();
$other_job = "";
$job_id = !empty($_GET['id']) ? trim($_GET['id']) : 0;
if (intval($job_id) < 1) {
    header("Location:/");
}
$news_job_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE id='" . $job_id . "' limit 1";
$news_job = $db->getone($news_job_sql);
$other_job_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE article_id='" . $news_job['article_id'] . "' AND id !=" . $job_id;
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

$sdistrict_jobs_sql = "SELECT * FROM " . table('jobs') . " WHERE sdistrict =" . $news['sdistrict'] . " AND deadline > " . $timestamp . " ORDER BY refreshtime DESC LIMIT 6";
$sdistrict_jobs = $db->getall($sdistrict_jobs_sql);
if (count($sdistrict_jobs) < 6) {
    $district_jobs_sql = "SELECT * FROM " . table('jobs') . " WHERE district =" . $news['district'] . " AND deadline > " . $timestamp . " ORDER BY refreshtime DESC LIMIT " . intval(6 - count($sdistrict_jobs));
    $district_jobs = $db->getall($district_jobs_sql);
    if (!empty($sdistrict_jobs)) {
        $sdistrict_jobs = !empty($district_jobs) ? array_merge($sdistrict_jobs, $district_jobs) : $sdistrict_jobs;
    } else {
        $sdistrict_jobs = $district_jobs;
    }
}
if (count($sdistrict_jobs) < 6) {
    $article_jobs = "";
    $article_jobs_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE id !='" . $job_id . "' AND article_id IN(SELECT id FROM " . table('article') . " WHERE sdistrict =" . $news['sdistrict'] . " AND endtime > " . $timestamp . " ORDER BY refreshtime DESC) LIMIT " . intval(6 - count($sdistrict_jobs));
    $article_jobs_tmp = $db->getall($article_jobs_sql);
    if (!empty($article_jobs_tmp)) {
        foreach ($article_jobs_tmp as $a) {
            $a_sql = "SELECT * FROM " . table('article') . " WHERE id='" . $a['article_id'] . "' limit 1";
            $a['article'] = $db->getone($a_sql);
            $article_jobs[] = $a;
        }
    }
    if (!empty($sdistrict_jobs)) {
        $sdistrict_jobs = !empty($article_jobs) ? array_merge($sdistrict_jobs, $article_jobs) : $sdistrict_jobs;
    } else {
        $sdistrict_jobs = $article_jobs;
    }
}
if ($news_job['subclass'] > 0) {
    $subclass_jobs_sql = "SELECT * FROM " . table('jobs') . " WHERE subclass =" . $news_job['subclass'] . " AND deadline > " . $timestamp . " ORDER BY refreshtime DESC LIMIT 6";
    $subclass_jobs = $db->getall($subclass_jobs_sql);
    if (count($subclass_jobs) < 6) {
        $category_jobs_sql = "SELECT * FROM " . table('jobs') . " WHERE category =" . $news_job['category'] . " AND deadline > " . $timestamp . " ORDER BY refreshtime DESC LIMIT " . intval(6 - count($subclass_jobs));
        $category_jobs = $db->getall($category_jobs_sql);
        if (!empty($subclass_jobs)) {
            $subclass_jobs = !empty($category_jobs) ? array_merge($subclass_jobs, $category_jobs) : $subclass_jobs;
        } else {
            $subclass_jobs = $category_jobs;
        }
    }
    if (count($subclass_jobs) < 6) {
        $article_jobs = "";
        $article_jobs_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE id !='" . $job_id . "' AND subclass='" . $news_job['subclass'] . "' AND article_id IN(SELECT id FROM " . table('article') . " WHERE endtime > " . $timestamp . " ORDER BY refreshtime DESC) LIMIT " . intval(6 - count($subclass_jobs));
        $article_jobs_tmp = $db->getall($article_jobs_sql);
        if (!empty($article_jobs_tmp)) {
            foreach ($article_jobs_tmp as $a) {
                $a_sql = "SELECT * FROM " . table('article') . " WHERE id='" . $a['article_id'] . "' limit 1";
                $a['article'] = $db->getone($a_sql);
                $article_jobs[] = $a;
            }
        }
        if (!empty($subclass_jobs)) {
            $subclass_jobs = !empty($article_jobs) ? array_merge($subclass_jobs, $article_jobs) : $subclass_jobs;
        } else {
            $subclass_jobs = $article_jobs;
        }
    }
} else {
    $category_jobs_sql = "SELECT * FROM " . table('jobs') . " WHERE category =" . $news_job['category'] . " AND deadline > " . $timestamp . " ORDER BY refreshtime DESC LIMIT 6";
    $subclass_jobs = $db->getall($category_jobs_sql);
    if (count($subclass_jobs) < 6) {
        $article_jobs = "";
        $article_jobs_sql = "SELECT * FROM " . table('jiaoshi_article_jobs') . " WHERE id !='" . $job_id . "' AND category='" . $news_job['category'] . "' AND article_id IN(SELECT id FROM " . table('article') . " WHERE endtime > " . $timestamp . " ORDER BY refreshtime DESC) LIMIT " . intval(6 - count($subclass_jobs));
        $article_jobs_tmp = $db->getall($article_jobs_sql);
        if (!empty($article_jobs_tmp)) {
            foreach ($article_jobs_tmp as $a) {
                $a_sql = "SELECT * FROM " . table('article') . " WHERE id='" . $a['article_id'] . "' limit 1";
                $a['article'] = $db->getone($a_sql);
                $article_jobs[] = $a;
            }
        }
        if (!empty($subclass_jobs)) {
            $subclass_jobs = !empty($article_jobs) ? array_merge($subclass_jobs, $article_jobs) : $subclass_jobs;
        } else {
            $subclass_jobs = $article_jobs;
        }
    }
}

$smarty->assign('type_jobs', $subclass_jobs);
$smarty->assign('district_jobs', $sdistrict_jobs);
$smarty->assign('news_job', $news_job);
$smarty->assign('other_job', $other_job);
$smarty->assign('news', $news);
$smarty->display('news-jobs-show.htm');
?>