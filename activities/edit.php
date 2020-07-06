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
$act = empty($_GET['act']) ? "edit" : $_GET['act'];
$mem = new Memcache;
$mem->connect("localhost", 11111);

if ($act == "edit") {
    $id = intval($_GET['id']) > 0 ? intval($_GET['id']) : header("Location:/user/login.php?url=/activities/list.php");
    $sql = "select * from " . table('activities_20170410') . " where id = " . $id;
    $item = $db->getone($sql);
    $photo_arr = explode("##", $item['photo']);
    foreach ($photo_arr as $key => $value) {
        $k = intval($key);
        $a['num'] = $k + 1;
        $a['path'] = $value;
        $item['photo_arr'][] = $a;
    }
    $item['photo_count'] = count($item['photo_arr']);
    $item['photo_count_cut'] = 5 - count($item['photo_arr']);
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $smarty->assign('district', $district);
    if ($item['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " where parentid = " . $item['district'];
        $sdistrict = $db->getall($sql);
        $smarty->assign('sdistrict', $sdistrict);
    }
    $smarty->assign('item', $item);
    $smarty->display('activities/edit.htm');
} else if ($act == "uploadphoto") {
    $num = intval($_REQUEST['num']);
    !$_FILES['photo' . $num]['name'] ? exit('请上传图片！') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $photo_dir = "../data/activities_photo/" . date("Y/m/d/");
    make_dir($photo_dir);
    $save_path = _asUpFiles($photo_dir, "photo" . $num, 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
    if (!empty($save_path)) {
        $path = $num . "##/data/activities_photo/" . date("Y/m/d/") . $save_path;
        exit($path);
    } else {
        exit("-8");
    }
} elseif ($act == "get_city") {
    $parent_id = intval($_GET['pid']);
    $sql = "select * from " . table('category_district') . " where parentid = " . $parent_id;
    $city = $db->getall($sql);
    $city_str = "";
    foreach ($city as $c) {
        $city_str.=$c['id'] . "-" . $c['categoryname'] . "||";
    }
    $city_str = trim($city_str, "||");
    echo $city_str;
} elseif ($act == "save") {
    if (!empty($_POST['photo'])) {
        foreach ($_POST['photo'] as $p) {
            if (!empty($p)) {
                $photo_arr[] = $p;
            }
        }
        $photo = implode("##", $photo_arr);
        $name = trim($_POST['name']);
        $district = intval($_POST['district']);
        $sdistrict = intval($_POST['sdistrict']);
        $school = trim($_POST['school']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $in['photo'] = $photo;
        $in['name'] = ($name);
        $in['district'] = $district;
        $in['sdistrict'] = $sdistrict;
        $in['name'] = ($name);
        $in['school'] = ($school);
        $in['address'] = ($address);
        $in['phone'] = ($phone);
        $in['title'] = ($title);
        $in['content'] = $content;
        $in['audit'] = 1;
        $db->update(table('activities_20170410'), $in, ' id = ' . intval($_POST['id']));
        echo '<script language="javascript" type="text/javascript">alert("提交成功！");window.location.href="/activities/details.php?id=' . intval($_POST['id']) . '";</script>';
    } else {
        echo '<script language="javascript" type="text/javascript">alert("参数错误！");window.location.href="/activities/index.php";</script>';
    }
}
?>