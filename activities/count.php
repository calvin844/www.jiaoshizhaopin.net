<?php

/*
 * 74cms ���ؼ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ��������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã��������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "save" : $_GET['act'];
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (empty($_SESSION['uid'])) {
    header("Location:/user/login.php?url=/activities/index.php");
} else {
    $sql = "select * from " . table('activities_20170410') . " where uid = " . $_SESSION['uid'];
    $user_up = $db->getone($sql);
    if (!empty($user_up)) {
        echo '<script language="javascript" type="text/javascript">alert("�����ύ���ˣ�ȥͶƱ��");window.location.href="/activities/details.php";</script>';
        exit;
    }
}
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
        $in['uid'] = $_SESSION['uid'];
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
        echo '<script language="javascript" type="text/javascript">alert("�ύ�ɹ���");window.location.href="/activities/details.php";</script>';
    } else {
        echo '<script language="javascript" type="text/javascript">alert("��������");window.location.href="/activities/index.php";</script>';
    }
}
?>