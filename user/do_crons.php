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
        echo '�����ļ� ' . $crons['filename'] . ' �����ڣ�';
        exit;
    }
    require_once(QISHI_ROOT_PATH . "include/crons/" . $crons['filename']);
    echo 'ִ�гɹ���';
    exit;
} else {
    echo 'û�����ݣ�';
    exit;
}
//adminmsg("ִ�гɹ���", 2);
?>