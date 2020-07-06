<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
if (empty($_POST)) {
    $s = $_GET['state'];
    $state_str = "";
    if ($s > -1) {
        $state_str = " and state =" . $s;
    }
    $sql = "select * from " . table('bd_log') . " where bd_name ='bd002 ' " . $state_str . " order by add_time desc";
    $all = $db->getall($sql);
    $smarty->assign('list', $all);
    $smarty->display('bd002/list.htm');
} else {
    $idstr = implode(',', $_POST['item']);
    $sql = "update " . table('bd_log') . " set state=1 where id in(" . $idstr . ")";
    $db->query($sql);
    header("Location:/bd002/list.php");
}
?>