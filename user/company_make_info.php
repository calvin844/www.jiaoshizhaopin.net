<?php

/*
 * 74cms 下载简历
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
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if ($act == "form") {
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $sql = "select * from " . table('category') . " where c_alias = 'QS_company_type' order by c_id asc";
    $company_type = $db->getall($sql);
    $smarty->assign('district', $district);
    $smarty->assign('company_type', $company_type);
    $smarty->display('user/company_make_info.htm');
} elseif ($act == "get_city") {
    $parent_id = intval($_GET['pid']);
    $sql = "select * from " . table('category_district') . " where parentid = " . $parent_id;
    $city = $db->getall($sql);
    $city_str = "";
    foreach ($city as $c) {
        $city_str.=$c['id'] . "-" . $c['categoryname'] . "||";
    }
    $city_str = trim($city_str, "||");
    echo $city_str;
}
/*
  elseif ($act == "check_telephone") {
  $telephone = intval($_GET['phone']);
  $sql = "select * from " . table('company_profile') . " where telephone = " . $telephone;
  $t = $db->getone($sql);
  if (empty($t['id'])) {
  echo 1;
  } else {
  echo 0;
  }
  } elseif ($act == "send_code") {
  $mobile = intval($_GET['phone']);
  if ($mobile > 0) {
  $result = $mem->get($mobile);
  if (!$result) {
  $result = rand(1000, 9999);
  $mem->set($mobile, $result, 60);
  }
  $text = iconv("GB2312", "UTF-8//IGNORE", "【教师网】您的验证码是" . $result);
  $ch = curl_init();
  $apikey = "732be982abc9313b64561224601f70a3";
  $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
  curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  $json_data = curl_exec($ch);
  }
  }
 * 
 */ elseif ($act == "save") {
    $in['uid'] = $_SESSION['uid'];
    $in['telephone'] = $_SESSION['mobile'];
    $in['companyname'] = trim($_POST['companyname']);
    $in['companyname'] = empty($in['companyname']) ? showmsg('请填写公司名称', 1) : $in['companyname'];
    $nature_arr = explode("-", trim($_POST['nature']));
    $in['nature'] = $nature_arr[0];
    $in['nature_cn'] = $nature_arr[1];
    $in['district'] = intval($_POST['district']);
    $in['sdistrict'] = isset($_POST['sdistrict']) ? intval($_POST['sdistrict']) : 0;
    $in['yellowpages'] = 1;
    $sql = "select * from " . table('category_district') . " where id = " . $in['district'];
    $district_cn = $db->getone($sql);
    $district_cn = $district_cn['categoryname'];
    if ($in['sdistrict'] != 0) {
        $sql = "select * from " . table('category_district') . " where id = " . $in['sdistrict'];
        $sdistrict_cn = $db->getone($sql);
        $sdistrict_cn = !empty($sdistrict_cn) ? "/" . $sdistrict_cn['categoryname'] : "";
        $in['district_cn'] = $district_cn . $sdistrict_cn;
    }
    $in['email'] = $_SESSION['email'];
    $in['telephone'] = $_SESSION['mobile'];
    $in['address'] = trim($_POST['address']) ? trim($_POST['address']) : showmsg('请填写详细地址！', 1);
    $in['trade'] = 33;
    $in['trade_cn'] = "教育/培训";
    $in['addtime'] = $in['refreshtime'] = time();
    inserttable(table('company_profile'), $in);
    //header("Location: /user/company/company_index.php");
    header("Location: /user/company_make_info2.php");
}
?>