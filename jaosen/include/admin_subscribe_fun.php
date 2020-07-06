<?php

/*
 * 74cms 微招聘
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
}

function get_subscribe_list($offset, $perpage, $sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT {$offset},{$perpage} ";
    $sql = "SELECT * FROM " . table('jobs_subscribe') . $sql . $limit;
    $result = $db->getall($sql);
    foreach($result as $r){
        if (empty($r['subject_id']) && !empty($r['search_name'])) {
            $subject_id = "";
            $sql = "select id,category_job_ids,subject_name from " . table("jiaoshi_subject");
            $subject_arr = $db->getall($sql);
            foreach ($subject_arr as $s) {
                if (strstr($r['search_name'], $s['subject_name'])) {
                    $subject_id .= $s['id'] . ",";
                }
            }
            if (empty($subject_id)) {
                foreach ($subject_arr as $s) {
                    $subject_id .= $s['id'] . ",";
                }
            }
            $subject_id = trim($subject_id, ",");
            $wheresqlarr = " id =" . $r['id'];
            updatetable(table("jobs_subscribe"), array("subject_id" => $subject_id), $wheresqlarr);
            $r['subject_id'] = $subject_id;
        } elseif (empty($r['subject_id']) && empty($r['search_name'])) {
            $db->query("Delete from " . table('jobs_subscribe') . " WHERE id='" . intval($value['id']) . "'");
            continue;
        }
        $subject_id = trim($r['subject_id'], ",");
        $subject_name = "";
        $sql = "select category_job_ids,subject_name from " . table("jiaoshi_subject") . " where id in(" . $subject_id . ")";
        $subject = $db->getall($sql);
        foreach ($subject as $s) {
            $subject_name .= $s['subject_name'] . ",";
        }
        $r['subject_name']  = trim($subject_name, ",");
        //var_dump($r);exit;
        $row_arr[] = $r;
    }
    return $row_arr;
}

function subscribe_del($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $return = 0;
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('jobs_subscribe') . " WHERE id IN (" . $sqlin . ")"))
            return false;
        $return = $return + $db->affected_rows();
    }
    return $return;
}

?>