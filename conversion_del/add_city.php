<?php
 /*
 * 74cms ��վ��ҳ
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
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');
$id=intval($_GET['id']);
$cname=escape_str($_GET['cname']);
$aname=escape_str($_GET['aname']);

//��Ӳ���ְλ������Ϣ��¼_Calvin_20140618

//echo $cname."<br>";
//echo $aname."<br>";exit;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname,-2);
    if($tmp_str == "��" || $tmp_str == "ʡ"){
        $cname = substr($cname,0,strlen($cname)-2);
    }
    $tmp_str = substr($aname,-2);
    if($tmp_str == "��" || $tmp_str == "ʡ"){
        $aname = substr($aname,0,strlen($aname)-2);
    }
    if(!empty($cname)){
        $sql = "select id,parentid,categoryname from ".table('category_district');
        $info=$db->getall($sql);
        $return=locoyspider_search_str($info,$cname,"categoryname",60);
        $return = FALSE;
        if (!$return){
            //�����������û����Ӧ���ݣ��Ե��������ݽ��в���
            $sql = "select id,categoryname from ".table('category_district')." where parentid = 0";
            $info_a=$db->getall($sql);
            $return_a=locoyspider_search_str($info_a,$aname,"categoryname",60);
            
            if(!$return_a){
                $add_area = array("parentid" => 0,"categoryname" => $aname);
                $return_a['id'] = inserttable(table('category_district'),$add_area,true);
                access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$return_a['id']);
                if($cname == $aname){
                    $return['id'] =$return_a['id'];
                    $return['parentid'] =0;
                    $return['categoryname'] =$aname;
                }else{
                    $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
                    $c_id=inserttable(table('category_district'),$add_city,true);
                    access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
                    $return['id'] = $c_id;
                    $return['parentid'] = $return_a['id'];
                    $return['categoryname'] = $cname;
                }
            }else{
                $add_city = array("parentid" => $return_a['id'],"categoryname" => $cname);
                $c_id=inserttable(table('category_district'),$add_city,true);
                access_url("http://".$_SERVER['HTTP_HOST']."/conversion/conversion_city_pinyin.php?id=".$c_id);
                $return['parentid'] = $return_a['id'];
                $return['id'] = $c_id;
                $return['categoryname'] = $cname;
            }
        }
    }
    var_dump(array("district"=>$return['parentid'],"sdistrict"=>$return['id'],"district_cn"=>$return['categoryname']));
    
    echo "<br>";
    echo "<br>���";

 
?>