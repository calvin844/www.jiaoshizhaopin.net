<?php
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
if ($mypage['caching'] > 0) {
    $smarty->cache = true;
    $smarty->cache_lifetime = $mypage['caching'];
} else {
    $smarty->cache = false;
}
$smarty->display("404.htm");
unset($smarty);
exit();
?>