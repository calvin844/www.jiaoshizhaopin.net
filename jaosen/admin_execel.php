<?php

/*
 * 74cms 分类
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
$list = array();
$sql = "select id,fullname,sex_cn,birthdate,experience_cn,district_cn,wage_cn,education_cn,addtime from " . table('resume') . " where  addtime>1496246400 and addtime<1527782400";
$result = $db->getall($sql);
$header = array('ID', '姓名', '性别', '年龄', '工作经验', '期望地区', '期望月薪', '学历', '添加时间');
$index = array('id', 'fullname', 'sex_cn', 'birthdate', 'experience_cn', 'district_cn', 'wage_cn', 'education_cn', 'addtime');
//$result_index = array('ID', '姓名', '性别', '年龄', '工作经验', '期望地区', '期望月薪', '学历', '添加时间');
foreach ($result as $r) {
    $r['sex_cn'] = $r['sex_cn'] != "" ? $r['sex_cn'] : "未填写";
    $r['birthdate'] = $r['birthdate'] > 0 ? date("Y") - $r['birthdate'] : "0";
    $r['experience_cn'] = $r['experience_cn'] != "" ? $r['experience_cn'] : "未填写";
    $r['district_cn'] = $r['district_cn'] != "" ? $r['district_cn'] : "未填写";
    $r['wage_cn'] = $r['wage_cn'] != "" ? $r['wage_cn'] : "未填写";
    $r['education_cn'] = $r['education_cn'] != "" ? $r['education_cn'] : "未填写";
    $r['addtime'] = date("Y-m-d", $r['addtime']);
    $list[] = $r;
}
createtable($list, '20180605', $header, $index);
//exportExcel($list, "20180605", $result_index);

/**
 * 创建(导出)Excel数据表格 
 * @param  array   $list 要导出的数组格式的数据 
 * @param  string  $filename 导出的Excel表格数据表的文件名 
 * @param  array   $header Excel表格的表头 
 * @param  array   $index $list数组中与Excel表格表头$header中每个项目对应的字段的名字(key值) 
 * 比如: $header = array('编号','姓名','性别','年龄'); 
 *       $index = array('id','username','sex','age'); 
 *       $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24)); 
 * @return [array] [数组] 
 */
function createtable($list, $filename, $header = array(), $index = array()) {
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:filename=" . $filename . ".xls");
    $teble_header = implode("\t", $header);
    $strexport = $teble_header . "\r";
    foreach ($list as $row) {
        foreach ($index as $val) {
            $strexport.=$row[$val] . "\t";
        }
        $strexport.="\r";
    }
    //$strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
    exit($strexport);
}

?>