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

echo "完成";
?>