<?php

/*
 * 74cms ����
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../data/config.php');
require_once(dirname(__FILE__) . '/include/admin_common.inc.php');
$list = array();
$sql = "select id,fullname,sex_cn,birthdate,experience_cn,district_cn,wage_cn,education_cn,addtime from " . table('resume') . " where  addtime>1496246400 and addtime<1527782400";
$result = $db->getall($sql);
$header = array('ID', '����', '�Ա�', '����', '��������', '��������', '������н', 'ѧ��', '���ʱ��');
$index = array('id', 'fullname', 'sex_cn', 'birthdate', 'experience_cn', 'district_cn', 'wage_cn', 'education_cn', 'addtime');
//$result_index = array('ID', '����', '�Ա�', '����', '��������', '��������', '������н', 'ѧ��', '���ʱ��');
foreach ($result as $r) {
    $r['sex_cn'] = $r['sex_cn'] != "" ? $r['sex_cn'] : "δ��д";
    $r['birthdate'] = $r['birthdate'] > 0 ? date("Y") - $r['birthdate'] : "0";
    $r['experience_cn'] = $r['experience_cn'] != "" ? $r['experience_cn'] : "δ��д";
    $r['district_cn'] = $r['district_cn'] != "" ? $r['district_cn'] : "δ��д";
    $r['wage_cn'] = $r['wage_cn'] != "" ? $r['wage_cn'] : "δ��д";
    $r['education_cn'] = $r['education_cn'] != "" ? $r['education_cn'] : "δ��д";
    $r['addtime'] = date("Y-m-d", $r['addtime']);
    $list[] = $r;
}
createtable($list, '20180605', $header, $index);
//exportExcel($list, "20180605", $result_index);

/**
 * ����(����)Excel���ݱ�� 
 * @param  array   $list Ҫ�����������ʽ������ 
 * @param  string  $filename ������Excel������ݱ���ļ��� 
 * @param  array   $header Excel���ı�ͷ 
 * @param  array   $index $list��������Excel����ͷ$header��ÿ����Ŀ��Ӧ���ֶε�����(keyֵ) 
 * ����: $header = array('���','����','�Ա�','����'); 
 *       $index = array('id','username','sex','age'); 
 *       $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'��','age'=>24)); 
 * @return [array] [����] 
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