<?php
 /*
 * 74cms 企业用户转换
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
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
    if($cr['categoryname'] == '山西'){
        $c_py = $c_py."1";
    }elseif($cr['categoryname'] == '重庆'){
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
 exit("所有城市拼音已经全部转换完成！");


 

?>