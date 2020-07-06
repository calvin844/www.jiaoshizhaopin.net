<?php

/*
 * 74cms 意见反馈
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
$feedback = trim($_POST['feedback']);
$tel = trim($_POST['tel']);
if ($tel != '555-666-0606' || strlen($feedback) > 20) {
    $postcaptcha = $_POST['postcaptcha'];
    if ($captcha['captcha_lang'] == "cn" && strcasecmp(QISHI_DBCHARSET, "utf8") != 0) {
        $postcaptcha = utf8_to_gbk($postcaptcha);
    }
    if (empty($postcaptcha) || empty($_SESSION['imageCaptcha_content']) || strcasecmp($_SESSION['imageCaptcha_content'], $postcaptcha) != 0) {
        exit("err");
    }
    $setsqlarr['infotype'] = intval($_POST['infotype']);
    $setsqlarr['feedback'] = trim($_POST['feedback']) ? trim($_POST['feedback']) : showmsg("请填写内容！", 1);
    $setsqlarr['tel'] = trim($_POST['tel']) ? trim($_POST['tel']) : showmsg("请填写联系方式！", 1);
    $setsqlarr['addtime'] = time();
    $link[0]['text'] = "网站首页";
    $link[0]['href'] = $_CFG['site_dir'];
    !inserttable(table('feedback'), $setsqlarr) ? showmsg("添加失败！", 0) : showmsg("添加成功，感谢您对本站的支持！", 2, $link);
} else {
    $link[0]['text'] = "网站首页";
    $link[0]['href'] = $_CFG['site_dir'];
    showmsg("添加成功，感谢您对本站的支持！", 2, $link);
}
?>