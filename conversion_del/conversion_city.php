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

$sql="select * from PH_City";
$rs = ms_getall($sql);;   //ִ���������

foreach($rs as $rs){
    $setsqlarr['parentid']=$rs['provinceid'];
    $setsqlarr['categoryname']=escape_str($rs['city']);
    $c_id = inserttable(table('category_district'),$setsqlarr,1);
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
}
$url_in_arr = array("url" => "conversion_user_company.php","addtime"=> strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
if(!empty($cons_id)){
    echo '���г����Ѿ�ȫ��ת����ɣ�';
    echo '<script type="text/javascript" language="javascript">window.location.href="conversion_user_company.php";</script> ';
}
 exit("���г����Ѿ�ȫ��ת����ɣ�");


 

?>