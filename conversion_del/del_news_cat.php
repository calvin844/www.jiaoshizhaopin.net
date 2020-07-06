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
if($start_id<14){
    exit;
}

$sql = "select * from ".  table("article_category")." where id =".$start_id;
$cat_rs = $db->getone($sql);
//var_dump($cat_rs);exit;
if(!empty($cat_rs['id'])){
    $sql = "select id from ".table("article")." WHERE type_id = ".$cat_rs['id'];
    $news_rs = $db->getall($sql);
    foreach ($news_rs as $n) {
        echo $n['id']."，开始删除<br>";
        echo "关联栏目ID：".$cat_rs['id']."<br>";
        $sql="select Typeid from pH_New_Info where NewId = '".$n['id']."'";
        $tid = ms_getone($sql);
        //echo $tid['Typeid'];exit;
        access_url("http://pt.jiaoshizhaopin.net/mrd7wy1p/del_news.shtml?action=del&newid=".$n['id']."&cityid=1037&pageno=1&typeid=".$tid['Typeid']."&filename=");
        echo $n['id']."，删除完成<br>";
    }
    $db->query("Delete from ".table('article_category')." WHERE id='{$cat_rs['id']}' LIMIT 1");
}else{
    exit("参数错误！");
}
exit("所有新闻已经全部处理完成！");


 

?>