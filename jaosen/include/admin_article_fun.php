<?php

/*
 * 74cms 管理中心共用数据调用函数
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

function get_news($offset, $perpage, $sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT a.*,c.id as cid,c.categoryname as c_categoryname,p.id as pid,p.categoryname as p_categoryname FROM " . table('article') . " AS a " . $sql . "  " . $limit);
    while ($row = $db->fetch_array($result)) {
        $tit_color = $row['tit_color'] ? "color:" . $row['tit_color'] . ";" : '';
        $tit_b = $row['tit_b'] > 0 ? "font-weight:bold;" : '';
        $tit_style = $tit_color || $tit_b ? "style=\"" . $tit_color . $tit_b . "\"" : '';
        $Small_img = $row['Small_img'] ? "<span style=\"color:#009900\">(图)</span>" : '';
        if (!empty($row['is_url']) && $row['is_url'] != 'http://') {
            $row['url'] = $row['is_url'];
        } else {
            $row['url'] = url_rewrite('QS_newsshow', array('id' => $row['id']), true, $row['subsite_id']);
        }
        $row['url_title'] = "<a href=\"" . $row['url'] . "\" target=\"_blank\" " . $tit_style . ">" . $row['title'] . "</a> " . $Small_img . "";
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_news($id) {
    global $db, $thumb_dir, $upfiles_dir;
    if (!is_array($id)) {
        $id = array($id);
    }
    foreach ($id as $val) {
        $sql_img = "select Small_img from " . table('article') . " where id=" . intval($val) . " LIMIT 1";
        $y_img = $db->getone($sql_img);
        @unlink($upfiles_dir . "/" . $y_img['Small_img']);
        @unlink($thumb_dir . $y_img['Small_img']);
        del_article_jobs($val);
        //$db->query("Delete from  " . table('article') . " where id=" . intval($val) . " LIMIT 1");
        //$db->query("Delete from  " . table('jiaoshi_article_jobs') . " where article_id=" . intval($val));
        //$db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where parent_id=" . intval($val) . " and type = 'article'");
        
    }
    return true;
}

function get_job_category($ids) {
    global $db;
    if (isset($ids['parentid']) && isset($ids['id'])) {
        $where = " id = " . $ids['id'] . " and parentid = " . $ids['parentid'];
    } elseif (isset($ids['parentid']) && !isset($ids['id'])) {
        $where = " parentid = " . $ids['parentid'];
    } elseif (!isset($ids['parentid']) && isset($ids['id'])) {
        $where = " id = " . $ids['id'];
    }
    $sql = "select * from " . table('category_jobs') . " where " . $where;
    $category_list = get_mem_cache($sql, "getall");
    return $category_list;
}

function get_article_category() {
    global $db;
    $sql = "select * from " . table('article_category') . " where parentid=0  ORDER BY category_order desc";
    $category_list = $db->getall($sql);
    if (is_array($category_list)) {
        foreach ($category_list as $v) {
            $list[] = array("id" => $v['id'], "categoryname" => $v['categoryname'], "parentid" => $v['parentid']);
            $sqlf = "select * from " . table('article_category') . " where parentid=" . $v['id'] . "  ORDER BY category_order desc";
            $category_f = $db->getall($sqlf);
            if (is_array($category_f)) {
                foreach ($category_f as $vs) {
                    $list[] = array("id" => $vs['id'], "categoryname" => "|-" . $vs['categoryname'], "parentid" => $vs['parentid']);
                }
            }
        }
    }
    return $list;
}

function get_article_category_one($id) {
    global $db;
    $sql = "select * from " . table('article_category') . " where id=" . intval($id) . " LIMIT 1";
    $var = $db->getone($sql);
    return $var;
}

function get_article_parentid($val) {
    global $db;
    $sql = "select * from " . table('article_category') . " where id=" . intval($val) . "  LIMIT 1";
    $category = $db->getone($sql);
    return $category['parentid'];
}

function del_article_jobs($id) {
    global $db;
    $db->query("Delete from " . table('jiaoshi_article_jobs') . " WHERE article_id = " . $id);
    $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where parent_id=" . intval($id) . " and type = 'article'");
}

function del_category($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $return = 0;
    foreach ($id as $sid) {
        $sql = "select * from " . table('article_category') . " where id=" . intval($sid) . "  LIMIT 1";
        $category = $db->getone($sql);
        if ($category['parentid'] == "0") {
            if (!$db->query("Delete from " . table('article_category') . " WHERE (parentid =" . intval($sid) . " OR id =" . intval($sid) . ")  AND admin_set<>1"))
                return false;
            $return = $return + $db->affected_rows();
        }
        else {
            if (!$db->query("Delete from " . table('article_category') . " WHERE id =" . intval($sid) . " AND admin_set<>1"))
                return false;
            $return = $return + $db->affected_rows();
        }
    }
    return $return;
}

function del_property($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $return = 0;
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('article_property') . " WHERE id IN (" . $sqlin . ")  AND admin_set<>1"))
            return false;
        $return = $return + $db->affected_rows();
    }
    return $return;
}

function article_refresh($id) {
    global $db;
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $id)) {
        $db->query("UPDATE " . table('article') . " SET refreshtime = " . time() . " WHERE id = " . $id." and endtime > ". time());
        if($db->affected_rows()>0){
            $return = $return + $db->affected_rows();
            updateindex(table('article'),$id);
        }else{
            $return = FALSE;
        }
    }
    return $return;
}

function get_article_property_one($id) {
    global $db;
    $sql = "select * from " . table('article_property') . " where id=" . intval($id) . " LIMIT 1";
    $var = $db->getone($sql);
    return $var;
}

?>