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
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "index" : $_GET['act'];
if ($act == "index") {
    $sql = "select * from " . table('online_course_info') . " where audit = 2 and (end_time > " . time() . " or end_time = 0) order by addtime desc limit 10";
    $course_data = $db->getall($sql);
    foreach ($course_data as $cd) {
        $sql = "select * from " . table('online_course_school') . " where id=" . $cd['school_id'];
        $school = $db->getone($sql);
        $cd['school'] = !empty($school) ? $school['name'] : "δ����������";
        $course_list[] = $cd;
    }
    $sql = "select * from " . table('online_course_type');
    $type_list = $db->getall($sql);
    $sql = "select * from " . table('article') . " where audit = 1 and is_display = 1 and endtime > " . time() . " and type_id = 7 order by addtime desc limit 9";
    $left_guide = $db->getall($sql);
    $sql = "select * from " . table('article') . " where audit = 1 and is_display = 1 and endtime > " . time() . " and type_id = 7 order by addtime desc limit 10,9";
    $right_guide = $db->getall($sql);
    $channel = array("url" => '/zhaokao', "name" => '�п�');
    $smarty->assign('channel', $channel);
    $smarty->assign('left_guide', $left_guide);
    $smarty->assign('right_guide', $right_guide);
    $smarty->assign('type_list', $type_list);
    $smarty->assign('course_list', $course_list);
    $smarty->display('zhaokao/index.htm');
}
?>