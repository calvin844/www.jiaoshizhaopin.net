<?php

/*
 * ╫лй╕упф╦мЬ мЬу╬╣ьм╪
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$smarty->display('2017/map/index.htm');
?>