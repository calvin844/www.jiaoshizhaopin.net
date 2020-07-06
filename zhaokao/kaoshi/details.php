<?php

/*
 * 74cms ���ؼ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../../include/common.inc.php');
require_once(dirname(__FILE__) . '/../../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "index" : $_GET['act'];
if ($act == "index") {
    $course_id = !empty($_GET['cid']) ? intval($_GET['cid']) : 0;
    if ($course_id > 0) {
        $sql = "select * from " . table('online_course_info') . " where id = " . $course_id;
        $course = $db->getone($sql);
        $sql = "select * from " . table('online_course_type') . " where id = " . $course['type_id'];
        $course['type'] = $db->getone($sql);
        $sql = "select * from " . table('online_course_school') . " where id = " . $course['school_id'];
        $course['school'] = $db->getone($sql);
        $sql = "select * from " . table('online_course_teacher') . " where id = " . $course['teacher_id'];
        $course['teacher'] = $db->getone($sql);
        $sql = "select * from " . table('online_course_index') . " where course_id = " . $course['id'];
        $index = $db->getall($sql);
        foreach ($index as $i) {
            if ($i['parent_id'] == 0) {
                $course['index'][$i['id']] = $i;
            } else {
                $course['index'][$i['parent_id']]['son'][] = $i;
            }
        }
    }
    $channel = array("url" => '/zhaokao', "name" => '�п�');
    $smarty->assign('channel', $channel);
    $smarty->assign('course', $course);
    $smarty->display('zhaokao/details.htm');
} elseif ($act == "add_fav") {
    if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
        echo 3;
        exit;
    }
    $course_id = !empty($_GET['cid']) ? intval($_GET['cid']) : 0;
    $uid = intval($_SESSION['uid']);
    $data['uid'] = intval($_SESSION['uid']);
    $data['course_id'] = !empty($_GET['cid']) ? intval($_GET['cid']) : 0;
    $sql = "select * from " . table('online_course_favorite') . " where uid = " . $data['uid'] . " and course_id = " . $data['course_id'];
    $favorite = $db->getone($sql);
    if (!empty($favorite)) {
        echo 4;
        exit;
    }
    $data['addtime'] = time();
    if ($data['uid'] > 0 && $data['course_id'] > 0) {
        $db->insert("online_course_favorite", $data);
        echo 1;
    } else {
        echo 0;
    }
}
?>