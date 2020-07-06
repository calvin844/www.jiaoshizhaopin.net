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
require_once(dirname(__FILE__) . '/../include/mysql.class.php');
require_once(dirname(__FILE__) . '/../include/fun_personal.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$mem = new Memcache;
$mem->connect("localhost", 11111);
if (empty($_GET['act'])) {
    if (browser() == "mobile") {
        echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/make_info";</script>';
        exit;
    }
    $act = "make_info";
} else {
    $act = explode("-", $_GET['act']);
    intval($act[1]) > 0 ? $_GET['team_id'] = intval($act[1]) : "";
    $act = $act[0];
    if (browser() == "mobile") {
        echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/make_info?team_id=' . $_GET['team_id'] . '";</script>';
        exit;
    }
}
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '' || intval($_SESSION['uid']) === 0) {
    $query_string = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : "";
    header("Location: /user/login.php?act_url=" . $_SERVER['PHP_SELF'] . $query_string);
    exit();
} elseif ($_SESSION['utype'] != '2') {
    $query_string = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : "";
    $link[0]['text'] = "马上登录";
    $link[0]['href'] = "/user/login.php?act_url=" . $_SERVER['PHP_SELF'] . $query_string;
    showmsg('您访问的页面需要 个人会员 登录！', 1, $link);
}
if ($act == "make_info") {
    $uid = intval($_SESSION['uid']);
    $leader_resume = array();
    $team_id = !empty($_GET['team_id']) ? intval($_GET['team_id']) : 0;
    $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid;
    $uteam = $db->getone($sql);
    if ($team_id > 0) {
        $sql = "select * from " . table('act_20170601_team') . " where id = " . $team_id;
        $team = $db->getone($sql);
        $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid . " and leader = " . $team['leader'];
        $team = $db->getone($sql);
        if (!empty($team)) {
            $sql = "select * from " . table('act_20170601') . " where uid = " . $team['leader'];
            $item = $db->getone($sql);
            $link[0]['text'] = "查看作品";
            $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
            showmsg('您已找到组织！', 1, $link);
        }
        $sql = "select * from " . table('act_20170601_team') . " where id = " . $team_id;
        $team = $db->getone($sql);
        $sql = "select * from " . table('resume') . " WHERE uid = " . $team['leader'] . " LIMIT 1";
        $leader_resume = $db->getone($sql);
        $sql = "select * from " . table('act_20170601') . " where uid = " . $team['leader'];
        $item = $db->getone($sql);
        $sql = "select count(*) as t from " . table('act_20170601_team') . " where leader = " . $team['leader'];
        $team_total = $db->getone($sql);
        if ($team_total['t'] > 9) {
            $link[0]['text'] = "查看作品";
            $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
            showmsg('组织已满人！', 1, $link);
        }
        if (!empty($uteam)) {
            if ($team['leader'] != $uid) {
                $in_team['leader'] = $team['leader'];
                $in_team['uid'] = $uid;
                $in_team['addtime'] = time();
                $db->insert('act_20170601_team', $in_team);
                $link[0]['text'] = "查看作品";
                $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
                showmsg('加入成功！', 1, $link);
            }
        }
    }
    $resume = array();
    $sdistrict = array();
    $jobs = array();
    $sql = "select * from " . table('resume') . " WHERE uid = " . $uid . " LIMIT 1";
    $resume = $db->getone($sql);
    $sql = "select * from " . table('category_district') . " where parentid = 0";
    $district = $db->getall($sql);
    if ($resume['district'] > 0) {
        $sql = "select * from " . table('category_district') . " where parentid = " . $resume['district'];
        $sdistrict = $db->getall($sql);
    }
    $sql = "select * from " . table('category_jobs') . " where parentid = 0 order by category_order desc";
    $parent_jobs = $db->getall($sql);
    $sql = "select * from " . table('resume_jobs') . " where uid = " . $uid . " order by id desc limit 1";
    $resume_jobs = $db->getone($sql);
    if (!empty($resume_jobs)) {
        $sql = "select * from " . table('category_jobs') . " where parentid = " . $resume_jobs['category'];
        $jobs = $db->getall($sql);
    }

    $sql = "select * from " . table('resume_certificate') . " where uid = " . $uid . " and note = '教师资格证书'";
    $resume_certificate = $db->getone($sql);
    $sql = "select * from " . table('act_20170601') . " where uid = " . $uid;
    $item = $db->getone($sql);
    $smarty->assign('uteam', $uteam);
    $smarty->assign('resume_certificate', $resume_certificate);
    $smarty->assign('leader_resume', $leader_resume);
    $smarty->assign('district', $district);
    $smarty->assign('parent_jobs', $parent_jobs);
    $smarty->assign('resume_jobs', $resume_jobs);
    $smarty->assign('jobs', $jobs);
    $smarty->assign('sdistrict', $sdistrict);
    $smarty->assign('resume', $resume);
    $smarty->assign('item', $item);
    $smarty->display('act0601/make_info.htm');
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
} elseif ($act == "get_jobs") {
    $parent_id = intval($_GET['pid']);
    $sql = "select * from " . table('category_jobs') . " where parentid = " . $parent_id;
    $jobs = $db->getall($sql);
    $jobs_str = "";
    foreach ($jobs as $c) {
        $jobs_str.=$c['id'] . "-" . $c['categoryname'] . "||";
    }
    $jobs_str = trim($jobs_str, "||");
    echo $jobs_str;
} elseif ($act == 'up_certificate') {
    !$_FILES['photo']['name'] ? exit('请上传图片！') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $resume_basic = get_resume_basic(intval($_SESSION['uid']));
    //var_dump($resume_basic);
    if (empty($resume_basic['id'])) {
        $in['uid'] = $_SESSION['uid'];
        $in['addtime'] = $in['refreshtime'] = time();
        $in['audit'] = 2;
        $in['trade'] = 33;
        $in['trade_cn'] = "教育/培训";
        $in['entrust'] = 0;
        $db->insert('resume', $in);
    }
    $photo_dir = substr($_CFG['resume_photo_dir'], strlen($_CFG['website_dir']));
    $photo_dir = "../data/resume_certificate/" . date("Y/m/d/");
    make_dir($photo_dir);
    $setsqlarr['photo_img'] = _asUpFiles($photo_dir, "photo", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
    $setsqlarr['photo_img'] = date("Y/m/d/") . $setsqlarr['photo_img'];
    exit($setsqlarr['photo_img']);
} elseif ($act == "check_telephone") {
    $telephone = intval($_GET['phone']);
    $sql = "select * from " . table('resume') . " where telephone = " . $telephone;
    $t = $db->getone($sql);
    if (empty($t['id']) || $t['uid'] == $_SESSION['uid'] || $telephone == 15915791159) {
        echo 1;
    } else {
        echo 0;
    }
} elseif ($act == "send_code") {
    $postcaptcha = $_GET['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("0-请填写验证码");
    }
    $mobile = intval($_GET['phone']);
    //$sql = "select * from " . table('resume') . " where telephone = " . $mobile;
    //$t = $db->getone($sql);
    //$mobile = (empty($t['id']) || $t['uid'] == $_SESSION['uid']) ? $mobile : 0;
    if ($mobile > 0) {
        $result = $mem->get($mobile);
        $result = 0;
        if (!$result) {
            $result = rand(1000, 9999);
            $mem->set($mobile, $result, 0, 60);
        }
        $text = iconv("GB2312", "UTF-8//IGNORE", "【教师网】您的验证码是" . $result);
        //$text = "【教师网】您的验证码是" . $result;
        $ch = curl_init();
        $apikey = "732be982abc9313b64561224601f70a3";
        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $json_data = curl_exec($ch);

        $sql = "select * from " . table('members') . " where mobile = " . $mobile;
        $sms_to_user = $db->getone($sql);
        $in['phone'] = $mobile;
        $in['utype'] = !empty($sms_to_user) ? $sms_to_user['utype'] : 0;
        $in['sms_type'] = 1;
        $in['back'] = 0;
        $in['addtime'] = time();
        $db->insert('yunpian_sms_log', $in);
    }
} elseif ($act == "save") {
    $telephone = empty($_POST['telephone']) ? showmsg('请填写手机号！', 1) : $_POST['telephone'];
    $uid = intval($_SESSION['uid']);
    if ($mem->get($telephone) == intval($_POST['code'])) {
        $in['uid'] = $uid;
        $in['telephone'] = $data['telephone'] = $telephone;
        $in['fullname'] = trim($_POST['fullname']);
        $in['fullname'] = empty($in['fullname']) ? showmsg('请填写姓名！', 1) : $in['fullname'];
        $in['title'] = $in['fullname'] . "创建的简历";
        $in['district'] = intval($_POST['district']);
        $in['sdistrict'] = isset($_POST['sdistrict']) ? intval($_POST['sdistrict']) : 0;
        $in['trade'] = 33;
        $in['trade_cn'] = "教育/培训";
        $in['entrust'] = 0;
        $in['audit'] = 2;
        $experience_arr = explode("#", $_POST['experience_str']);
        $in['experience'] = $experience_arr[0];
        $in['experience_cn'] = $experience_arr[1];
        $education_arr = explode("-", $_POST['education_str']);
        $in['education'] = $education_arr[0];
        $in['education_cn'] = $education_arr[1];
        $in['specialty'] = trim($_POST['specialty']);
        $sql = "select * from " . table('category_district') . " where id = " . $in['district'];
        $district_cn = $db->getone($sql);
        $district_cn = $district_cn['categoryname'];
        if ($in['sdistrict'] != 0) {
            $sql = "select * from " . table('category_district') . " where id = " . $in['sdistrict'];
            $sdistrict_cn = $db->getone($sql);
            $sdistrict_cn = !empty($sdistrict_cn) ? "/" . $sdistrict_cn['categoryname'] : "";
            $in['district_cn'] = $district_cn . $sdistrict_cn;
        }
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
            $resume_basic = get_resume_basic($uid);
            if (empty($resume_basic)) {
                $in['addtime'] = $in['refreshtime'] = time();
                $db->insert('resume', $in);
            } else {
                $db->update(table('resume'), $in, ' uid = ' . $in['uid']);
            }
        }
        $sql = "select * from " . table('resume') . " where uid = " . $uid;
        $resume = $db->getone($sql);
        if (!empty($_POST['certificate'])) {
            $certificate['path'] = trim($_POST['certificate']);
            $certificate['uid'] = $uid;
            $certificate['pid'] = $resume['id'];
            $certificate['note'] = "教师资格证书";
            $certificate['addtime'] = time();
            $db->insert("resume_certificate", $certificate);
        }
        $sql = "select * from " . table('resume_jobs') . " where uid = " . $uid . " and category = " . intval($_POST['parent_job_type_id']) . " and subclass = " . intval($_POST['job_type_id']) . " and district = " . intval($_POST['district']) . " and sdistrict = " . intval($_POST['sdistrict']);
        $resume_jobs = $db->getone($sql);
        if (empty($resume_jobs)) {
            $resume_jobs['uid'] = $_SESSION['uid'];
            $resume_jobs['pid'] = intval($resume['id']);
            $resume_jobs['topclass'] = 0;
            $resume_jobs['category'] = intval($_POST['parent_job_type_id']);
            $resume_jobs['subclass'] = intval($_POST['job_type_id']);
            $resume_jobs['district'] = intval($_POST['district']);
            $resume_jobs['sdistrict'] = intval($_POST['sdistrict']);
            $db->insert('resume_jobs', $resume_jobs);
        }
        $sql = "select * from " . table('resume_certificate') . " where uid = " . $uid . " and note = '教师资格证书' and addtime > 1494691200 and audit = 1";
        $resume_certificate = $db->getone($sql);
        if (!empty($resume_certificate)) {
            $in_team['audit'] = 2;
        }
        $team_id = trim($_POST['team_id']);
        if (empty($team_id)) {
            $sql = "select * from " . table('act_20170601_team') . " where uid = " . $uid;
            $user_team = $db->getone($sql);
            if (empty($user_team)) {
                $in_team['leader'] = $uid;
                $in_team['uid'] = $uid;
                $in_team['addtime'] = time();
                $team['id'] = $db->insert('act_20170601_team', $in_team);
                header("Location: /act0601/share.php?team_id=" . $team['id']);
            } else {
                $sql = "select * from " . table('act_20170601') . " where uid = " . $user_team['leader'];
                $item = $db->getone($sql);
                $link[0]['text'] = "查看作品";
                $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
                showmsg('保存成功！', 1, $link);
            }
        } else {
            $sql = "select * from " . table('act_20170601_team') . " where id = " . $team_id;
            $team = $db->getone($sql);
            if ($team['leader'] != $uid) {
                $in_team['leader'] = $team['leader'];
                $in_team['uid'] = $uid;
                $in_team['addtime'] = time();
                $db->insert('act_20170601_team', $in_team);
                $sql = "select * from " . table('act_20170601') . " where uid = " . $team['leader'];
                $item = $db->getone($sql);
                $link[0]['text'] = "查看作品";
                $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
                showmsg('加入成功！', 1, $link);
            } else {
                $link[0]['text'] = "活动首页";
                $link[0]['href'] = "/act0601/index.php";
                showmsg('该团暂无作品！', 1, $link);
            }
        }
    }
}
?>