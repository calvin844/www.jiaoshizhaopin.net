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
require_once('conversion_cngfig.php'); 

$sql="select * from PH_province";
$rs = ms_getall($sql);   //ִ���������

foreach($rs as $rs){
$setsqlarr['id']=$rs['id'];
$setsqlarr['categoryname']=escape_str($rs['province']);
$c_id = inserttable(table('category_district'),$setsqlarr,1);
access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
}
$url_in_arr = array("url" => "conversion_city.php","addtime"=> strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
if(!empty($cons_id)){
    echo '����ʡ�Ѿ�ȫ��ת����ɣ�';
    echo '<script type="text/javascript" language="javascript">window.location.href="conversion_city.php";</script> ';
}
 exit("����ʡ�Ѿ�ȫ��ת����ɣ�");
 


?>