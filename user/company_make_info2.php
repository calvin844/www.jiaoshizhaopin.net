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
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
    header("Location: " . url_rewrite('QS_login') . "?act=logout");
    exit();
} elseif ($_SESSION['utype'] != '1') {
    $link[0]['text'] = "会员中心";
    $link[0]['href'] = url_rewrite('QS_login');
    showmsg('您访问的页面需要 企业会员 登录！', 1, $link);
}
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if ($act == "form") {
    $smarty->display('user/company_make_info2.htm');
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
    require_once(QISHI_ROOT_PATH . 'include/upload.php');
    !empty($_POST['contact']) ? $setsqlarr['contact'] = trim($_POST['contact']) : "";
    !empty($_POST['license']) ? $setsqlarr['license'] = trim($_POST['license']) : "";
    $setsqlarr['audit'] = 2; //添加默认审核中..
    if ($_SESSION['uid'] > 0) {
        updatetable(table('company_profile'), $setsqlarr, "uid='" . $_SESSION['uid'] . "'");
    }
    header("Location: /user/company_make_info3.php");
} elseif ($act == 'up_certificate') {
    !$_FILES['certificate']['name'] ? exit('请上传图片！') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $certificate_dir = "../data/certificate/" . date("Y/m/d/");
    make_dir($certificate_dir);
    $setsqlarr['certificate_img'] = _asUpFiles($certificate_dir, "certificate", 5000, 'gif/jpg/bmp/png', true);
    $setsqlarr['certificate_img'] = date("Y/m/d/") . $setsqlarr['certificate_img'];
    if ($_SESSION['uid'] > 0) {
        $wheresql = "uid='" . $_SESSION['uid'] . "'";
        write_memberslog($_SESSION['uid'], 1, 8002, $_SESSION['username'], "上传了营业执照");
        updatetable(table('jobs'), array('company_audit' => 2), $wheresql);
        updatetable(table('jobs_tmp'), array('company_audit' => 2), $wheresql);
        updatetable(table('company_profile'), $setsqlarr, $wheresql);
        exit($setsqlarr['certificate_img']);
    }
}
?>