<?php

/*
 * 74cms ���ؼ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (time() < 1493136000) {
    exit('4');
}
$id = intval($_POST['id']);
if ($id < 1) {
    exit('1');
}
$time_now = strtotime(date('Y-m-d', time()));
setcookie("vote_" . $id, "1", $time_now + 84600);
/**
  $user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
  $user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
  var_dump($_SERVER);
  exit;
 * 
 */
//$sql = "select * from " . table('activities_20170410_vote') . " WHERE ip = '" . $user_IP . "' AND addtime > " . (intval($time_now) - 86401) . " ORDER BY addtime desc LIMIT 1";
//$y_vote = $db->getone($sql);
if (!empty($_COOKIE["vote_" . $id])) {
    exit('2');
}
$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
$vote_log['pid'] = $id;
$vote_log['ip'] = $user_IP;
$vote_log['addtime'] = $time_now;
$db->insert('activities_20170410_vote', $vote_log);
$sql = "select max(id) as id from " . table('activities_20170410_vote') . " WHERE ip = '" . $user_IP . "' AND addtime = " . $time_now;
$item = $db->getone($sql);
if ($item['id'] > 0) {
    $db->query("UPDATE " . table('activities_20170410') . " SET `count` = count+1 WHERE id = " . $id);
}
exit('3');
?>