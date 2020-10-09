<?php

/*
 * 74cms 个人
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);

$urls = array();
$time = time() - 86400;
$sql = "select * from " . table('jobs') . " WHERE company_audit = 1 AND audit = 1 AND display = 1 AND addtime > " . $time;
$job_list = $db->getall($sql);
foreach ($job_list as $j) {
    $urls[] = "http://www.jiaoshizhaopin.net/job/" . $j['id'] . ".html";
}

$sql = "select * from " . table('company_profile') . " WHERE audit = 1 AND addtime > " . $time;
$company_list = $db->getall($sql);
foreach ($company_list as $c) {
    $urls[] = "http://www.jiaoshizhaopin.net/school/" . $c['id'] . ".html";
}

$sql = "select * from " . table('article') . " WHERE audit = 1 AND click > 1 AND addtime > " . $time;
$article_list = $db->getall($sql);
foreach ($article_list as $a) {
    $sql = "select * from " . table('category_district') . " WHERE id = " . $a['district'];
    $p_district = $db->getone($sql);
    $pinyin_str = $p_district['pinyin'];
    if ($a['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " WHERE id = " . $a['sdistrict'];
        $district = $db->getone($sql);
        $pinyin_str .= "/" . $district['pinyin'];
    }
    $urls[] = "http://www.jiaoshizhaopin.net/" . $pinyin_str . "/jobshow_" . $a['id'] . ".html";
    $sql = "select * from " . table('jiaoshi_article_jobs') . " WHERE article_id ='" . $a['id'] . "'";
    $article_jobs_list = $db->getall($sql);
    if (!empty($article_jobs_list)) {
        foreach ($article_jobs_list as $aj) {
            $urls[] = "http://www.jiaoshizhaopin.net/news_jobs/" . $aj['id'] . ".html";
        }
    }
}
//var_dump($urls);
//exit;
$api = 'http://data.zz.baidu.com/urls?site=www.jiaoshizhaopin.net&token=XYUynnCV3AToA4LL';
$ch = curl_init();
$options = array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>