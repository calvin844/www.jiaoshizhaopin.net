<?php

/*
 * 74cms 计划任务 清除缓存
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once('../include/common.inc.php');
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
$sql = "select e.*,r.* from " . table('resume_entrust') . " as e left join " . table('resume_jobs') . " as r on e.id=r.pid where r.pid > 0  order by e.days asc limit 1000";
$result = $db->getall($sql);

foreach ($result as $row) {
    if (empty($row['pid'])) {
        update_entrust($row['pid']);
        continue;
    }
    if ($row['subclass'] == 0) {
        if (!empty($row['category'])) {
            $c_str = " category = " . $row['category'];
        }
    } else {
        if (!empty($row['subclass'])) {
            $c_str = " subclass = " . $row['subclass'];
        }
    }
    if ($row['sdistrict'] == 0) {
        if (!empty($row['district'])) {
            $d_str = " and district = " . $row['district'];
        }
    } else {
        if (!empty($row['sdistrict'])) {
            $d_str = " and sdistrict = " . $row['sdistrict'];
        }
    }
    if (empty($d_str) || empty($c_str)) {
        update_entrust($row['pid']);
        continue;
    }
    $sql = "select id,jobs_name,company_id,companyname,uid from " . table('jobs') . " where " . $c_str . $d_str . " limit 10";
    $jobs = $db->getall($sql);
    if (empty($jobs)) {
        update_entrust_days($row['pid']);
        continue;
    } else {
        $resume_basic = $db->getone("select id,display_name,fullname,refreshtime from " . table('resume') . " where id=" . $row['pid']);
        if ($resume_basic['refreshtime'] < 1420041600) {
            update_entrust($row['pid']);
            continue;
        }
        foreach ($jobs as $key => $value) {
            if (check_jobs_apply($value['id'], $row['pid'], $row['uid'])) {
                update_entrust_days($row['pid']);
                continue;
            }
            if ($resume_basic['display_name'] == "2") {
                $personal_fullname = "N" . str_pad($resume_basic['id'], 7, "0", STR_PAD_LEFT);
            } elseif ($resume_basic['display_name'] == "3") {
                $personal_fullname = cut_str($resume_basic['fullname'], 1, 0, "**");
            } else {
                $personal_fullname = $resume_basic['fullname'];
            }
            $addarr['resume_id'] = $resume_basic['id'];
            $addarr['resume_name'] = $personal_fullname;
            $addarr['personal_uid'] = intval($row['uid']);
            $addarr['jobs_id'] = $value['id'];
            $addarr['jobs_name'] = $value['jobs_name'];
            $addarr['company_id'] = $value['company_id'];
            $addarr['company_name'] = $value['companyname'];
            $addarr['company_uid'] = $value['uid'];
            $addarr['notes'] = "系统自动投递";
            $addarr['apply_addtime'] = time();
            $addarr['personal_look'] = 1;
            $db->query("Delete from  " . table('personal_jobs_apply') . " WHERE personal_uid = '" . intval($row['uid']) . "' AND jobs_id='" . intval($value['id']) . "'  AND resume_id='" . intval($resume_basic['id']) . "'");
            inserttable(table('personal_jobs_apply'), $addarr);
        }
        update_entrust($row['pid']);
    }
    sleep(1);
    // $db->query("UPDATE ".table('jobs_search_wage')." SET refreshtime='{$time}' WHERE id='{$row['cp_jobid']}' LIMIT 1");
}

echo "自动投递完成";
/*
  //更新任务时间表
  if ($crons['weekday'] >= 0) {
  $weekday = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
  $nextrun = strtotime("Next " . $weekday[$crons['weekday']]);
  } elseif ($crons['day'] > 0) {
  $nextrun = strtotime('+1 months');
  $nextrun = mktime(0, 0, 0, date("m", $nextrun), $crons['day'], date("Y", $nextrun));
  } else {
  $nextrun = time();
  }
  if ($crons['hour'] >= 0) {
  $nextrun = strtotime('+1 days', $nextrun);
  $nextrun = mktime($crons['hour'], 0, 0, date("m", $nextrun), date("d", $nextrun), date("Y", $nextrun));
  }
  if (intval($crons['minute']) > 0) {
  $nextrun = strtotime('+1 hours', $nextrun);
  $nextrun = mktime(date("H", $nextrun), $crons['minute'], 0, date("m", $nextrun), date("d", $nextrun), date("Y", $nextrun));
  }
  $setsqlarr['nextrun'] = $nextrun;
  $setsqlarr['lastrun'] = time();
  updatetable(table('crons'), $setsqlarr, " cronid ='" . intval($crons['cronid']) . "'");
 * 
 */

function check_jobs_apply($jobs_id, $resume_id, $p_uid) {
    global $db;
    $sql = "select did from " . table('personal_jobs_apply') . " WHERE personal_uid = '" . intval($p_uid) . "' AND jobs_id='" . intval($jobs_id) . "'  AND resume_id='" . intval($resume_id) . "' LIMIT 1";
    return $db->getone($sql);
}

function update_entrust($id) {
    global $db;
    $db->query("delete from " . table('resume_entrust') . " where id=" . $id);
    updatetable(table("resume"), array("entrust" => "0"), " id=" . $id . " ");
}

function update_entrust_days($id) {
    global $db;
    $sql = "select * from " . table('resume_entrust') . " WHERE id = '" . intval($id) . "' LIMIT 1";
    $e = $db->getone($sql);
    if ($e['days'] >= 5) {
        update_entrust($id);
    } else {
        updatetable(table("resume_entrust"), array("days" => ($e['days'] + 1)), " id=" . $id . " ");
    }
}

?>