<?php
 /*
 * 74cms 网站首页
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
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");
require_once('../include/common.inc.php'); 
require_once(QISHI_ROOT_PATH.'include/fun_user.php');
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
require_once(QISHI_ROOT_PATH.'include/fun_personal.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');

$page=intval($_GET['p']);
$size=800;
$id=intval($_GET['id']);

$sql = "SELECT id FROM ".table('resume_tmp')." WHERE `district_cn` LIKE '%?%' or `fullname` LIKE '%?%' or `householdaddress` LIKE '%?%' or `address` LIKE '%?%' or `key` LIKE '%?%'";
$temp_arr=$db->getall($sql);
//print_r($temp_arr);
foreach($temp_arr as $t){
    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_resume_save.php?id=".$t['id']);
}
echo "完成";

?>