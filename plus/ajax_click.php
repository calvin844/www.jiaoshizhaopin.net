<?php

/*
 * 74cms ajax 点击次数
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
$act = !empty($_GET['act']) ? trim($_GET['act']) : '';
if ($act == 'news_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('article') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('article') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'company_news_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('company_news') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('company_news') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'notice_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('notice') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('notice') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'jobs_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $db->query("update " . table('jobs') . " set click=click+1 WHERE id='{$id}'  LIMIT 1");
        $sql = "select click from " . table('jobs') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        $db->query("update " . table('jobs_search_hot') . " set click='{$val['click']}' WHERE id='{$id}'  LIMIT 1");
        exit($val['click']);
    }
} elseif ($act == 'resume_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $db->query("update " . table('resume') . " set click=click+1 WHERE id='{$id}'  LIMIT 1");
        $val = $db->getone("select click from " . table('resume') . " where id='{$id}'  LIMIT 1");
        exit($val['click']);
    }
} elseif ($act == 'company_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('company_profile') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('company_profile') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'jobfair_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('jobfair') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('jobfair') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'simple_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('simple') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('simple') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'simple_resume_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('simple_resume') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('simple_resume') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
}
//3.4
elseif ($act == 'course_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('course') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('course') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'train_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('train_profile') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('train_profile') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'teacher_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('train_teachers') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('train_teachers') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'train_news_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('train_news') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('train_news') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'hunterjobs_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('hunter_jobs') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('hunter_jobs') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
} elseif ($act == 'manager_resume_click') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $sql = "update " . table('manager_resume') . " set click=click+1 WHERE id='{$id}'  LIMIT 1";
        $db->query($sql);
        $sql = "select click from " . table('manager_resume') . " where id='{$id}'  LIMIT 1";
        $val = $db->getone($sql);
        exit($val['click']);
    }
}
?>