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
$t1 = exectime();
require_once('conversion_cngfig.php'); 

$sql = "select url from ".table('cons')." order by addtime desc limit 1";
$url_str = $db->getone($sql);
if(empty($url_str['url']) || $url_str['url'] == "conversion_province.php"){
    $db->query("Delete from ".table('category_district'));
    $db->query("Delete from ".table('category_district_pinyin'));
    //exit;
    $url_in_arr = array("url" => "conversion_province.php","addtime"=> strtotime("now"));
    $cons_id = inserttable(table('cons'), $url_in_arr,TRUE);
    if(!empty($cons_id)){
        echo '<script type="text/javascript" language="javascript">window.location.href="conversion_province.php";</script> ';
    }
    exit;
}else{
    echo '<script type="text/javascript" language="javascript">window.location.href="'.$url_str['url'].'";</script> ';
}



?>