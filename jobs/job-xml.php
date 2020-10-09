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
$sql = "select * from " . table('jobs') . " WHERE company_audit = 1 AND audit = 1 AND display = 1 ORDER BY id desc LIMIT 2000";
$job_list = $db->getall($sql);
//$xml = "<html><head></head><body>";
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
//$xml .= "<xsl:stylesheetxmlns:xsl=\"http://www w3 org/1999/XSL/Transform\" version=\"1.0\">\n";
$xml .= "<urlset>\n";
foreach ($job_list as $data) {
    $xml .= "<url>\n";
    $xml .= "<loc>http://www.jiaoshizhaopin.net/job/" . $data['id'] . ".html</loc>\n";
    $xml .= "<lastmod>" . date("Y-m-d", $data['refreshtime']) . "</lastmod>\n";
    $xml .= "<changefreq>weekly</changefreq>\n";
    $xml .= "<priority>1.0</priority>\n";
    $xml .= "<data>\n";
    $xml .= "<display>\n";
    $xml .= "<wapurl>http://m.jiaoshizhaopin.net/job/detail?job_id=" . $data['id'] . "</wapurl>\n";
    //$data['jobs_name'] = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $data['jobs_name']);
    //$data['jobs_name'] = htmlspecialchars($data['jobs_name'], ENT_COMPAT, 'ISO-8859-1');
    $data['jobs_name'] = trim($data['jobs_name']);
    $xml .= "<title><![CDATA[" . $data['jobs_name'] . "]]></title>\n";
    $sql = "select * from " . table('category_jobs') . " where id = " . intval($data['category']);
    $parent_job_cn = $db->getall($sql);
    $parent_job_cn = $parent_job_cn['categoryname'];
    $xml .= "<jobfirstclass>" . $parent_job_cn . "</jobfirstclass>\n";
    $job_cn = "";
    if ($data['subclass'] > 0) {
        $sql = "select * from " . table('category_jobs') . " where id = " . intval($data['subclass']);
        $job_cn = $db->getone($sql);
        $job_cn = $job_cn['categoryname'];
    }
    $xml .= "<jobsecondclass>" . $job_cn . "</jobsecondclass>\n";
    $xml .= "<number>" . $data['amount'] == 0 ? "若干" : $data['amount'] . "人</number>\n";
    $xml .= "<age>" . $data['age'] . "</age>\n";
    $xml .= "<sex>" . $data['sex_cn'] . "</sex>\n";
    $data['contents'] = htmlspecialchars($data['contents'], ENT_COMPAT, 'ISO-8859-1');
    $data['contents'] = stripInvalidXml($data['contents']);
    $xml .= "<description><![CDATA[" . $data['contents'] . "]]></description>\n";
    $xml .= "<education>" . $data['education_cn'] . "</education>\n";
    $xml .= "<experience>" . $data['experience_cn'] . "</experience>\n";
    $xml .= "<startdate>" . date("Y-m-d", $data['addtime']) . "</startdate>\n";
    $xml .= "<enddate>" . date("Y-m-d", $data['deadline']) . "</enddate>\n";
    $xml .= "<salary>" . $data['wage_cn'] . "</salary>\n";
    $city_arr = explode("/", $data['district_cn']);
    $xml .= "<city>" . $city_arr[0] . "</city>\n";
    !empty($city_arr[1]) ? $xml .= "<district>" . $city_arr[1] . "</district>\n" : $xml .= "<district></district>\n";
    $xml .= "<location>" . $data['district_cn'] . " " . $data['companyname'] . "</location>\n";
    $xml .= "<type>" . $data['nature_cn'] . "</type>\n";
    $xml .= "<officialname>" . $data['companyname'] . "</officialname>\n";
    $xml .= "<commonname></commonname>\n";
    $sql = "select * from " . table('company_profile') . " where uid = " . intval($data['uid']);
    $company = $db->getone($sql);
    !empty($company['address']) ? $xml .= "<companyaddress><![CDATA[" . htmlspecialchars($company['address'], ENT_COMPAT, 'ISO-8859-1') . "]]></companyaddress>\n" : $xml .= "<companyaddress>" . $data['district_cn'] . "</companyaddress>\n";
    !empty($company['nature_cn']) ? $xml .= "<employertype>" . $company['nature_cn'] . "</employertype>\n" : $xml .= "<employertype>民营</employertype>\n";
    $xml .= "<size>" . $company['scale_cn'] . "</size>\n";
    !empty($company['contents']) ? $xml .= "<companydescription><![CDATA[" . htmlspecialchars($company['contents'], ENT_COMPAT, 'ISO-8859-1') . "]]></companydescription>\n" : $xml .= "<companydescription>" . $data['companyname'] . "</companydescription>\n";
    $xml .= "<industry>教育/培训</industry>\n";
    $xml .= "<secondindustry>教育/培训</secondindustry>\n";
    $xml .= "<companyID>" . $company['id'] . "</companyID>\n";
    $xml .= "<source>中国教师招聘网</source>\n";
    $xml .= "<sourcelink>http://www.jiaoshizhaopin.net/</sourcelink>\n";
    $xml .= "<joblink>http://www.jiaoshizhaopin.net/job/" . $data['id'] . ".html</joblink>\n";
    $xml .= "<jobwapurl>http://m.jiaoshizhaopin.net/job/detail?job_id=" . $data['id'] . "</jobwapurl>\n";
    $xml .= "<oauthjoburl>https://openapi.baidu.com/oauth/2.0/authorize?response_type=code&amp;client_id=VgFfilVwuLofFu3pWuYaPk2s&amp;redirect_uri=http://m.jiaoshizhaopin.net/job/detail?job_id=" . $data['id'] . "&amp;display=mobile</oauthjoburl>\n";
    $xml .= "<company_id></company_id>\n";
    $xml .= "<promise></promise>\n";
    $xml .= "<pcoauthjoburl>https://openapi.baidu.com/oauth/2.0/authorize?response_type=code&amp;client_id=VgFfilVwuLofFu3pWuYaPk2s&amp;redirect_uri=http://www.jiaoshizhaopin.net/job/" . $data['id'] . ".html&amp;display=page</pcoauthjoburl>\n";
    $xml .= "<phone>" . $company['telephone'] . "</phone>\n";
    $xml .= "<is_direct>0</is_direct>\n";
    $xml .= "</display>\n";
    $xml .= "</data>\n";
    $xml .= "</url>\n";
}
$xml .= "</urlset>";
//$xml .= "</body></html>";
//$xml = htmlspecialchars($xml,ENT_COMPAT,'ISO-8859-1');
$xml = iconv("gb2312", "utf-8//IGNORE", $xml);
//echo  $xml;exit;
file_put_contents("/data2/www/www.jiaoshizhaopin.net/data/job-xml.xml", $xml);

//$myfile = fopen("/data2/www/test.jiaoshizhaopin.net/data/job-xml.xml", "w") or die("Unable to open file!");
//fwrite($myfile, $xml);
//fclose($myfile);


$jobui_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
//$xml .= "<xsl:stylesheetxmlns:xsl=\"http://www w3 org/1999/XSL/Transform\" version=\"1.0\">\n";
$jobui_xml .= "<jobs>\n";
foreach ($job_list as $data) {
    $jobui_xml .= "<position>\n";
    $jobui_xml .= "<postionNO><![CDATA[" . $data['id'] . "]]></postionNO>\n";
    $jobui_xml .= "<positionName><![CDATA[" . $data['jobs_name'] . "]]></positionName>\n";
    $jobui_xml .= "<time><![CDATA[" . date("Y-m-d H:i:s", $data['refreshtime']) . "]]></time>\n";
    $jobui_xml .= "<endDay><![CDATA[" . date("Y-m-d", $data['deadline']) . "]]></endDay>\n";
    $jobui_xml .= "<area><![CDATA[" . $data['district_cn'] . "]]></area>	\n";
    $jobui_xml .= "<companyName><![CDATA[" . $data['companyname'] . "]]></companyName>\n";
    $data['contents'] = htmlspecialchars($data['contents'], ENT_COMPAT, 'ISO-8859-1');
    $data['contents'] = stripInvalidXml($data['contents']);
    $jobui_xml .= "<positionInfo><![CDATA[" . $data['contents'] . "]]></positionInfo>\n";
    $jobui_xml .= "<companyIndustry><![CDATA[教育/培训]]></companyIndustry>\n";
    $sql = "select * from " . table('company_profile') . " where uid = " . intval($data['uid']);
    $company = $db->getone($sql);
    $company['contents'] = !empty($company['contents']) ? htmlspecialchars($company['contents'], ENT_COMPAT, 'ISO-8859-1') : $data['companyname'];
    $jobui_xml .= "<companyInfo><![CDATA[" . $company['contents'] . "]]></companyInfo>\n";
    $jobui_xml .= "<email><![CDATA[" . $company['email'] . "]]></email>\n";
    $jobui_xml .= "<url><![CDATA[http://www.jiaoshizhaopin.net/job/" . $data['id'] . ".html]]></url>\n";
    $jobui_xml .= "<pay><![CDATA[" . $data['wage_cn'] . "]]></pay>\n";
    $jobui_xml .= "<degree><![CDATA[" . $data['education_cn'] . "]]></degree>\n";
    $jobui_xml .= "<jobAge><![CDATA[" . $data['experience_cn'] . "]]></jobAge>\n";
    $jobui_xml .= "<jobType><![CDATA[全职]]></jobType>\n";
    $jobui_xml .= "<workerNum><![CDATA[" . $company['scale_cn'] . "]]></workerNum>\n";
    $jobui_xml .= "</position>\n";
}
$jobui_xml .= "</jobs>\n";
//$xml .= "</body></html>";
//$xml = htmlspecialchars($xml,ENT_COMPAT,'ISO-8859-1');
$jobui_xml = iconv("gb2312", "utf-8//IGNORE", $jobui_xml);
//echo  $xml;exit;
file_put_contents("/data2/www/www.jiaoshizhaopin.net/data/jobui.xml", $jobui_xml);

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