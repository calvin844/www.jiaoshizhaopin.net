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
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$locoyspider=get_cache('locoyspider');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php'); 
$size=1000;
$id=intval($_GET['id']);


$sql = "select id from ".table('company_profile')." where district=0";
$ids=$db->getall($sql);
foreach($ids as $i){
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_company_save.php?id=".$i['id']);
}

echo "���";
?>