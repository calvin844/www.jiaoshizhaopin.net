<?php

/*
 * 74cms ajax返回
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$ajax_str = "";
if ($act == "index_search_type") {
    if (strpos($_GET['id'], '.')) {
        $id = explode('.', $_GET['id']);
        $id = $id[1];
    } else {
        $id = intval($_GET['id']);
    }
    $type = $_GET['type'];
    $son = !empty($_GET['son']) ? $_GET['son'] : "li";
    if ($type == "city_box") {
        $table = "category_district";
        $suffix_str = "省";
    } elseif ($type == "type_box") {
        $table = "category_jobs";
        $suffix_str = "";
    }
    if ($id > 0) {
        $sql = "select * from " . table($table) . " where id = " . $id . " order by category_order desc,id asc";
        $parent = $db->getone($sql);
        if ($son == "option") {
            $ajax_str .= '<option disabled >请选择</option>';
        } else {
            $ajax_str .= '<li val="' . $id . '.0.0">' . $parent['categoryname'] . $suffix_str . '</li>';
        }
    }
    $sql = "select * from " . table($table) . " where parentid = " . $id . " order by category_order desc,id asc";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        if ($son == "option") {
            $ajax_str .= '<option value="0.' . $r['parentid'] . '.' . $r['id'] . '">' . $r['categoryname'] . '</option>';
        } else {
            $ajax_str .= '<li val="' . $r['parentid'] . '.' . $r['id'] . '.0">' . $r['categoryname'] . '</li>';
        }
    }
    exit($ajax_str);
} elseif ($act == "page_search_getcn") {
    //var_dump($_GET['id']);
    $id = intval($_GET['id']);
    $table = trim($_GET['table']);
    $get = trim($_GET['get']);
    $sql = "select " . $get . " from " . table($table) . " where id = " . $id;
    $result = $db->getone($sql);
    //$ajax_str .='<input type="hidden" value="'.$result[$get].'" name="getcn">';
    exit($result[$get]);
} elseif ($act == "get_cities") {
    $parent_district_id = intval($_GET['id']);
    $sql = "select * from " . table("category_district") . " where parentid = {$parent_district_id} order by category_order desc,id asc";
    $ajax_str = "<option disabled>选择城市</option><option value='0'>全部</option>";
    $result_arr = $db->getall($sql);
    foreach ($result_arr as $r) {
        $ajax_str .= "<option value='" . $r['id'] . "'>" . $r['categoryname'] . "</option>";
    }
    exit($ajax_str);
}
?>