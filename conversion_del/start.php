<?php
 /*
 * 74cms ��ҵ�û�ת��
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
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php'); 

$sql = "select url from ".table('cons')." order by addtime desc limit 1";
$url_str = $db->getone($sql);
if(empty($url_str['url']) || $url_str['url'] == "conversion_province.php"){
    $db->query("Delete from ".table('category_district'));
    $db->query("Delete from ".table('category_district_pinyin'));
    //exit;
    $url_in_arr = array("url" => "conversion_province.php","addtime"=> strtotime("now"));
    $cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
    if(!empty($cons_id)){
        echo '<script type="text/javascript" language="javascript">window.location.href="conversion_province.php";</script> ';
    }
    exit;
}else{
    echo '<script type="text/javascript" language="javascript">window.location.href="'.$url_str['url'].'";</script> ';
}



?>