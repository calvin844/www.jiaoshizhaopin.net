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
$act = empty($_GET['act']) ? "index" : $_GET['act'];
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (time() > 1493136000) {
    //echo '<script language="javascript" type="text/javascript">alert("征集阶段已经过了，去投票吧！");window.location.href="/activities/list.php";</script>';
}
if (browser() == "mobile") {
    $url = "http://m.jiaoshizhaopin.net/activities/";
} else {
    $url = "/activities/";
}
if ($act == "index") {
    $resume = array();
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $smarty->assign('district', $district);
    $sql = "select count(*) as c from " . table('activities_20170410');
    $total = $db->getone($sql);
    $smarty->assign('total', $total['c']);
    $smarty->display('activities/index.htm');
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
}
?>