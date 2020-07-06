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
require_once(dirname(__FILE__) . '/personal/personal_common.php');
$act = empty($_GET['act']) ? "form" : $_GET['act'];
if ($act == "form") {
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    $smarty->assign('district', $district);
    $smarty->display('user/make_info.htm');
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
 * elseif ($act == "get_jobs") {
  $parent_id = intval($_GET['pid']);
  $sql = "select * from " . table('category_jobs') . " where parentid = " . $parent_id;
  $jobs = $db->getall($sql);
  $jobs_str = "";
  foreach ($jobs as $c) {
  $jobs_str.=$c['id'] . "-" . $c['categoryname'] . "||";
  }
  $jobs_str = trim($jobs_str, "||");
  echo $jobs_str;
  }
  elseif ($act == "check_telephone") {
  $telephone = intval($_GET['phone']);
  $sql = "select * from " . table('resume') . " where telephone = " . $telephone;
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
    //$telephone = empty($_POST['telephone']) ? exit('<script type="text/javascript" language="javascript">alert("请填写手机号！");window.location.href="/user/user_make_info.php";</script> ') : $_POST['telephone'];
    //if ($mem->get($telephone) == intval($_POST['code'])) {
    $in['uid'] = $_SESSION['uid'];
    $members = "";
    $sql = "select * from " . table('members') . " where uid = " . $in['uid'];
    $members = $db->getone($sql);
    $in['telephone'] = !empty($_SESSION['mobile']) ? $_SESSION['mobile'] : $members['mobile'];
    $in['fullname'] = trim($_POST['fullname']);
    $in['fullname'] = empty($in['fullname']) ? showmsg('请填写姓名', 1) : $in['fullname'];
    $in['title'] = $in['fullname'] . "创建的简历";
    $sex_arr = explode("-", trim($_POST['sex']));
    $in['sex'] = $sex_arr[0];
    $in['sex_cn'] = $sex_arr[1];
    $in['birthdate'] = intval($_POST['birthdate']);
    $in['email'] = !empty($_SESSION['email']) ? $_SESSION['email'] : $members['email'];
    /*
      $residence_district = intval($_POST['district']);
      $residence_sdistrict = isset($_POST['sdistrict']) ? intval($_POST['sdistrict']) : 0;
      $in['residence'] = $residence_district . "." . $residence_sdistrict;
      $sql = "select * from " . table('category_district') . " where id = " . $residence_district;
      $district_cn = $db->getone($sql);
      $district_cn = $district_cn['categoryname'];
      if ($residence_sdistrict > 0) {
      $sql = "select * from " . table('category_district') . " where id = " . $residence_sdistrict;
      $sdistrict_cn = $db->getone($sql);
      $sdistrict_cn = !empty($sdistrict_cn) ? "/" . $sdistrict_cn['categoryname'] : "";
      $in['residence_cn'] = $district_cn . $sdistrict_cn;
      }
     * 
     */
    $education_arr = explode("-", trim($_POST['education']));
    $in['education'] = $education_arr[0];
    $in['education_cn'] = $education_arr[1];
    $in['trade'] = 33;
    $in['trade_cn'] = "教育/培训";
    $in['entrust'] = 0;
    if (!empty($in['fullname']) && !empty($in['telephone'])) {
        $in['addtime'] = $in['refreshtime'] = time();
        $pid = inserttable(table('resume'), $in, 1);
        $searchtab['id'] = $pid;
        $searchtab['uid'] = $_SESSION['uid'];
        $searchtab['audit'] = 2;
        inserttable(table('resume_search_key'), $searchtab);
        inserttable(table('resume_search_rtime'), $searchtab);
    }
    check_resume($_SESSION['uid'], $pid);
    $infoarr['realname'] = $in['fullname'];
    $infoarr['sex'] = $in['sex'];
    $infoarr['sex_cn'] = $in['sex_cn'];
    $infoarr['birthday'] = $in['birthdate'];
    $infoarr['education'] = $in['education'];
    $infoarr['education_cn'] = $in['education_cn'];
    //$infoarr['residence'] = $in['residence'];
    //$infoarr['residence_cn'] = $in['residence_cn'];
    $infoarr['phone'] = $in['telephone'];
    $infoarr['email'] = $in['email'];
    $infoarr['uid'] = intval($_SESSION['uid']);
    if (get_userprofile($_SESSION['uid'])) {
        $wheresql = " uid='" . intval($_SESSION['uid']) . "'";
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        updatetable(table('members_info'), $infoarr, $wheresql);
    } else {
        $infoarr['uid'] = intval($_SESSION['uid']);
        write_memberslog($_SESSION['uid'], 2, 1005, $_SESSION['username'], "修改了个人资料");
        inserttable(table('members_info'), $infoarr);
    }
    /*
      $sql = "select * from " . table('category_jobs') . " where id = " . intval($_POST['parent_job_type_id']);
      $parent_job_cn = $db->getone($sql);
      $parent_job_cn = $parent_job_cn['categoryname'];
      if (intval($_POST['job_type_id']) != 0) {
      $sql = "select * from " . table('category_jobs') . " where id = " . intval($_POST['job_type_id']);
      $job_cn = $db->getone($sql);
      $job_cn = "-" . $job_cn['categoryname'];
      } else {
      $job_cn = "";
      }
      $in['intention_jobs'] = $parent_job_cn . $job_cn;
      if (!empty($in['fullname']) && !empty($in['telephone'])) {
      $in['addtime'] = $in['refreshtime'] = time();
      $db->insert('resume', $in);
      }
      $sql = "select * from " . table('resume') . " where uid = " . $_SESSION['uid'];
      $resume = $db->getone($sql);
      $setsqlarr['uid'] = $_SESSION['uid'];
      $setsqlarr['pid'] = intval($resume['id']);
      $setsqlarr['topclass'] = 0;
      $setsqlarr['category'] = intval($_POST['parent_job_type_id']);
      $setsqlarr['subclass'] = intval($_POST['job_type_id']);
      $setsqlarr['district'] = intval($_POST['province_id']);
      $setsqlarr['sdistrict'] = intval($_POST['city_id']);
      $db->insert('resume_jobs', $setsqlarr);
      $data['coupon'] = file_get_contents("http://m.51jiangtai.com/batch/add_special_offer_coupon_json?token=sfdsdfiwuerweri12312kjhsekrj&phone=" . $in['telephone']);
      if ($data['coupon'] > 0) {
      $in_coupon['uid'] = $_SESSION['uid'];
      $in_coupon['telephone'] = $telephone;
      $in_coupon['coupon'] = $data['coupon'];
      $in_coupon['addtime'] = time();
      $db->insert('coupon_tmp', $in_coupon);
      }
     * 
     */
    header("Location: /user/user_make_info2.php");
    //}
}
/*
  elseif ($act == "show_coupon") {
  $sql = "select * from " . table('coupon_tmp') . " where uid = " . $_SESSION['uid'];
  $data = $db->getone($sql);
  if ($data['id'] > 0) {
  $smarty->assign('data', $data);
  $smarty->display('user/show_coupon.htm');
  } else {
  header("Location: /user/user_make_info.php");
  }
  }
 * 
 */
?>