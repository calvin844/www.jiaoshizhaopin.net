<?php

/*
 * 74cms ��վ��ҳ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
set_time_limit(0);
define('IN_QISHI', true);
require_once('../include/common.inc.tmp.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
//require_once('conversion_cngfig.php');
//$id=intval($_GET['id']);

$sql = "select id from " . table("article") . " where is_url = '" . $_POST['is_url'] . "' or title LIKE '%" . $_POST['title'] . "%'";
$result = $db->query($sql);
if (empty($result['id'])) {
    $f_id = FALSE;
    $data['content'] = trim($_POST['content']);
    if (!empty($data['content'])) {
        $data['is_url'] = $_POST['is_url'];
        $data['title'] = trim($_POST['title']);
        $data['audit'] = 2;
        $data['endtime'] = $_POST['time'];
        $f_id = inserttable(table('article'), $data, 1);
    }
    if ($f_id) {
        return TRUE;
    } else {
        return FALSE;
    }
}
return FALSE;
?>