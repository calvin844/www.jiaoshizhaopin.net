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
$size=1000;
$id=intval($_GET['id']);


//$sql = "select id,seo_description from ".table('article');
$sql = "select id from ".table('article');
$temp_arr=$db->getall($sql);
foreach($temp_arr as $t){
    //$arr = explode(" ",$t['seo_description']);
    //$city_str = explode("-",$arr[1]);
    //if(empty($city_str[1])){
        access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_news_save.php?id=".$t['id']);
    //}
}
echo "���";

?>