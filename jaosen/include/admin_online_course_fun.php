<?php

/*
 * 74cms �������� ��ҵ�û���غ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
}

function get_school($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $row_arr = $db->getall("SELECT * FROM " . table('online_course_school') . $get_sql . $limit);
    return $row_arr;
}

function get_school_list($audit = 2) {
    global $db;
    $result = $db->getall("SELECT * FROM " . table('online_course_school') . " WHERE audit = 2");
    return $result;
}

function del_school($id) {
    global $db;
    $db->query("delete from " . table('online_course_school') . " where id=" . $id);
    return true;
}

function get_school_one($id) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('online_course_school') . " where id=" . $id);
    return $result;
}

function get_teacher($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $row_arr = $db->getall("SELECT * FROM " . table('online_course_teacher') . $get_sql . $limit);
    return $row_arr;
}

function get_teacher_list($audit = 2) {
    global $db;
    $result = $db->getall("SELECT * FROM " . table('online_course_teacher') . " WHERE audit = " . $audit);
    return $result;
}

function get_teacher_list_by_school_id($school_id = 0, $audit = 2) {
    global $db;
    $result = $db->getall("SELECT * FROM " . table('online_course_teacher') . " WHERE school_id = " . $school_id . " and audit = " . $audit);
    return $result;
}

function del_teacher($id) {
    global $db;
    $db->query("delete from " . table('online_course_teacher') . " where id=" . $id);
    return true;
}

function get_teacher_one($id) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('online_course_teacher') . " where id=" . $id);
    return $result;
}

function get_type($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $row_arr = $db->getall("SELECT * FROM " . table('online_course_type') . $get_sql . $limit);
    return $row_arr;
}

function get_type_list($audit = 2) {
    global $db;
    $result = $db->getall("SELECT * FROM " . table('online_course_type'));
    return $result;
}

function del_type($id) {
    global $db;
    $db->query("delete from " . table('online_course_type') . " where id=" . $id);
    return true;
}

function get_type_one($id) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('online_course_type') . " where id=" . $id);
    return $result;
}

function get_info($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $row_arr = $db->getall("SELECT * FROM " . table('online_course_info') . $get_sql . $limit);
    return $row_arr;
}

function del_info($id) {
    global $db;
    $db->query("delete from " . table('online_course_info') . " where id=" . $id);
    return true;
}

function get_info_one($id) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('online_course_info') . " where id=" . $id);
    return $result;
}

function get_course_index($cid = 0, $pid = 0) {
    global $db;
    $pid = intval($pid);
    $cid = intval($cid);
    $sql = "select * from " . table('online_course_index') . " where course_id=" . $cid . " AND parent_id = " . $pid . "  order BY id asc";
    return $db->getall($sql);
}

function del_index($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $return = 0;
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('online_course_index') . " WHERE id IN (" . $sqlin . ") "))
            return false;
        $return = $return + $db->affected_rows();
        if (!$db->query("Delete from " . table('online_course_index') . " WHERE parent_id IN (" . $sqlin . ") "))
            return false;
        $return = $return + $db->affected_rows();
    }
    return $return;
}

function get_index_one($id) {
    global $db;
    $sql = "select * from " . table('online_course_index') . " WHERE id=" . intval($id) . " LIMIT 1";
    return $db->getone($sql);
}

function get_order($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $row_arr = $db->getall("SELECT * FROM " . table('online_course_buy_log') . $get_sql . $limit);
    return $row_arr;
}

function get_order_one($id) {
    global $db;
    $sql = "select * from " . table('online_course_buy_log') . " WHERE id=" . intval($id) . " LIMIT 1";
    return $db->getone($sql);
}

function del_order($id) {
    global $db;
    $db->query("delete from " . table('online_course_buy_log') . " where id=" . $id);
    return true;
}

function get_user_by_id($uid) {
    global $db;
    $row_arr = $db->getone("SELECT * FROM " . table('members') . " where uid = " . $uid);
    return $row_arr;
}

?>