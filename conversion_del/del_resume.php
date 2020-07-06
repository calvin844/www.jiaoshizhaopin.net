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
$start_id = $_GET['id'];
$aid = $_GET['aid'];
if($start_id<550){
    //exit;
}
    $sql = "select * from ".  table("category_district")." where id =".$start_id." and parentid = 0";
    $city_rs = $db->getall($sql);
    //var_dump($city_rs);exit;
    if(empty($aid)){
        $sql = "select * from ".table("resume")." WHERE `district_cn` LIKE '%".$city_rs['categoryname']."%'";
        $re_rs = $db->getall($sql);
    }else{
        $sql = "select * from ".table("resume")." WHERE id = ".$aid;
        $re_rs = $db->getall($sql);
    }
    //var_dump($re_rs);exit;
    foreach($re_rs as $r){
        echo $r['uid']."，开始删除<br>";
        echo "关联地区ID：".$city_rs['id']."<br>";
        //echo "select * from pH_Person_Info where NCID = '".$r['id']."'";exit;
        $sql="select * from pH_Person_Info where NCID = '".$r['id']."'";
        $pid = ms_getone($sql);
        //var_dump($pid);exit;
        //var_dump($pid['perId']);exit;
        //echo "http://pt.jiaoshizhaopin.net/mrd7wy1p/del_resume.shtml?param=delperson&Perid=".$pid['PerId'];exit;
        access_url("http://pt.jiaoshizhaopin.net/mrd7wy1p/del_resume.shtml?param=delperson&Perid=".$pid['PerId']);
        echo $r['uid']."，删除完成<br>";
    }
    //$db->query("Delete from ".table('category_district')." WHERE id='{$c['id']}' LIMIT 1");
    //$db->query("Delete from ".table('category_district_pinyin')." WHERE d_id='{$c['id']}' LIMIT 1");


 exit("所有简历已经全部处理完成！");


 

?>