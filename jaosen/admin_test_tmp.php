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
$sql = "select count(*) as c from " . table('test_tmp') . " where q1 = 1";
$q1_1 = $db->getone($sql);
$q1_1 = $q1_1['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q1 = 2";
$q1_2 = $db->getone($sql);
$q1_2 = $q1_2['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q1 = 3";
$q1_3 = $db->getone($sql);
$q1_3 = $q1_3['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q1 = 4";
$q1_4 = $db->getone($sql);
$q1_4 = $q1_4['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q1 = 5";
$q1_5 = $db->getone($sql);
$q1_5 = $q1_5['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q2 = 1";
$q2_1 = $db->getone($sql);
$q2_1 = $q2_1['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q2 = 2";
$q2_2 = $db->getone($sql);
$q2_2 = $q2_2['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q2 = 3";
$q2_3 = $db->getone($sql);
$q2_3 = $q2_3['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q3 = 1";
$q3_1 = $db->getone($sql);
$q3_1 = $q3_1['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q3 = 2";
$q3_2 = $db->getone($sql);
$q3_2 = $q3_2['c'];
$sql = "select count(*) as c from " . table('test_tmp') . " where q3 = 3";
$q3_3 = $db->getone($sql);
$q3_3 = $q3_3['c'];
echo '������ʦ��Ƹ����Ŀ�ģ�_���������ҹ���:' . $q1_1 . "</br>" . '������ʦ��Ƹ����Ŀ�ģ�_����ʦ�ʸ�֤:' . $q1_2 . "</br>" . '������ʦ��Ƹ����Ŀ�ģ�_����ʦ����:' . $q1_3 . "</br>" . '������ʦ��Ƹ����Ŀ�ģ�_��ʵϰ����:' . $q1_4 . "</br>" . '������ʦ��Ƹ����Ŀ�ģ�_���һ����û�ʺϵĹ���:' . $q1_5 . "</br>" . '������ʲô����ѧУ�����أ�_������ѵ����:' . $q2_1 . "</br>" . '������ʲô����ѧУ�����أ�_���ѧУ:' . $q2_2 . "</br>" . '������ʲô����ѧУ�����أ�_����ѧУ:' . $q2_3 . "</br>" . '�����ʦ��Ƹ�������ע����ʲô��Ϣ��_����ѧУ��Ƹ����:' . $q3_1 . "</br>" . '�����ʦ��Ƹ�������ע����ʲô��Ϣ��_���λ��Ƹ��Ϣ:' . $q3_2 . "</br>" . '�����ʦ��Ƹ�������ע����ʲô��Ϣ��_����ʦ��Ϣ:' . $q3_3 . "</br>";
?>