<?php
 /*
 * 74cms ajax �鿴������ͼ
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
*/
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)).'/include/plus.common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : 'company_map';
require_once(QISHI_ROOT_PATH.'include/mysql.class.php');
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
if ($act=="company_map")
{	
	$id = intval($_GET['id']);
        $company_profile=$db->getone("select * from ".table('company_profile')." where id=".$id." LIMIT 1");
	$map_x = trim($company_profile['map_x']);
	$map_y = trim($company_profile['map_y']);
	$map_zoom = trim($company_profile['map_zoom']);
	$companyname = trim($company_profile['companyname']);
	$address = trim($company_profile['address']);
	$tpl=QISHI_ROOT_PATH.'templates/'.$_CFG['template_dir']."ajax_map.htm";
	$contents=file_get_contents($tpl);
	$contents=str_replace('{#$company.map_x#}',$map_x,$contents);
	$contents=str_replace('{#$company.map_y#}',$map_y,$contents);
	$contents=str_replace('{#$company.map_zoom#}',$map_zoom,$contents);
	$contents=str_replace('{#$company.companyname#}',$companyname,$contents);
	$contents=str_replace('{#$company.address#}',$address,$contents);
	exit($contents);
}
?>