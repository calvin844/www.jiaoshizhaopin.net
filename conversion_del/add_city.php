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
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
$t1 = exectime();
require_once('conversion_cngfig.php');
$locoyspider=get_cache('locoyspider');
$id=intval($_GET['id']);
$cname=escape_str($_GET['cname']);
$aname=escape_str($_GET['aname']);

//添加补充职位地区信息记录_Calvin_20140618

//echo $cname."<br>";
//echo $aname."<br>";exit;
    $cname = trim($cname);
    $aname = trim($aname);
    $tmp_str = substr($cname,-2);
    if($tmp_str == "市" || $tmp_str == "省"){
        $cname = substr($cname,0,strlen($cname)-2);
    }
    $tmp_str = substr($aname,-2);
    if($tmp_str == "市" || $tmp_str == "省"){
        $aname = substr($aname,0,strlen($aname)-2);
    }
    if(!empty($cname)){
        $sql = "select id,parentid,categoryname from ".table('category_district');
        $info=$db->getall($sql);
        $return=locoyspider_search_str($info,$cname,"categoryname",60);
        $return = FALSE;
        if (!$return){
            //如果地区表里没有相应数据，对地区表数据进行补充
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
    echo "<br>完成";

 
?>