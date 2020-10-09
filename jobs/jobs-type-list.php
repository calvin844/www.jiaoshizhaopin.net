<?php

/*
 * 74cms 下载简历
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
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$type_name = !empty($_GET['type']) ? trim($_GET['type']) : "youer";
$nature = 62;
switch ($type_name) {
    case 'youer':
        $article_id_arr = "4";
        $jobs_id_arr = "2";
        $type_cn = "幼儿";
        $tp_name = "2019_jobs_type_list";
        break;
    case 'zhongxiaoxue':
        $article_id_arr = "1, 2, 3";
        $jobs_id_arr = "1, 6, 7";
        $type_cn = "中小学";
        $tp_name = "2019_jobs_type_list";
        break;
    case 'peixunjigou':
        $article_id_arr = "5, 8";
        $jobs_id_arr = "3, 196";
        $type_cn = "培训机构";
        $tp_name = "2019_jobs_type_list";
        break;
    default:
        $article_id_arr = "4";
        $jobs_id_arr = "2";
        $type_cn = "幼儿";
        $tp_name = "2019_jobs_type_list";
        break;
}
$sql = "select * from " . table('category_district') . " where parentid = 0 order by `category_order` desc limit 8";
$city = $db->getall($sql);
$sql = "select * from " . table('category_district') . " where parentid = 0 order by `category_order` desc";
$city_more = $db->getall($sql);
$sql = "select * from " . table('category_district') . " where parentid > 0 order by `job_count` desc,`category_order` desc limit 24";
$hot_city = $db->getall($sql);

$category_str = !empty($jobs_id_arr) ? " AND category IN(" . $jobs_id_arr . ") " : "";

$sql = "select * from " . table('jobs') . " WHERE deadline>" . time() . $category_str . " AND nature = " . $nature . " AND display = 1 AND audit = 1 ORDER BY emergency DESC, refreshtime DESC limit 6";
$emergency_tmp = $db->getall($sql);
foreach ($emergency_tmp as $e) {
    $e['d_cn'] = explode("/", $e['district_cn']);
    $e['d_cn'] = $e['d_cn'][1];
    $e['jobs_url'] = url_rewrite("QS_jobsshow", array('id' => $e['id']), true, $e['subsite_id']);
    $emergency[] = $e;
}
$sql = "select * from " . table('jobs') . " WHERE deadline>" . time() . $category_str . " AND nature = " . $nature . " AND display = 1 AND audit = 1 ORDER BY recommend DESC, refreshtime DESC limit 6";
$recommend_tmp = $db->getall($sql);
foreach ($recommend_tmp as $e) {
    $e['d_cn'] = explode("/", $e['district_cn']);
    $e['d_cn'] = $e['d_cn'][1];
    $e['jobs_url'] = url_rewrite("QS_jobsshow", array('id' => $e['id']), true, $e['subsite_id']);
    $recommend[] = $e;
}
$sql = "select * from " . table('jobs') . " WHERE deadline>" . time() . $category_str . " AND nature = " . $nature . " AND display = 1 AND audit = 1 ORDER BY click DESC, refreshtime DESC limit 6";
$hot_tmp = $db->getall($sql);
foreach ($hot_tmp as $e) {
    $e['d_cn'] = explode("/", $e['district_cn']);
    $e['d_cn'] = $e['d_cn'][1];
    $e['jobs_url'] = url_rewrite("QS_jobsshow", array('id' => $e['id']), true, $e['subsite_id']);
    $hot[] = $e;
}
if ($experience > 0) {
    $sql = "select * from " . table('jobs') . " WHERE deadline>" . time() . $category_str . " AND experience = " . $experience . " AND display = 1 AND audit = 1 ORDER BY click DESC, refreshtime DESC limit 6";
    $experience_tmp = $db->getall($sql);
    foreach ($experience_tmp as $e) {
        $e['d_cn'] = explode("/", $e['district_cn']);
        $e['d_cn'] = $e['d_cn'][1];
        $e['jobs_url'] = url_rewrite("QS_jobsshow", array('id' => $e['id']), true, $e['subsite_id']);
        $graduates[] = $e;
    }
}

$news_wheresql = " is_display=1 AND audit = 1 AND type_id IN(" . $article_id_arr . ")";
$n_orderbysql = " order by case when endtime > " . time() . " then 1 else 10 end,  refreshtime desc ";
$r = 10;
$te_result = array();
$te_article_sql = "SELECT * FROM " . table('article') . " WHERE " . $news_wheresql . " AND top > " . time() . " AND (top_level = 1 OR (top_level = 2) OR (top_level = 3 )) AND emergency > " . time() . " AND (emergency_level = 1 OR (emergency_level = 2) OR (emergency_level = 3)) order by top_month desc limit " . $r;
$te_result_tmp = $db->getall($te_article_sql);
if (!empty($te_result_tmp)) {
    foreach ($te_result_tmp as $t) {
        $t['top_flag'] = 1;
        $t['emergency_flag'] = 1;
        $top_tmp[] = $t['id'];
        $te_result[] = $t;
    }
    $r = $r - count($te_result_tmp);
}
if ($r > 0 && $r < 10) {
    $top_article_sql = "SELECT * FROM " . table('article') . " WHERE " . $news_wheresql . " AND top > " . time() . " AND (top_level = 1 OR (top_level = 2) OR (top_level = 3 )) AND emergency =0 order by top_month desc limit " . $r;
    $top_result_tmp = $db->getall($top_article_sql);
    if (!empty($top_result_tmp)) {
        foreach ($top_result_tmp as $t) {
            $t['top_flag'] = 1;
            $t['emergency_flag'] = 0;
            $top_tmp[] = $t['id'];
            $top_result[] = $t;
        }
        $r = $r - count($top_result);
        if (!empty($te_result)) {
            $te_result = array_merge($te_result, $top_result);
        } else {
            $te_result = $top_result;
        }
    }
}
if ($r > 0 && $r < 10) {
    $emergency_article_sql = "SELECT * FROM " . table('article') . " WHERE " . $news_wheresql . " AND top =0 AND emergency > " . time() . " AND (emergency_level = 1 OR (emergency_level = 2 ) OR (emergency_level = 3 )) order by top_month desc limit " . $r;
    $emergency_result_tmp = $db->getall($emergency_article_sql);
    if (!empty($emergency_result_tmp)) {
        foreach ($emergency_result_tmp as $t) {
            $t['top_flag'] = 0;
            $t['emergency_flag'] = 1;
            $top_tmp[] = $t['id'];
            $emergency_result[] = $t;
        }
        $r = $r - count($emergency_result);
        if (!empty($te_result)) {
            $te_result = array_merge($te_result, $emergency_result);
        } else {
            $te_result = $emergency_result;
        }
    }
}
if (!empty($top_tmp)) {
    $top_id = implode(",", $top_tmp);
    $news_wheresql .= " AND id NOT IN(" . $top_id . ") ";
    $r = $r < 0 ? 0 : $r;
}
$limit = " LIMIT 0," . $r;
$list_article_sql = "SELECT * FROM " . table('article') . " WHERE " . $news_wheresql . $n_orderbysql . $limit;
$parent_result = $db->getall($list_article_sql);
if (!empty($te_result)) {
    if ($r > 0) {
        $parent_result = array_merge($te_result, $parent_result);
    } else {
        $parent_result = $te_result;
    }
}
foreach ($parent_result as $row) {
    $sdistrict_id = $row['sdistrict'] ? $row['sdistrict'] : $row['district'];
    $sql = "select parentid,pinyin from " . table('category_district') . " where id = " . $sdistrict_id;
    $pinyin_res = get_mem_cache($sql, "getone");
    $sql = "select pinyin from " . table('category_district') . " where id = " . $pinyin_res['parentid'];
    $parent_pinyin_res = get_mem_cache($sql, "getone");
    $row['parent_pinyin'] = $parent_pinyin_res['pinyin'];
    $row['district_pinyin'] = $pinyin_res['pinyin'];
    $row['district_cn'] = explode("/", $row['district_cn']);
    if (!empty($row['district_cn'][1])) {
        $row['district_cn'] = $row['district_cn'][1];
    } else {
        $row['district_cn'] = $row['district_cn'][0];
    }

    if (!empty($row['is_url']) && $row['is_url'] != 'http://') {
        $row['url'] = $row['is_url'];
    } else {
        if (!empty($parent_pinyin_res)) {
            $row['url'] = "/" . $parent_pinyin_res['pinyin'] . "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
        } else {
            $row['url'] = "/" . $pinyin_res['pinyin'] . "/jobshow_" . $row['id'] . ".html";
        }
    }
    $row['content'] = $row['content'] ? $row['content'] : $row['contents'];
    $row['content'] = str_replace('&nbsp;', '', $row['content']);
    if ($aset['infolen'] > 0) {
        $row['briefly'] = cut_str(strip_tags($row['content']), $aset['infolen'], 0, $aset['dot']);
    }
    $row['addtime'] = date("Y-m-d", $row['addtime']);
    $row['refreshtime'] = date("Y-m-d", $row['refreshtime']);
    $article_list[] = $row;
}


$sql = "SELECT * FROM " . table('ad') . " WHERE ( starttime<=" . time() . "  OR starttime=0 ) AND (deadline>=" . time() . " OR deadline='0' ) AND is_display=1 ORDER BY show_order DESC,id DESC";
$ad = $db->getall($sql);
foreach ($ad as $a) {
    $ad_arr[$a['alias']][] = $a;
}

$smarty->assign('type_cn', $type_cn);
$smarty->assign('ad', $ad_arr);
$smarty->assign('city_more', $city_more);
$smarty->assign('city', $city);
$smarty->assign('hot_city', $hot_city);
$smarty->assign('emergency', $emergency);
$smarty->assign('recommend', $recommend);
$smarty->assign('hot', $hot);
$smarty->assign('graduates', $graduates);
$smarty->assign('type_name', $type_name);
$smarty->assign('article_list', $article_list);
$smarty->display($tp_name . '.htm');
?>