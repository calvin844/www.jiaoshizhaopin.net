<?php

/*
 * 74cms 计划任务 每日数据统计
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
//$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
global $_CFG;
$start_id = $_GET['start_id'] ? intval($_GET['start_id']) : 0;
$_CFG['web_logo'] = $_CFG['web_logo'] ? $_CFG['web_logo'] : 'logo.gif';
$_CFG['upfiles_dir'] = $_CFG['site_dir'] . "data/" . $_CFG['updir_images'] . "/";
$sql = "select * from " . table("mailconfig");
$mailconfig_tmp = $db->getall($sql);
//var_dump($mailconfig_tmp);exit;
foreach ($mailconfig_tmp as $m) {
    $mailconfig[$m['name']] = $m['value'];
}
$mailconfig['smtpservers'] = explode('|-_-|', $mailconfig['smtpservers']);
$mailconfig['smtpusername'] = explode('|-_-|', $mailconfig['smtpusername']);
$mailconfig['smtppassword'] = explode('|-_-|', $mailconfig['smtppassword']);
$mailconfig['smtpfrom'] = explode('|-_-|', $mailconfig['smtpfrom']);
$mailconfig['smtpport'] = explode('|-_-|', $mailconfig['smtpport']);
$mailconfig['smtpnum'] = explode('|-_-|', $mailconfig['smtpnum']);
$mailconfig['smtpupdatetime'] = explode('|-_-|', $mailconfig['smtpupdatetime']);
for ($i = 0; $i < count($mailconfig['smtpservers']); $i++) {
    $mailconfigarray[] = array('smtpservers' => $mailconfig['smtpservers'][$i], 'smtpusername' => $mailconfig['smtpusername'][$i], 'smtppassword' => $mailconfig['smtppassword'][$i], 'smtpfrom' => $mailconfig['smtpfrom'][$i], 'smtpport' => $mailconfig['smtpport'][$i], 'smtpnum' => $mailconfig['smtpnum'][$i], 'smtpupdatetime' => $mailconfig['smtpupdatetime'][$i]);
}
foreach ($mailconfigarray as $m) {
    if (($m['smtpnum'] - 1) > 0 && empty($mc)) {
        $mc = $m;
    }
}
$end_id = "";
$today = strtotime(date('Y-m-d'));
$list = $db->getall("select * from " . table('jobs_subscribe') . " where id > " . $start_id . " order by id asc limit 100");
//$list = $db->getall("select * from " . table('jobs_subscribe') . " where id =14064  order by id asc limit 1");
if (!empty($list)) {
    foreach ($list as $value) {
        $addtime = strtotime(date('Y-m-d', $value['addtime']));
        if (!filter_var($value['email'], FILTER_VALIDATE_EMAIL)) {
            $db->query("Delete from " . table('jobs_subscribe') . " WHERE id='" . intval($value['id']) . "'");
            continue;
        }
        $subject_id = trim($value['subject_id']);
        $subject_id = trim($subject_id, ",");
        $subject_arr = explode(",", $subject_id);
        $subject_id = "";
        foreach ($subject_arr as $s) {
            $arr = explode(".", $s);
            $arr[2] == 0 ? $subject_id .=$arr[1] . "," : $subject_id .=$arr[2] . ",";
        }
        $subject_id = trim($subject_id, ",");
        if (empty($subject_id)) {
            $db->query("Delete from " . table('jobs_subscribe') . " WHERE id='" . intval($value['id']) . "'");
            continue;
        }
        if (((($today - $addtime) / 24 / 3600) % $value['days']) == 0) {
            $end_id = $value['id'];
            //var_dump($value['id']);exit;
            $subject_id = trim($value['subject_id']);
            $district = trim($value['district']);
            $district_res = explode(",", $district);
            foreach ($district_res as $key => $v) {
                $district_arr = explode(".", $v);
                if (intval($district_arr[1]) == 0) {
                    $district_str_arr[] = $district_arr[0];
                } else {
                    $sdistrict_str_arr[] = $district_arr[1];
                }
            }
            $district_str = implode(",", $district_str_arr);
            $sdistrict_str = implode(",", $sdistrict_str_arr);
            if (trim($district_str, ",")) {
                $wheresql = " where district in (" . $district_str . ")";
            }
            if (trim($sdistrict_str, ",")) {
                $wheresql = $wheresql == '' ? " where sdistrict in (" . $sdistrict_str . ")" : $wheresql . " OR sdistrict in (" . $sdistrict_str . ")";
            }

            if (!empty($subject_id)) {
                $subject_id = trim($subject_id, ",");
                $subject_arr = explode(",", $subject_id);
                $subject_id = "";
                foreach ($subject_arr as $s) {
                    $arr = explode(".", $s);
                    $arr[2] == 0 ? $subject_id .=$arr[1] . "," : $subject_id .=$arr[2] . ",";
                }
                $subject_id = trim($subject_id, ",");
                if (empty($subject_id)) {
                    $db->query("Delete from " . table('jobs_subscribe') . " WHERE id='" . intval($value['id']) . "'");
                    continue;
                }


                $subject_name = "";
                $sql = "select * from " . table("category_jobs") . " where id in(" . $subject_id . ")";
                $subject = $db->getall($sql);

                foreach ($subject as $s) {
                    $category_id_str.= $s['id'] . ",";
                    $subject_name.=$s['categoryname'] . ",";
                }
                $subject_name = trim($subject_name, ",");
                $category_id_str = trim($category_id_str, ",");

                //var_dump($category_id_str);exit;
                if ($wheresql != "") {
                    $wheresql .= " AND subclass in(" . $category_id_str . ") ";
                } else {
                    $wheresql = " where subclass in(" . $category_id_str . ") ";
                }
            }
            $search_res = $db->getall("select p_id,type from " . table('jiaoshi_article_jobs_index') . $wheresql . " order by refreshtime desc limit 10");
            foreach ($search_res as $n) {
                $idarr[] = array('id' => $n['p_id'], 'type' => $n['type']);
            }
            $jobs_id_str = "";
            $news_id_str = "";
            if (empty($idarr)) {
                $id_str = 0;
            } else {
                foreach ($idarr as $i) {
                    if ($i['type'] == "article") {
                        $news_id_str .= $i['id'] . ",";
                    } elseif ($i['type'] == "jobs") {
                        $jobs_id_str .= $i['id'] . ",";
                    }
                }
            }
            $jobs_id_str = trim($jobs_id_str, ",");
            $news_id_str = trim($news_id_str, ",");
            $html = '<table width="700" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;color:#555; font:16px/26px \'微软雅黑\',\'宋体\',Arail; ">
	    	<tbody><tr>
	        	<td style="height:62px; background-color:#FCFCFC; padding:10px 0 0 10px;">
	            	<a target="_blank" href="' . $_CFG['site_domain'] . $_CFG['site_dir'] . '"><img src="' . $_CFG['site_domain'] . $_CFG['upfiles_dir'] . $_CFG['web_logo'] . '" width="200" height="45" style="border:none;"/></a>
	            </td>
	        </tr>
	        <tr style="background-color:#fff;">
	        	<td style="padding:30px 38px;">
	            	<div>亲爱的<span style="color:#017FCF;"><a href="mailto:' . $value['email'] . '" target="_blank">' . $value['email'] . '</a></span>，你好!</div>';
            if (empty($jobs_id_str) && empty($news_id_str)) {
                $html .='<div style="text-indent:2em;">你在<a style="color:#017FCF;" href="' . $_CFG['site_domain'] . $_CFG['site_dir'] . '" target="_blank">' . $_CFG['site_name'] . '</a>上订阅了"<span style="color:#017FCF;">' . $subject_name . '</span>"相关栏目信息，经我们精心的挑选，并没有找到与您意向相符的信息，您可以<a href="' . $_CFG['site_domain'] . $_CFG['site_dir'] . '">重新选择</a>。祝职业更上一层楼！</div>';
            } else {
                $html .='<div style="text-indent:2em;">你在<a style="color:#017FCF;" href="' . $_CFG['site_domain'] . $_CFG['site_dir'] . '" target="_blank">' . $_CFG['site_name'] . '</a>上订阅了"<span style="color:#017FCF;">' . $subject_name . '</span>"相关栏目信息，经我们精心的挑选，现将筛选结果发送给你，希望我们的邮件能够对你有所帮助。祝职业更上一层楼！</div>
	                <div style="text-indent:2em;"></div>
	            	<div style="border-bottom:1px solid #e6e6e6; font-weight:bold; margin:20px 0 0 0; padding-bottom:5px;">以下是你订阅的信息：</div>
	            	<ul style="list-style:none; margin:0; padding:0;">';
                if (!empty($jobs_id_str)) {
                    $jobslist = $db->getall("select id,jobs_name,companyname,company_id,district_cn from " . table('jobs') . " where id in (" . $jobs_id_str . ")");
                }
                if (!empty($news_id_str)) {
                    $newslist = $db->getall("select id,job_name,article_id from " . table('jiaoshi_article_jobs') . " where id in (" . $news_id_str . ")");
                    //var_dump("select id,job_name,article_id from " . table('jiaoshi_article_jobs') . " where id in (" . $news_id_str . ")");exit;
                }

                if (!empty($jobslist) && !empty($newslist)) {
                    $result = array_merge($jobslist, $newslist);
                } elseif (empty($jobslist) && !empty($newslist)) {
                    $result = $newslist;
                } elseif (!empty($jobslist) && empty($newslist)) {
                    $result = $jobslist;
                }

                foreach ($result as $k => $v) {
                    $refreshtime[$k] = $v['refreshtime'];
                }
                array_multisort($refreshtime, SORT_DESC, $result);

                if (!empty($result)) {
                    foreach ($result as $r) {
                        $n_flag = $r['companyname'] ? 0 : 1;
                        if ($n_flag) {
                            $parent_news = $db->getone("select title,district_cn from " . table('article') . " where id=" . $r['article_id'] . " limit 1");
                            $r['parent_news'] = $parent_news['title'];
                            $r['district_cn'] = $parent_news['district_cn'];
                            $news_url = url_rewrite('QS_newsshow', array('id' => $r['article_id']));
                        } else {
                            $news_url = "";
                            $jobs_url = url_rewrite('QS_jobsshow', array('id' => $r['id']));
                            $company_url = url_rewrite('QS_companyshow', array('id' => $r['company_id']));
                        }
                        //var_dump($company_url);
                        $name = $r['job_name'] ? $r['job_name'] : $r['jobs_name'];
                        $parent_name = $r['parent_news'] ? $r['parent_news'] : $r['companyname'];
                        $district = $r['district_cn'];
                        $url = $n_flag ? $news_url : $jobs_url;
                        $parent_url = $n_flag ? $news_url : $company_url;
                        $html .='<li style="list-style:none;padding:15px 10px 15px 0;border-bottom:1px solid #e6e6e6; overflow:hidden;">
                                <div>
                                <a target="_blank" style="float:left; color:#017FCF; text-decoration:underline;" href="' . $url . '">
                                ' . $name . '
                                </a>
                                <a target="_blank" style="float:right; color:#017FCF; text-decoration:underline;" href="' . $url . '">
                                查看详情
                                </a><br>
                                <div><a target="_blank" style="font-weight:700; color:#333; text-decoration:none;" href="' . $parent_url . '">' . $parent_name . '</a></div>
                                <div>工作地区：' . $district . '</div>
                                </div>
                                </li>';
                    }
                }

                $html .='</ul>
	                <a target="_blank" style="float:right; text-decoration:underline; font-weight:700; margin:15px 0;color:#017FCF;" href="' . $_CFG["site_domain"] . $_CFG["site_dir"] . '">查看更多信息</a>
	            </td>
	        </tr>';
            }


            $html .='
	        <tr>
	        	<td style="text-align:center; color:#c9cbce; font-size:14px; padding:5px 0;">公司网址：<a style="color:#017FCF;" target="_blank" href="' . $_CFG["site_domain"] . $_CFG["site_dir"] . '">' . $_CFG["site_domain"] . $_CFG["site_dir"] . '</a>   </td>
	        </tr>
	        <tr>
	            <td style="line-height:30px;text-align:right;font-size:14px;"> 为保证邮箱正常接收，请将<a href="mailto:' . $mc['smtpfrom'] . '" target="_blank">' . $mc['smtpfrom'] . '</a>添加进你的通讯录</td>
	        </tr>
	        <tr>
	            <td style="line-height:30px;text-align:right;font-size:14px;"> 不想继续收到职位订阅？<a href="' . $_CFG["site_domain"] . $_CFG["site_dir"] . '/subscribe/delete.php?email=' . $value['email'] . '" target="_blank">点击退订</a></td>
	        </tr>
	    </tbody></table>';
            //$value['email'] = "377128799@qq.com";
            $send_flag = @smtp_mail($value['email'], $_CFG['site_name'] . "向您发送了您订阅的栏目信息", $html, $mc['smtpfrom'], $_CFG['site_name']);

            //$send_flag = smtp_mail("2429328824@qq.com", $_CFG['site_name'] . "向您发送了您订阅的栏目信息", $html, $mc['smtpfrom'], $_CFG['site_name']);
            //var_dump($send_flag);exit;
        }
    }
}
/*
  if (empty($end_id)) {
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
  //var_dump($setsqlarr);exit;
  updatetable(table('crons'), $setsqlarr, " cronid ='" . intval($crons['cronid']) . "'");
  } else {
  $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  $html.='<html xmlns="http://www.w3.org/1999/xhtml">';
  $html.='<head>';
  $html.='</head>';
  $html.='<body>';
  $html.='<script type="text/javascript" language="javascript">';
  $html.='function reloadyemian()';
  $html.='{ ';
  $html.='window.location.href="http://' . $_SERVER['HTTP_HOST'] . '/user/do_crons.php?' . 'id=8&start_id=' . $end_id . '"';
  $html.='} ';
  $html.='window.setTimeout("reloadyemian();",3000); ';
  $html.='</script> ';
  $html.='</body></html>';
  echo $html;
  }
 * 
 */

if (!empty($end_id)) {
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $html.='<html xmlns="http://www.w3.org/1999/xhtml">';
    $html.='<head>';
    $html.='</head>';
    $html.='<body>';
    $html.='<script type="text/javascript" language="javascript">';
    $html.='function reloadyemian()';
    $html.='{ ';
    $html.='window.location.href="http://' . $_SERVER['HTTP_HOST'] . '/user/do_crons.php?' . 'id=8&start_id=' . $end_id . '"';
    $html.='} ';
    $html.='window.setTimeout("reloadyemian();",3000); ';
    $html.='</script> ';
    $html.='</body></html>';
    echo $html;
}
//var_dump($end_id);
//exit;
?>