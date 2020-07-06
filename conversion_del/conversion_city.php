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
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
require_once('conversion_cngfig.php'); 

$sql="select * from PH_City";
$rs = ms_getall($sql);;   //执行搜索语句

foreach($rs as $rs){
    $setsqlarr['parentid']=$rs['provinceid'];
    $setsqlarr['categoryname']=escape_str($rs['city']);
    $c_id = inserttable(table('category_district'),$setsqlarr,1);
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
}
$url_in_arr = array("url" => "conversion_user_company.php","addtime"=> strtotime("now"));
$cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
if(!empty($cons_id)){
    echo '所有城市已经全部转换完成！';
    echo '<script type="text/javascript" language="javascript">window.location.href="conversion_user_company.php";</script> ';
}
 exit("所有城市已经全部转换完成！");


 

?>