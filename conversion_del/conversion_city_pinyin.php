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
require_once('pinyinInit.class.php');
$py = new pinyinInit();
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
require_once('conversion_cngfig.php'); 

$where = !empty($_GET['id'])?" where id = ".intval($_GET['id']):'';

$sql="select id,categoryname from ".table('category_district').$where;
$city_res = $db->getall($sql);
foreach ($city_res as $cr) {
    //$c_py = $py->pinyin($cr['categoryname'],FALSE);
    $c_py = $py->getAllPY($cr['categoryname'], $delimiter = '', $length = 0,$charset='gb2312');
    if($cr['categoryname'] == 'ɽ��'){
        $c_py = $c_py."1";
    }elseif($cr['categoryname'] == '����'){
        $c_py = "chongqing";
    }
    $sql="select * from ".table('category_district')." where pinyin = '".$c_py."' limit 1";
    $py_res = $db->getone($sql);
    if(!empty($py_res['id'])){
        $c_py = $c_py."1";
    }
    $wheresqlarr = " id = ".$cr['id'];
    updatetable(table('category_district'),array('pinyin'=>$c_py), $wheresqlarr);
}
 exit("���г���ƴ���Ѿ�ȫ��ת����ɣ�");


 

?>