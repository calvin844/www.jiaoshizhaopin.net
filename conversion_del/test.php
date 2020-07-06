<?php

/*
 * 74cms 网站首页
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.tmp.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
//require_once('conversion_cngfig.php');
//$id=intval($_GET['id']);

$sql = "select id from " . table("article") . " where is_url = '" . $_POST['is_url'] . "' or title LIKE '%" . $_POST['title'] . "%'";
$result = $db->query($sql);
if (empty($result['id'])) {
    $f_id = FALSE;
    $data['content'] = trim($_POST['content']);
    if (!empty($data['content'])) {
        $data['is_url'] = $_POST['is_url'];
        $data['title'] = trim($_POST['title']);
        $data['audit'] = 2;
        $data['endtime'] = $_POST['time'];
        $f_id = inserttable(table('article'), $data, 1);
    }
    if ($f_id) {
        return TRUE;
    } else {
        return FALSE;
    }
}
return FALSE;
?>