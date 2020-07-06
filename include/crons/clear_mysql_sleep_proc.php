<?php

define('MAX_SLEEP_TIME', 120);
$hostname = "rm-bp19ls7c6h52w61lq.mysql.rds.aliyuncs.com";
$username = "jiaoshizhaopin";
$password = "tRfzV6sF";
$connect = mysql_connect($hostname, $username, $password);
$result = mysql_query("SHOWPROCESSLIST", $connect);
while ($proc = mysql_fetch_assoc($result)) {
    $list[] = $proc;
    if ($proc["Command"] == "Sleep" && $proc["Time"] > MAX_SLEEP_TIME) {
        @mysql_query("KILL" . $proc["Id"], $connect);
    }
}
var_dump($list);
mysql_close($connect);
?>