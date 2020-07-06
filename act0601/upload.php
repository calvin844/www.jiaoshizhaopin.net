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
//require_once(dirname(__FILE__) . '/personal_common.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$act = empty($_GET['act']) ? "upload" : $_GET['act'];
if (browser() == "mobile") {
    echo '<script language="javascript" type="text/javascript">window.location.href="http://m.jiaoshizhaopin.net/act0601/upload";</script>';
    exit;
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

$sql = "select * from " . table('act_20170601') . " where uid = " . $_SESSION['uid'];
$item = $db->getone($sql);
if (!empty($item)) {
    $link[0]['text'] = "查看作品";
    $link[0]['href'] = "/act0601/details.php?id=" . $item['id'];
    showmsg('您已上传过作品！', 1, $link);
}
if ($act == "upload") {
    $smarty->display('act0601/upload.htm');
} else if ($act == "save") {
    $uid = intval($_SESSION['uid']);
    $_POST['content'] = $_OLD_POST['content'];
    $data['uid'] = $uid > 0 ? $uid : showmsg('请先登录！', 1);
    $data['student_name'] = !empty($_POST['student_name']) ? trim($_POST['student_name']) : showmsg('请填写学生姓名！', 1);
    $data['student_age'] = intval($_POST['student_age']) > 0 ? intval($_POST['student_age']) : showmsg('请填写学生年龄！', 1);
    $data['item_type'] = intval($_POST['item_type']) > 0 ? intval($_POST['item_type']) : showmsg('请选择作品类型！', 1);
    $data['title'] = !empty($_POST['title']) ? trim($_POST['title']) : showmsg('请填写作品标题！', 1);
    $data['content'] = !empty($_POST['content']) ? $_POST['content'] : showmsg('请填写作品内容！', 1);
    $data['thumb'] = !empty($_POST['thumb']) ? trim($_POST['thumb']) : showmsg('请上传缩略图！', 1);
    $in_resume['fullname'] = $data['teacher_name'] = !empty($_POST['teacher_name']) ? trim($_POST['teacher_name']) : showmsg('请填写教师姓名！', 1);
    $in_resume['photo_img'] = $data['teacher_photo'] = !empty($_POST['teacher_photo']) ? trim($_POST['teacher_photo']) : showmsg('请上传教师照片！', 1);
    $data['audit'] = 1;
    $data['addtime'] = time();
    $db->insert("act_20170601", $data);
    $in_resume['photo'] = 1;
    updatetable(table('resume'), $in_resume, " uid='" . $uid . "'");
    $sql = "select * from " . table('resume') . " WHERE uid = " . $uid . " LIMIT 1";
    $resume = $db->getone($sql);
    check_resume($_SESSION['uid'], $resume['id']);
    header("Location: /act0601/user.php");
} else if ($act == "uploadphoto") {
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
        $setsqlarr['photo_audit'] = $_CFG['audit_resume_photo'];
    } else {
        $_CFG['audit_edit_photo'] != "-1" ? $setsqlarr['photo_audit'] = intval($_CFG['audit_edit_photo']) : "";
    }
    $photo_dir = substr($_CFG['resume_photo_dir'], strlen($_CFG['website_dir']));
    $photo_dir = "../" . $photo_dir . date("Y/m/d/");
    $up_res_rlogo = "../data/photo/rlogo/";
    make_dir($photo_dir);
    make_dir($up_res_rlogo . date("Y/m/d/"));
    $setsqlarr['photo_img'] = _asUpFiles($photo_dir, "photo", $_CFG['resume_photo_max'], 'gif/jpg/bmp/png', true);
    makethumb($photo_dir . $setsqlarr['photo_img'], $up_res_rlogo . date("Y/m/d/"), 280, 280);
    $setsqlarr['photo_img'] = date("Y/m/d/") . $setsqlarr['photo_img'];
    !updatetable(table('resume'), $setsqlarr, " uid='" . intval($_SESSION['uid']) . "'") ? exit("保存失败！") : '';
    // check_resume($_SESSION['uid'],intval($_REQUEST['pid']));
    exit($setsqlarr['photo_img']);
} else if ($act == "uploadthumb") {
    !$_FILES['thumb_file']['name'] ? exit('请上传图片！') : "";
    require_once(QISHI_ROOT_PATH . 'include/cut_upload.php');
    $photo_dir = "../data/act0601/thumb/" . date("Y/m/d/");
    make_dir($photo_dir);
    $save_path = _asUpFiles($photo_dir, "thumb_file" . $num, 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
    if (!empty($save_path)) {
        $path = "/data/act0601/thumb/" . date("Y/m/d/") . $save_path;
        exit($path);
    } else {
        exit("-8");
    }
}
?>