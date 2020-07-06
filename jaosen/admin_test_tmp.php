<?php

/*
 * 74cms 个人
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
echo '您到教师招聘网的目的？_创建简历找工作:' . $q1_1 . "</br>" . '您到教师招聘网的目的？_考教师资格证:' . $q1_2 . "</br>" . '您到教师招聘网的目的？_考教师编制:' . $q1_3 . "</br>" . '您到教师招聘网的目的？_找实习机会:' . $q1_4 . "</br>" . '您到教师招聘网的目的？_浏览一下有没适合的工作:' . $q1_5 . "</br>" . '您想找什么样的学校工作呢？_教育培训机构:' . $q2_1 . "</br>" . '您想找什么样的学校工作呢？_民办学校:' . $q2_2 . "</br>" . '您想找什么样的学校工作呢？_公办学校:' . $q2_3 . "</br>" . '进入教师招聘网您最关注的是什么信息？_公办学校招聘简章:' . $q3_1 . "</br>" . '进入教师招聘网您最关注的是什么信息？_广告位招聘信息:' . $q3_2 . "</br>" . '进入教师招聘网您最关注的是什么信息？_考教师信息:' . $q3_3 . "</br>";
?>