<?php

define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
if ($_SESSION['utype'] == 2) {
    echo '<script>alert("��ʹ����ҵ�˺Ű󶨣�");window.location.href="/user/login.php"</script>';
} else {
    $smarty->display('bind_company.htm');
}
?>