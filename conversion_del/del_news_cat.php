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
        echo $n['id']."����ʼɾ��<br>";
        echo "������ĿID��".$cat_rs['id']."<br>";
        $sql="select Typeid from pH_New_Info where NewId = '".$n['id']."'";
        $tid = ms_getone($sql);
        //echo $tid['Typeid'];exit;
        access_url("http://pt.jiaoshizhaopin.net/mrd7wy1p/del_news.shtml?action=del&newid=".$n['id']."&cityid=1037&pageno=1&typeid=".$tid['Typeid']."&filename=");
        echo $n['id']."��ɾ�����<br>";
    }
    $db->query("Delete from ".table('article_category')." WHERE id='{$cat_rs['id']}' LIMIT 1");
}else{
    exit("��������");
}
exit("���������Ѿ�ȫ��������ɣ�");


 

?>