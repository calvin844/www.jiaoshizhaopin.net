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
$data['ad_name'] = trim($_GET['ad_name']);
$data['img'] = !empty($_GET['img']) ? trim($_GET['img']) : "";
$c_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
$url = explode("&url=", $c_url);
$data['url'] = trim($url[1]);
$data['click_time'] = time();
if (!empty($data['ad_name']) || !empty($data['url'])) {
    $match_num = preg_match_all("/^[\w\|]+$/U", $data['ad_name']);
    if ($match_num > 0) {
        $sql = "select * from " . table('ad_count_index') . " where ad_name = '" . $data['ad_name'] . "'";
        $ad_index = $db->getone($sql);
        $sql = "select * from " . table('ad_category') . " where alias = '" . $data['ad_name'] . "'";
        $ad = $db->getone($sql);
        $ad_arr = array('ad_101|0', 'ad_101|1', 'ad_101|2', 'ad_101|3', 'ad_101|4');
        if ((!empty($ad) || strstr($data['ad_name'], "wx_") || strstr($data['ad_name'], "count_bd") || in_array($data['ad_name'], $ad_arr)) && empty($ad_index)) {
            $data_index['ad_name'] = $data['ad_name'];
            $data_index['last_update'] = time();
            $sql = "select count(*) as total from " . table('ad_count') . " where ad_name = '" . $data['ad_name'] . "'";
            $total = $db->getone($sql);
            $data_index['total'] = $total['total'] + 1;
            $db->insert("ad_count_index", $data_index);
            $db->insert("ad_count", $data);
        } elseif ((!empty($ad) || strstr($data['ad_name'], "wx_") || strstr($data['ad_name'], "count_bd") || in_array($data['ad_name'], $ad_arr)) && !empty($ad_index)) {
            $data_index = array();
            if ($ad_index['last_update'] < strtotime("-1 week")) {
                $sql = "select count(*) as total from " . table('ad_count') . " where ad_name = '" . $data['ad_name'] . "'";
                $total = $db->getone($sql);
                $data_index['total'] = $total['total'] + 1;
                $data_index['last_update'] = time();
            } else {
                $data_index['total'] = $ad_index['total'] + 1;
            }
            updatetable(table('ad_count_index'), $data_index, " ad_name = '" . $data['ad_name'] . "'");
            $db->insert("ad_count", $data);
        }
    }
    header("Location: " . $data['url']);
}
?>