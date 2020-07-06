<?php
 /*
 * 74cms ajax
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)).'/include/plus.common.inc.php');
$table = !empty($_POST['table']) ? trim($_POST['table']) : 'article';
$where = !empty($_POST['where']) ? trim($_POST['where']) : '';
$num = !empty($_POST['num']) ? trim($_POST['num']) : 1;
$url =  !empty($_POST['url']) ? trim($_POST['url']) : '';
$page =  !empty($_POST['page']) ? trim($_POST['page']) : 1;
$page_str = " ";
$page_select = " ";
if(!empty($_POST['kw'])){
    $kw = " content LIKE '%".escape_str($_POST['kw'])."%'";
    if(!empty($where)){
        $where .= " AND ".$kw;
    }else{
        $where = " where ".$kw;
    }
}
if(!empty($_POST['cw'])){
    $cw = intval($_POST['cw']);
    $sql = "select categoryname from ".table('category_district')." where id = '".$cw."' LIMIT 1";
    $city=$db->getone($sql);
    $cw = " seo_description LIKE '%".$city['categoryname']."%'";
    if(!empty($where)){
        $where .= " AND ".$cw;
    }else{
        $where = " where ".$cw;
    }
}

$sql = "select count(1) as num from ".table($table)." ".$where;
//echo $sql;exit;
$news_total=$db->getone($sql);
$news_total = floor($news_total['num']/$num);
//echo $news_total;exit;

if($news_total>$num){
    $perpage=($page-1)>0?$page-1:1;
    $nextpage=($page+1)<$news_total?($page+1):$news_total;
    if($page>1){
        $page_str = '<a href="'.$url.'/p/'.$perpage.'"><< </a>';
        $page_str .= '<a href="'.$url.'/p/1">1...</a>';
    }
    for($i = $page; $i<=$page+9;$i++){
        if($page != $news_total){
            if($i > $news_total-1){
                continue;
            }
        }else{
            if($i > $news_total){
                continue;
            }
        }
        $cur = $i == $page?'  class="cur"  ':'';
        $page_str .= '<a '.$cur.' href="'.$url.'/p/'.$i.'">'.$i.'</a>';
    }
    if($page<$news_total){
        $page_str .= '<a href="'.$url.'/p/'.$news_total.'">...'.$news_total.'</a>';
        $page_str .= '<a href="'.$url.'/p/'.$nextpage.'"> >></a>';
    }
    $page_select = '<select size="1" name="PageNo" >';
    for($i = 1; $i<=$news_total;$i++){
        if($i == $page){
            $sel = "selected";
        }else{
            $sel = "";
        }
        $page_select .='<option '.$sel.' value="'.$url.'/p/'.$i.'">'.$i.'</option>';
    }
    $page_select .= '</select>';
    $page_select .= '</select>';
    
}else{
    $page_str = "";
    $page_select = "";
}

$data = array('str'=>$page_str,'select'=>$page_select);
//echo $page_str;exit;
echo json_encode($data);

function escape_str($str)
{
	global $frdbcharset;
	$str=iconv("utf-8",'gbk//IGNORE',$str);
	$str=mysql_escape_string($str);
	$str=str_replace('\\\'','\'\'',$str);
	$str=str_replace("\\\\","\\\\\\\\",$str);
	$str=str_replace('$','\$',$str);
	return $str;
}
?>