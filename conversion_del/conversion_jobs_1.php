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
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');
$size=80;
$id=intval($_GET['id']);
if(!empty($_GET['big'])){
    $bignum_arr = $_GET['big'];
    $bignum_arr = explode(",", $bignum_arr);
    if(!empty($bignum_arr[0])){
        forbig($bignum_arr);
        $big = "";
        $m = $_GET['m']-$size;
        $thisid = $id;
         goon($m,$thisid,$big,$size);
        exit;
    }
}else{
    $big = "";
}


$sql = "select * from ".table("jobs_tmp")." where district=0";
$jobs_arr = $db->getall($sql);
foreach ($jobs_arr as $j){
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_jobs_save.php?id=".$j['id']);
}

echo "���";


?>