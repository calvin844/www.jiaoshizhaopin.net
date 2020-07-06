<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$id = intval($_GET['id']);
$crons = $db->getone("select * from " . table('crons') . " WHERE  cronid='{$id}' LIMIT 1 ");
if (!empty($crons)) {
    if (!file_exists(QISHI_ROOT_PATH . "include/crons/" . $crons['filename'])) {
        echo '任务文件 ' . $crons['filename'] . ' 不存在！';
        exit;
    }
    require_once(QISHI_ROOT_PATH . "include/crons/" . $crons['filename']);
    echo '执行成功！';
    exit;
} else {
    echo '没有数据！';
    exit;
}
//adminmsg("执行成功！", 2);
?>