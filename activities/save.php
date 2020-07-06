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
$act = empty($_GET['act']) ? "save" : $_GET['act'];
$mem = new Memcache;
$mem->connect("localhost", 11111);
if ($act == "save") {
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
        $in['uid'] = 0;
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
        $in['count'] = 0;
        $in['addtime'] = time();
        $db->insert('activities_20170410', $in);
        $sql = "select max(id) as id from " . table('activities_20170410');
        $item = $db->getone($sql);
        echo '<script language="javascript" type="text/javascript">alert("提交成功！您的作品正在审核中");window.location.href="/activities/details.php?id=' . $item['id'] . '";</script>';
    } else {
        echo '<script language="javascript" type="text/javascript">alert("参数错误！");window.location.href="/activities/index.php";</script>';
    }
}
?>