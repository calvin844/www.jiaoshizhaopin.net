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

$sql = "select * from " . table('jobs') . " WHERE company_audit = 1 AND audit = 1 AND display = 1 ORDER BY addtime desc LIMIT 10";
$job_list = $db->getall($sql);
$sql = "select * from " . table('company_profile') . " WHERE audit = 1 ORDER BY addtime desc LIMIT 10";
$company_list = $db->getall($sql);
$sql = "select * from " . table('article') . " WHERE audit = 1 ORDER BY addtime desc LIMIT 20";
$article_list = $db->getall($sql);
$sql = "select * from " . table('jiaoshi_article_jobs') . " ORDER BY id desc LIMIT 20";
$article_jobs_list = $db->getall($sql);
$sql = "select * from " . table('category_district') . " ORDER BY id";
$category_district = $db->getall($sql);




$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xml .= "<urlset>\n";
$xml .= " <url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>always</changefreq>\n";
$xml .= "<priority>1.0</priority>\n";
$xml .= "</url>\n";
$xml .= "<url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/youer</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>weekly</changefreq>\n";
$xml .= "<priority>0.8</priority>\n";
$xml .= "</url>\n";
$xml .= "<url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/zhongxiaoxue</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>weekly</changefreq>\n";
$xml .= "<priority>0.8</priority>\n";
$xml .= "</url>\n";
$xml .= "<url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/peixunjigou</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>weekly</changefreq>\n";
$xml .= "<priority>0.8</priority>\n";
$xml .= "</url>\n";
$xml .= "<url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/TeachingJobs</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>weekly</changefreq>\n";
$xml .= "<priority>0.8</priority>\n";
$xml .= "</url>\n";
foreach ($job_list as $data) {
    $xml .= "<url>\n";
    $xml .= "<loc>http://www.jiaoshizhaopin.net/job/" . $data['id'] . ".html</loc>\n";
    $xml .= "<lastmod>" . date("Y-m-d", $data['refreshtime']) . "</lastmod>\n";
    $xml .= "<changefreq>weekly</changefreq>\n";
    $xml .= "<priority>0.6</priority>\n";
    $xml .= "</url>\n";
}
$xml .= "<url>\n";
$xml .= "<loc>http://www.jiaoshizhaopin.net/company</loc>\n";
$xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
$xml .= "<changefreq>daily</changefreq>\n";
$xml .= "<priority>0.8</priority>\n";
$xml .= "</url>\n";
foreach ($company_list as $data) {
    $xml .= "<url>\n";
    $xml .= "<loc>http://www.jiaoshizhaopin.net/school/" . $data['id'] . ".html</loc>\n";
    $xml .= "<lastmod>" . date("Y-m-d", $data['refreshtime']) . "</lastmod>\n";
    $xml .= "<changefreq>weekly</changefreq>\n";
    $xml .= "<priority>0.6</priority>\n";
    $xml .= "</url>\n";
}
foreach ($category_district as $data) {
    $xml .= "<url>\n";
    if ($data['parentid'] > 0) {
        $sql = "select * from " . table('category_district') . " WHERE id = " . $data['parentid'];
        $p_district = $db->getone($sql);
        $xml .= "<loc>http://www.jiaoshizhaopin.net/" . $p_district['pinyin'] . "/" . $data['pinyin'] . "</loc>\n";
        $xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
        $xml .= "<changefreq>daily</changefreq>\n";
        $xml .= "<priority>0.8</priority>\n";
    } else {
        $xml .= "<loc>http://www.jiaoshizhaopin.net/" . $data['pinyin'] . "</loc>\n";
        $xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
        $xml .= "<changefreq>daily</changefreq>\n";
        $xml .= "<priority>0.8</priority>\n";
    }
    $xml .= "</url>\n";
}
foreach ($article_list as $data) {
    $sql = "select * from " . table('category_district') . " WHERE id = " . $data['district'];
    $p_district = $db->getone($sql);
    $pinyin_str = $p_district['pinyin'];
    if ($data['sdistrict'] > 0) {
        $sql = "select * from " . table('category_district') . " WHERE id = " . $data['sdistrict'];
        $district = $db->getone($sql);
        $pinyin_str .= "/" . $district['pinyin'];
    }
    $xml .= "<url>\n";
    $xml .= "<loc>http://www.jiaoshizhaopin.net/" . $pinyin_str . "/jobshow_" . $data['id'] . ".html</loc>\n";
    $xml .= "<lastmod>" . date("Y-m-d", $data['refreshtime']) . "</lastmod>\n";
    $xml .= "<changefreq>weekly</changefreq>\n";
    $xml .= "<priority>0.6</priority>\n";
    $xml .= "</url>\n";
}
foreach ($article_jobs_list as $data) {
    $xml .= "<url>\n";
    $xml .= "<loc>http://www.jiaoshizhaopin.net/news_jobs/" . $data['id'] . ".html</loc>\n";
    $xml .= "<lastmod>" . date("Y-m-d") . "</lastmod>\n";
    $xml .= "<changefreq>weekly</changefreq>\n";
    $xml .= "<priority>0.6</priority>\n";
    $xml .= "</url>\n";
}
$xml .= "</urlset>\n";
$xml = iconv("gb2312", "utf-8//IGNORE", $xml);
//echo $xml;
//exit;
file_put_contents("/data2/www/www.jiaoshizhaopin.net/data/baidu_xml.xml", $xml);

//$myfile = fopen("/data2/www/test.jiaoshizhaopin.net/data/job-xml.xml", "w") or die("Unable to open file!");
//fwrite($myfile, $xml);
//fclose($myfile);

function stripInvalidXml($value) {
    $ret = "";
    $current;
    if (empty($value)) {
        return $ret;
    }

    $length = strlen($value);
    for ($i = 0; $i < $length; $i++) {
        $current = ord($value{$i});
        if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF))) {
            $ret .= chr($current);
        } else {
            $ret .= " ";
        }
    }
    return $ret;
}
?>