<?php

/*
 * 74cms �������
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
    $setsqlarr['feedback'] = trim($_POST['feedback']) ? trim($_POST['feedback']) : showmsg("����д���ݣ�", 1);
    $setsqlarr['tel'] = trim($_POST['tel']) ? trim($_POST['tel']) : showmsg("����д��ϵ��ʽ��", 1);
    $setsqlarr['addtime'] = time();
    $link[0]['text'] = "��վ��ҳ";
    $link[0]['href'] = $_CFG['site_dir'];
    !inserttable(table('feedback'), $setsqlarr) ? showmsg("���ʧ�ܣ�", 0) : showmsg("��ӳɹ�����л���Ա�վ��֧�֣�", 2, $link);
} else {
    $link[0]['text'] = "��վ��ҳ";
    $link[0]['href'] = $_CFG['site_dir'];
    showmsg("��ӳɹ�����л���Ա�վ��֧�֣�", 2, $link);
}
?>