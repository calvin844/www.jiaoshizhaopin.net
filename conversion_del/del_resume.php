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
        echo $r['uid']."����ʼɾ��<br>";
        echo "��������ID��".$city_rs['id']."<br>";
        //echo "select * from pH_Person_Info where NCID = '".$r['id']."'";exit;
        $sql="select * from pH_Person_Info where NCID = '".$r['id']."'";
        $pid = ms_getone($sql);
        //var_dump($pid);exit;
        //var_dump($pid['perId']);exit;
        //echo "http://pt.jiaoshizhaopin.net/mrd7wy1p/del_resume.shtml?param=delperson&Perid=".$pid['PerId'];exit;
        access_url("http://pt.jiaoshizhaopin.net/mrd7wy1p/del_resume.shtml?param=delperson&Perid=".$pid['PerId']);
        echo $r['uid']."��ɾ�����<br>";
    }
    //$db->query("Delete from ".table('category_district')." WHERE id='{$c['id']}' LIMIT 1");
    //$db->query("Delete from ".table('category_district_pinyin')." WHERE d_id='{$c['id']}' LIMIT 1");


 exit("���м����Ѿ�ȫ��������ɣ�");


 

?>