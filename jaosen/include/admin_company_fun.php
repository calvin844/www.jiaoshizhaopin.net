<?php

/*
 * 74cms �������� ��ҵ�û���غ���
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
if (!defined('IN_QISHI')) {
    die('Access Denied!');
}

//******************************ְλ����**********************************
//��ȡְλ��Ϣ�б�
function get_jobs($offset, $perpage, $get_sql = '') {
    global $db, $timestamp;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['jobs_name'] = cut_str($row['jobs_name'], 12, 0, "...");
        if (!empty($row['highlight'])) {
            $row['jobs_name'] = "<span style=\"color:{$row['highlight']}\">{$row['jobs_name']}</span>";
        }
        $row['companyname'] = cut_str($row['companyname'], 18, 0, "...");
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']), false);
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['id']), true, $row['subsite_id']);
        $get_resume_nolook = $db->getone("select count(*) from " . table('personal_jobs_apply') . " where personal_look=1 and jobs_id=" . $row['id']);
        $get_resume_all = $db->getone("select count(*) from " . table('personal_jobs_apply') . " where jobs_id=" . $row['id']);
        $row['get_resume'] = "( δ����" . $get_resume_nolook['count(*)'] . " / �ܣ�" . $get_resume_all['count(*)'] . " )";
        $row_arr[] = $row;
    }
    return $row_arr;
}

function distribution_jobs($id) {
    global $db, $_CFG;
    if (!is_array($id))
        $id = array($id);
    $time = time();
    foreach ($id as $v) {
        $v = intval($v);
        $t1 = $db->getone("select * from " . table('jobs') . " where id='{$v}' LIMIT 1");
        $t2 = $db->getone("select * from " . table('jobs_tmp') . " where id='{$v}' LIMIT 1");
        if ((empty($t1) && empty($t2)) || (!empty($t1) && !empty($t2))) {
            continue;
        } else {
            $j = !empty($t1) ? $t1 : $t2;
            if (!empty($t1) && $j['audit'] == "1" && $j['display'] == "1" && $j['user_status'] == "1") {
                if ($_CFG['outdated_jobs'] == "1") {
                    if ($j['deadline'] > $time && ($j['setmeal_deadline'] == "0" || $j['setmeal_deadline'] > $time)) {
                        add_jobs_info($j);
                        continue;
                    }
                } else {
                    add_jobs_info($j);
                    continue;
                }
            } elseif (!empty($t2)) {
                if ($j['audit'] != "1" || $j['display'] != "1" || $j['user_status'] != "1") {
                    continue;
                } else {
                    if ($_CFG['outdated_jobs'] == "1" && ($j['deadline'] < $time || ($j['setmeal_deadline'] < $time && $j['setmeal_deadline'] != "0"))) {
                        continue;
                    }
                }
            }
            //������
            $j = array_map('addslashes', $j);
            if (!empty($t1)) {
                $db->query("Delete from " . table('jobs') . " WHERE id='{$v}' LIMIT 1");
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id=" . intval($v) . " and type = 'jobs' LIMIT 1");
                $db->query("Delete from " . table('jobs_tmp') . " WHERE id='{$v}' LIMIT 1");
                if (inserttable(table('jobs_tmp'), $j)) {
                    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_key') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('view_jobs') . " WHERE jobsid='{$v}'");
                }
            } else {
                $db->query("Delete from " . table('jobs') . " WHERE id='{$v}' LIMIT 1");
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id=" . intval($v) . " and type = 'jobs' LIMIT 1");
                $db->query("Delete from " . table('jobs_tmp') . " WHERE id='{$v}' LIMIT 1");
                if (inserttable(table('jobs'), $j)) {
                    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_key') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id='{$v}' {$uidsql} LIMIT 1");
                    $searchtab['id'] = $j['id'];
                    $searchtab['uid'] = $j['uid'];
                    $searchtab['subsite_id'] = $j['subsite_id'];
                    $searchtab['recommend'] = $j['recommend'];
                    $searchtab['emergency'] = $j['emergency'];
                    $searchtab['nature'] = $j['nature'];
                    $searchtab['sex'] = $j['sex'];
                    $searchtab['topclass'] = $j['topclass'];
                    $searchtab['category'] = $j['category'];
                    $searchtab['subclass'] = $j['subclass'];
                    $searchtab['trade'] = $j['trade'];
                    $searchtab['district'] = $j['district'];
                    $searchtab['sdistrict'] = $j['sdistrict'];
                    $searchtab['street'] = $j['street'];
                    $searchtab['education'] = $j['education'];
                    $searchtab['experience'] = $j['experience'];
                    $searchtab['wage'] = $j['wage'];
                    $searchtab['refreshtime'] = $j['refreshtime'];
                    $searchtab['scale'] = $j['scale'];
                    //--
                    inserttable(table('jobs_search_wage'), $searchtab);
                    inserttable(table('jobs_search_scale'), $searchtab);
                    //--
                    $searchtab['map_x'] = $j['map_x'];
                    $searchtab['map_y'] = $j['map_y'];
                    inserttable(table('jobs_search_rtime'), $searchtab);
                    unset($searchtab['map_x'], $searchtab['map_y']);
                    //--
                    $searchtab['stick'] = $j['stick'];
                    inserttable(table('jobs_search_stickrtime'), $searchtab);
                    unset($searchtab['stick']);
                    //--
                    $searchtab['click'] = $j['click'];
                    inserttable(table('jobs_search_hot'), $searchtab);
                    unset($searchtab['click']);
                    //--
                    $searchtab['key'] = $j['key'];
                    $searchtab['likekey'] = $j['jobs_name'] . ',' . $j['companyname'];
                    $searchtab['map_x'] = $j['map_x'];
                    $searchtab['map_y'] = $j['map_y'];
                    inserttable(table('jobs_search_key'), $searchtab);
                    unset($searchtab);
                    $tag = explode('|', $j['tag']);
                    $tagindex = 1;
                    $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
                    if (!empty($tag) && is_array($tag)) {
                        foreach ($tag as $k => $v) {
                            if ($k < 5) {
                                $vid = explode(',', $v);
                                $tagsql['tag' . $tagindex] = intval($vid[0]);
                                $tagindex++;
                            }
                        }
                    }
                    $tagsql['id'] = $j['id'];
                    $tagsql['uid'] = $j['uid'];
                    $tagsql['subsite_id'] = $j['subsite_id'];
                    $tagsql['topclass'] = $j['topclass'];
                    $tagsql['category'] = $j['category'];
                    $tagsql['subclass'] = $j['subclass'];
                    $tagsql['district'] = $j['district'];
                    $tagsql['sdistrict'] = $j['sdistrict'];
                    inserttable(table('jobs_search_tag'), $tagsql);
                }
            }
        }
    }
}

function add_jobs_info($j) {
    global $db, $_CFG;
    $db->query("Delete from " . table('jobs_search_hot') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_key') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_scale') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_wage') . " WHERE id='{$j['id']}' LIMIT 1");
    $db->query("Delete from " . table('jobs_search_tag') . " WHERE id='{$j['id']}' LIMIT 1");
    $searchtab['id'] = $j['id'];
    $searchtab['uid'] = $j['uid'];
    $searchtab['subsite_id'] = $j['subsite_id'];
    $searchtab['recommend'] = $j['recommend'];
    $searchtab['emergency'] = $j['emergency'];
    $searchtab['nature'] = $j['nature'];
    $searchtab['sex'] = $j['sex'];
    $searchtab['topclass'] = $j['topclass'];
    $searchtab['category'] = $j['category'];
    $searchtab['subclass'] = $j['subclass'];
    $searchtab['trade'] = $j['trade'];
    $searchtab['district'] = $j['district'];
    $searchtab['sdistrict'] = $j['sdistrict'];
    $searchtab['street'] = $j['street'];
    $searchtab['education'] = $j['education'];
    $searchtab['experience'] = $j['experience'];
    $searchtab['wage'] = $j['wage'];
    $searchtab['refreshtime'] = $j['refreshtime'];
    $searchtab['scale'] = $j['scale'];
    //--
    inserttable(table('jobs_search_wage'), $searchtab);
    inserttable(table('jobs_search_scale'), $searchtab);
    //--
    $searchtab['map_x'] = $j['map_x'];
    $searchtab['map_y'] = $j['map_y'];
    inserttable(table('jobs_search_rtime'), $searchtab);
    unset($searchtab['map_x'], $searchtab['map_y']);
    //--
    $searchtab['stick'] = $j['stick'];
    inserttable(table('jobs_search_stickrtime'), $searchtab);
    unset($searchtab['stick']);
    //--
    $searchtab['click'] = $j['click'];
    inserttable(table('jobs_search_hot'), $searchtab);
    unset($searchtab['click']);
    //--
    $searchtab['key'] = $j['key'];
    $searchtab['likekey'] = $j['jobs_name'] . ',' . $j['companyname'];
    $searchtab['map_x'] = $j['map_x'];
    $searchtab['map_y'] = $j['map_y'];
    inserttable(table('jobs_search_key'), $searchtab);
    unset($searchtab);
    $tag = explode('|', $j['tag']);
    $tagindex = 1;
    $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
    if (!empty($tag) && is_array($tag)) {
        foreach ($tag as $v) {
            $vid = explode(',', $v);
            $tagsql['tag' . $tagindex] = intval($vid[0]);
            $tagindex++;
        }
    }
    $tagsql['id'] = $j['id'];
    $tagsql['uid'] = $j['uid'];
    $tagsql['subsite_id'] = $j['subsite_id'];
    $tagsql['topclass'] = $j['topclass'];
    $tagsql['category'] = $j['category'];
    $tagsql['subclass'] = $j['subclass'];
    $tagsql['district'] = $j['district'];
    $tagsql['sdistrict'] = $j['sdistrict'];
    inserttable(table('jobs_search_tag'), $tagsql);
}

function distribution_jobs_uid($uid = "", $company_id = "") {
    global $db;
    if (!empty($uid)) {
        $uid = is_array($uid) ? $uid : array($uid);
        $uid = implode(",", $uid);
        $result = $db->query("select id from " . table('jobs') . " where uid in({$uid}) UNION ALL select id from " . table('jobs_tmp') . " where uid in({$uid}) ");
    } else {
        $company_id = is_array($company_id) ? $company_id : array($company_id);
        $company_id = implode(",", $company_id);
        $result = $db->query("select id from " . table('jobs') . " where company_id in({$company_id}) UNION ALL select id from " . table('jobs_tmp') . " where company_id in({$company_id}) ");
    }
    while ($row = $db->fetch_array($result)) {
        $id[] = $row['id'];
    }
    if (!empty($id)) {
        return distribution_jobs($id);
    }
}

function get_jobs_one($id) {
    global $db;
    $id = intval($id);
    $tb1 = $db->getone("select * from " . table('jobs') . " where id='{$id}' LIMIT 1");
    $tb2 = $db->getone("select * from " . table('jobs_tmp') . " where id='{$id}' LIMIT 1");
    $val = !empty($tb1) ? $tb1 : $tb2;
    $val['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $val['id']), true, $val['subsite_id']);
    $val['company_url'] = url_rewrite('QS_companyshow', array('id' => $val['company_id']), false);
    $val['user'] = get_user($val['uid']);
    $val['contact'] = $db->getone("select * from " . table('jobs_contact') . " where pid='{$id}' LIMIT 1");
    return $val;
}

//ɾ��ְλ
function del_jobs($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $id_str = "";
    $sql = "select id from " . table('jobs') . " WHERE id IN ({$sqlin}) {$uidsql}";
    $del_id_arr = $db->getall($sql);
    if (empty($del_id_arr)) {
        $sql = "select id from " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) {$uidsql}";
        $del_id_arr = $db->getall($sql);
    }
    foreach ($del_id_arr as $da) {
        $id_str .= $da['id'] . ",";
    }
    $id_str = trim($id_str, ",");
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if ($db->query("Delete from " . table('jobs') . " WHERE id IN ({$sqlin}) {$uidsql}")) {
            $return = $return + $db->affected_rows();
            if (!empty($id_str)) {
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN(" . $id_str . ") and type = 'jobs' ");
            }
        } else {
            return false;
        }
        if ($db->query("Delete from " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) {$uidsql}")) {
            $return = $return + $db->affected_rows();
            if (!empty($id_str)) {
                $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN(" . $id_str . ") and type = 'jobs' ");
            }
        } else {
            return false;
        }
        if (!$db->query("Delete from " . table('jobs_contact') . " WHERE pid IN ({$sqlin}) "))
            return false;
        if (!$db->query("Delete from " . table('promotion') . " WHERE cp_jobid IN ({$sqlin}) "))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_hot') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_key') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_rtime') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_scale') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_wage') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('jobs_search_tag') . " WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("Delete from " . table('view_jobs') . " WHERE jobsid IN ({$sqlin})"))
            return false;
        return $return;
    }
    else {
        return false;
    }
}

//�޸�ְλ���״̬
function edit_jobs_audit($id, $audit, $reason, $pms_notice = '1') {
    global $db, $_CFG;
    $audit = intval($audit);
    $reason = trim($reason);
    if (!is_array($id)) {
        $id = array($id);
    }
    $sqlin = implode(",", $id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('jobs') . " SET audit={$audit}  WHERE id IN ({$sqlin})")) {
            return false;
        }
        $return = $return + $db->affected_rows();
        if (!$db->query("update  " . table('jobs_tmp') . " SET audit={$audit}  WHERE id IN ({$sqlin})")) {
            return false;
        }
        $return = $return + $db->affected_rows();
        distribution_jobs($id);
        //����վ����
        if ($pms_notice == '1') {
            $result = $db->query("SELECT * FROM " . table('jobs') . " WHERE id IN ({$sqlin})  UNION ALL  SELECT * FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin})");
            $reason = $reason == '' ? 'ԭ��δ֪' : 'ԭ��' . $reason;
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = $audit == '1' ? "��������ְλ��{$list['jobs_name']},�ɹ�ͨ����վ����Ա��ˣ�" : "��������ְλ��{$list['jobs_name']},δͨ����վ����Ա���,{$reason}";
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
                $website = 'http://m.jiaoshizhaopin.net/company_center/send_wechat_job_audit_core/' . $list['id'];
//                //dfopen($website);
                $ch = curl_init();
                // ����URL����Ӧ��ѡ��
                curl_setopt($ch, CURLOPT_URL, $website);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                // ץȡURL���������ݸ������
                curl_exec($ch);
                // �ر�cURL��Դ�������ͷ�ϵͳ��Դ
                curl_close($ch);
            }
        }
        //���δͨ������ԭ��
        if ($audit == '3') {
            foreach ($id as $list) {
                $auditsqlarr['jobs_id'] = $list;
                $auditsqlarr['reason'] = $reason;
                $auditsqlarr['addtime'] = time();
                inserttable(table('audit_reason'), $auditsqlarr);
            }
        }
        //�����ʼ�
        $mailconfig = get_cache('mailconfig');
        $sms = get_cache('sms_config');
        if ($audit == "1" && $mailconfig['set_jobsallow'] == "1") {//���ͨ��
            $result = $db->query("SELECT * FROM " . table('jobs') . " WHERE id IN ({$sqlin})  UNION ALL  SELECT * FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_jobsallow");
                }
            }
        }
        if ($audit == "3" && $mailconfig['set_jobsnotallow'] == "1") {//���δͨ��
            $result = $db->query("SELECT * FROM " . table('jobs') . " WHERE id IN ({$sqlin})  UNION ALL  SELECT * FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) ");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_jobsnotallow");
                }
            }
        }
        //sms		
        if ($audit == "1" && $sms['open'] == "1" && $sms['set_jobsallow'] == "1") {
            $result = $db->query("SELECT * FROM " . table('jobs') . " WHERE id IN ({$sqlin})  UNION ALL  SELECT * FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) ");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_jobsallow");
                }
            }
        }
        //sms
        if ($audit == "3" && $sms['open'] == "1" && $sms['set_jobsnotallow'] == "1") {//��֤δͨ��
            $result = $db->query("SELECT * FROM " . table('jobs') . " WHERE id IN ({$sqlin})  UNION ALL  SELECT * FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin}) ");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1") {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $list['uid'] . "&key=" . asyn_userkey($list['uid']) . "&jobs_name=" . $list['jobs_name'] . "&act=set_jobsnotallow");
                }
            }
        }
        //sms
        return $return;
    } else {
        return $return;
    }
}

function edit_jobs_display($id, $display) {
    global $db;
    $display = intval($display);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('jobs') . " SET display='{$display}'  WHERE id IN ({$sqlin})"))
            return false;
        distribution_jobs($id);
        return true;
    }
    return false;
}

function get_user($uid) {
    global $db;
    $sql = "select * from " . table('members') . " where uid=" . intval($uid) . " LIMIT 1";
    return $db->getone($sql);
}

function refresh_company($company_id, $refresjobs = false) {
    global $db;
    $return = 0;
    $time = time();
    if (!is_array($company_id))
        $company_id = array($company_id);
    $sqlin = implode(",", $company_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('company_profile') . " SET refreshtime='" . $time . "'  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        if ($refresjobs) {
            $id_str = "";
            $sql = "select id from " . table('jobs') . "  WHERE company_id IN ({$sqlin})";
            $jobs_id_arr = $db->getall($sql);
            $sql = "select id from " . table('jobs_tmp') . "  WHERE company_id IN ({$sqlin})";
            $jobs_tmp_id_arr = $db->getall($sql);
            $jobs_all_id_arr = array_merge($jobs_id_arr, $jobs_tmp_id_arr);
            foreach ($jobs_all_id_arr as $da) {
                $id_str .= $da['id'] . ",";
            }
            $id_str = trim($id_str, ",");
            if (!$db->query("update  " . table('jobs') . " SET refreshtime='" . $time . "'  WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jiaoshi_article_jobs_index') . " SET refreshtime='" . $time . "'  WHERE p_id IN(" . $id_str . ") and type = 'jobs' "))
                return false;
            if (!$db->query("update  " . table('jobs_tmp') . " SET refreshtime='" . $time . "'  WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_hot') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_key') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_rtime') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_scale') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_stickrtime') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;
            if (!$db->query("update  " . table('jobs_search_wage') . "  SET refreshtime='" . $time . "' WHERE id IN ({$id_str})"))
                return false;

            $return = $return + $db->affected_rows();
        }
    }
    return $return;
}

function refresh_jobs($id) {
    global $db;
    $return = 0;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if ($db->query("update  " . table('jobs') . " SET refreshtime='{$time}'  WHERE id IN ({$sqlin})")) {
            $db->query("update  " . table('jiaoshi_article_jobs_index') . " SET refreshtime='{$time}'  WHERE p_id IN(" . $sqlin . ") and type = 'jobs' ");
        } else {
            return false;
        }
        $return = $return + $db->affected_rows();
        if (!$db->query("update  " . table('jobs_tmp') . " SET refreshtime='{$time}'  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        if (!$db->query("update  " . table('jobs_search_hot') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_search_key') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_search_rtime') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_search_scale') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_search_stickrtime') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_search_wage') . "  SET refreshtime='{$time}' WHERE id IN ({$sqlin})"))
            return false;
    }
    return $return;
}

function delay_jobs($id, $days) {
    global $db;
    $days = intval($days);
    $return = 0;
    if (empty($days))
        return false;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id,deadline FROM " . table('jobs') . " WHERE id IN ({$sqlin}) UNION ALL SELECT id,deadline FROM " . table('jobs_tmp') . " WHERE id IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            if ($row['deadline'] > $time) {
                $deadline = strtotime("+{$days} day", $row['deadline']);
            } else {
                $deadline = strtotime("+{$days} day");
            }
            if (!$db->query("update  " . table('jobs') . " SET deadline='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1")) {
                return false;
            }
            $return = $return + $db->affected_rows();
            if (!$db->query("update  " . table('jobs_tmp') . " SET deadline='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1")) {
                return false;
            }
            $return = $return + $db->affected_rows();
        }
    }
    return $return;
}

function delay_meal($id, $days) {
    global $db;
    $days = intval($days);
    $return = 0;
    if (empty($days))
        return false;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id,uid,endtime FROM " . table('members_setmeal') . " WHERE uid IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            if ($row['endtime'] == "0") {
                continue;
            } else {
                if ($row['endtime'] > $time) {
                    $deadline = strtotime("{$days} day", $row['endtime']);
                } else {
                    $deadline = strtotime("{$days} day");
                }
                if (!$db->query("update  " . table('members_setmeal') . " SET endtime='{$deadline}'  WHERE id='{$row['id']}'  LIMIT 1"))
                    return false;
                $return = $return + $db->affected_rows();
                if (!$db->query("update  " . table('jobs') . " SET setmeal_deadline='{$deadline}'  WHERE uid='{$row['uid']}'  LIMIT 1"))
                    return false;
                if (!$db->query("update  " . table('jobs_tmp') . " SET setmeal_deadline='{$deadline}'  WHERE uid='{$row['uid']}'  LIMIT 1"))
                    return false;
            }
        }
    }
    return $return;
}

//******************************��ҵ����**********************************
//��ȡ��ҵ�б�
function get_company($offset, $perpage, $get_sql = '', $mode = 1) {
    global $db;
    //$colum = $mode == 1 ? 'p.points' : 'p.setmeal_name';
    $colum = 'p.points,s.setmeal_name'; //�ײ������ͬʱ��ѯ
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT c.*,m.username,m.mobile,m.email as memail,{$colum} FROM " . table('company_profile') . " AS c " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['id']), false);
        $get_resume_nolook = $db->getone("select count(*) from " . table('personal_jobs_apply') . " where personal_look=1 and company_id=" . $row['id']);
        $get_resume_all = $db->getone("select count(*) from " . table('personal_jobs_apply') . " where company_id=" . $row['id']);
        $row['get_resume'] = "( " . $get_resume_nolook['count(*)'] . " / " . $get_resume_all['count(*)'] . " )";
        $row_arr[] = $row;
    }
    return $row_arr;
}

//��ȡ������ҵ����
function get_company_one_id($id) {
    global $db;
    $id = intval($id);
    $sql = "select * from " . table('company_profile') . " where id='{$id}'";
    $val = $db->getone($sql);
    $val['user'] = get_user($val['uid']);
    return $val;
}

function get_company_one_uid($uid) {
    global $db;
    $uid = intval($uid);
    $sql = "select * from " . table('company_profile') . " where uid={$uid}";
    $val = $db->getone($sql);
    return $val;
}

function get_company_uid($uid) {
    global $db;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $sql = "select * from " . table('company_profile') . "  WHERE uid IN ({$sqlin})";
        $val = $db->getall($sql);
        return $val;
    }
}

//������ҵ��֤״̬
function edit_company_audit($company_id, $audit, $reason, $pms_notice) {
    global $db, $_CFG;
    $audit = intval($audit);
    $pms_notice = intval($pms_notice);
    $reason = trim($reason);
    if (!is_array($company_id))
        $company_id = array($company_id);
    $sqlin = implode(",", $company_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('company_profile') . " SET audit='{$audit}'  WHERE id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs') . " SET company_audit='{$audit}'  WHERE company_id IN ({$sqlin})"))
            return false;
        if (!$db->query("update  " . table('jobs_tmp') . " SET company_audit='{$audit}'  WHERE company_id IN ({$sqlin})"))
            return false;

        //����վ����
        if ($pms_notice == '1') {
            $reasonpm = $reason == '' ? '��' : $reason;
            if ($audit == '1') {
                $note = '�ɹ�ͨ����վ����Ա���!';
            } elseif ($audit == '2') {
                $note = '���������!';
            } else {
                $note = 'δͨ����վ����Ա��ˣ�';
            }
            $result = $db->query("SELECT companyname,uid FROM " . table('company_profile') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if (!empty($user_info)) {
                    $setsqlarr['message'] = "���Ĺ�˾��{$list['companyname']}," . $note . '����˵����' . $reasonpm;
                    $setsqlarr['msgtype'] = 1;
                    $setsqlarr['msgtouid'] = $user_info['uid'];
                    $setsqlarr['msgtoname'] = $user_info['username'];
                    $setsqlarr['dateline'] = time();
                    $setsqlarr['replytime'] = time();
                    $setsqlarr['new'] = 1;
                    inserttable(table('pms'), $setsqlarr);
                }
            }
        }
        //���δͨ������ԭ��
        if ($audit == '3') {
            $reasona = $reason == '' ? 'ԭ����' : 'ԭ��' . $reason;
            foreach ($uid as $list) {
                $auditsqlarr['company_id'] = $list;
                $auditsqlarr['reason'] = $reasona;
                $auditsqlarr['addtime'] = time();
                inserttable(table('audit_reason'), $auditsqlarr);
            }
        }

        if ($audit == '1') {
            //3.4�����޸�ע��,ֻ�л���ģʽ��������
            //3.5�����޸�ע�⣬���ֺͻ��ģʽ����������
            if ($_CFG['operation_mode'] == '1' || ($_CFG['operation_mode'] == '3' && $_CFG['setmeal_to_points'] == '1')) {
                $points_rule = get_cache('points_rule');
                if ($points_rule['company_auth']['value'] > 0) {//�����������֤���ͻ���
                    gift_points($sqlin, 'companyauth', $points_rule['company_auth']['type'], $points_rule['company_auth']['value']);
                }
            }
        }
        $mailconfig = get_cache('mailconfig');
        $sms = get_cache('sms_config');
        if ($audit == "1" && $mailconfig['set_licenseallow'] == "1") {//��֤ͨ��
            $result = $db->query("SELECT * FROM " . table('company_profile') . " WHERE id IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1" && !empty($user_info)) {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid={$list['uid']}&key=" . asyn_userkey($list['uid']) . "&act=set_licenseallow");
                }
            }
        }
        if ($audit == "3" && $mailconfig['set_licensenotallow'] == "1") {//��֤δͨ��
            $result = $db->query("SELECT * FROM " . table('company_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['email_audit'] == "1" && !empty($user_info)) {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid={$list['uid']}&key=" . asyn_userkey($list['uid']) . "&act=set_licensenotallow");
                }
            }
        }
        //sms		
        if ($audit == "1" && $sms['open'] == "1" && $sms['set_licenseallow'] == "1") {
            $result = $db->query("SELECT * FROM " . table('company_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1" && !empty($user_info)) {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid={$list['uid']}&key=" . asyn_userkey($list['uid']) . "&act=set_licenseallow");
                }
            }
        }
        //sms
        if ($audit == "3" && $sms['open'] == "1" && $sms['set_licensenotallow'] == "1") {//��֤δͨ��
            $result = $db->query("SELECT * FROM " . table('company_profile') . " WHERE uid IN ({$sqlin})");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                if ($user_info['mobile_audit'] == "1" && !empty($user_info)) {
                    dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid={$list['uid']}&key=" . asyn_userkey($list['uid']) . "&act=set_licensenotallow");
                }
            }
        }
        //sms
        distribution_jobs_uid("", $company_id);
        return true;
    }
    return false;
}

//������ҵִ����֤״̬
function edit_company_license($company_id, $license, $pms_notice) {
    global $db, $_CFG;
    $license = intval($license);
    $pms_notice = intval($pms_notice);
    if (!is_array($company_id)) {
        $company_id = array($company_id);
    }
    if (!empty($company_id)) {
        foreach ($company_id as $u) {
            $company_info = get_company_one_id($u);
            if (!empty($company_info['license'])) {
                if ($license == 1 && substr($company_info['license'], 0, 2) != "a_") {
                    $up['license'] = "a_" . $company_info['license'];
                    $where = " id = " . $u;
                    updatetable(table('company_profile'), $up, $where);
                } elseif ($license == 0 && substr($company_info['license'], 0, 2) == "a_") {
                    $up['license'] = substr($company_info['license'], 2);
                    $where = " id = " . $u;
                    updatetable(table('company_profile'), $up, $where);
                } elseif ($license == 1 && substr($company_info['license'], 0, 2) == "a_") {
                    return TRUE;
                } elseif ($license == 0 && substr($company_info['license'], 0, 2) != "a_") {
                    return TRUE;
                }
                if ($pms_notice == 1) {
                    $reasonpm = '��';
                    if ($license == 1) {
                        $note = '����Ӫҵִ����ͨ����վ����Ա��ˣ�';
                    } else {
                        $note = '�ܱ�Ǹ������Ӫҵִ��δ��ͨ����վ����Ա��ˡ�';
                    }
                    $sql = "SELECT companyname,uid FROM " . table('company_profile') . " WHERE id = " . $u . " limit 1";
                    $result = $db->getone($sql);
                    if (!empty($result)) {
                        $user_info = get_user($result['uid']);
                        $setsqlarr['message'] = "���Ĺ�˾��{$result['companyname']}," . $note . '����˵����' . $reasonpm;
                        $setsqlarr['msgtype'] = 1;
                        $setsqlarr['msgtouid'] = $user_info['uid'];
                        $setsqlarr['msgtoname'] = $user_info['username'];
                        $setsqlarr['dateline'] = time();
                        $setsqlarr['replytime'] = time();
                        $setsqlarr['new'] = 1;
                        //var_dump($setsqlarr);exit;
                        inserttable(table('pms'), $setsqlarr);
                    }
                }
                //3.4�����޸�ע��,ֻ�л���ģʽ��������
                //3.5�����޸�ע�⣬���ֺͻ��ģʽ����������
                if ($_CFG['operation_mode'] == '1' || ($_CFG['operation_mode'] == '3' && $_CFG['setmeal_to_points'] == '1')) {
                    $points_rule = get_cache('points_rule');
                    if ($points_rule['company_license']['value'] > 0) {//�����������֤���ͻ���
                        if ($license == 1) {
                            gift_points($u, 'companylicense', $points_rule['company_license']['type'], $points_rule['company_license']['value']);
                        } elseif ($license == 0) {
                            gift_points($u, 'companylicense', 2, $points_rule['company_license']['value']);
                        }
                    }
                }
            }
        }
        distribution_jobs_uid("", $company_id);
        return TRUE;
    }
    return FALSE;
}

//ɾ����ҵ���ϣ�uid=array
function del_company($id) {
    global $db, $certificate_dir;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT certificate_img FROM " . table('company_profile') . " WHERE id IN ({$sqlin})");
        while ($row = $db->fetch_array($result)) {
            @unlink($certificate_dir . $row['certificate_img']);
        }
        if (!$db->query("Delete from " . table('company_profile') . " WHERE id IN ({$sqlin})"))
            return false;
        return true;
    }
    return false;
}

function del_company_alljobs($company_id) {
    global $db;
    if (!is_array($company_id))
        $company_id = array($company_id);
    $sqlin = implode(",", $company_id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        $result = $db->query("SELECT id FROM " . table('jobs') . " WHERE company_id IN ({$sqlin}) UNION ALL SELECT id FROM " . table('jobs_tmp') . " WHERE company_id IN ({$sqlin}) ");
        while ($row = $db->fetch_array($result)) {
            $db->query("Delete from " . table('jobs_contact') . " WHERE pid IN ({$row['id']})");
            $db->query("Delete from  " . table('jiaoshi_article_jobs_index') . " where p_id IN (" . $row['id'] . ") and type = 'jobs'");
            $db->query("Delete from " . table('promotion') . " WHERE cp_jobid IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_hot') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_key') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_rtime') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_scale') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_stickrtime') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_wage') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_search_tag') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('view_jobs') . " WHERE jobsid IN ({$row['id']})");
            $db->query("Delete from " . table('jobs') . " WHERE id IN ({$row['id']})");
            $db->query("Delete from " . table('jobs_tmp') . " WHERE id IN ({$row['id']})");
        }
        return true;
    }
    return false;
}


//******************************��������**********************************
//�����б�
function get_order_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT o.*,m.username,m.email,c.companyname FROM " . table('order') . " as o " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['payment_name'] = get_payment_info($row['payment_name'], true);
        $row_arr[] = $row;
    }
    return $row_arr;
}

//��ȡ����
function get_order_one($id = 0) {
    global $db;
    $sql = "select * from " . table('order') . " where id=" . intval($id) . " LIMIT 1";
    $val = $db->getone($sql);
    $val['payment_name'] = get_payment_info($val['payment_name'], true);
    $val['payment_username'] = get_user($val['uid']);
    return $val;
}


//ȡ������
function del_order($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("Delete from " . table('order') . " WHERE id IN (" . $sqlin . ")  AND is_paid=1 "))
            return false;
        return true;
    }
    return false;
}

//��ȡ��ֵ֧����ʽ����
function get_payment_info($typename, $name = false) {
    global $db;
    $sql = "select * from " . table('payment') . " where typename ='" . $typename . "'";
    $val = $db->getone($sql);
    if ($name) {
        return $val['byname'];
    } else {
        return $val;
    }
}

function get_meal_members_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT a.*,b.*,c.companyname,c.telephone,c.contact FROM " . table('members_setmeal') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

//��ȡ��Ա���ײͱ����¼
function get_meal_members_log($offset, $perpage, $get_sql = '', $mode = '1') {
    global $db;
    $colum = $mode == 1 ? 'b.points' : 'b.setmeal_name';
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT a.*,{$colum},c.companyname FROM " . table('members_charge_log') . " as a " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['log_value_'] = $row['log_value'];
        $row['log_value'] = cut_str($row['log_value'], 20, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

//ɾ����ҵ��Ա�ײͱ����¼
function del_meal_log($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('members_charge_log') . " WHERE log_id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

function get_member_list($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT m.*,c.companyname,c.id,c.addtime FROM " . table('members') . " as m " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function delete_company_user($uid) {
    global $db;
    if (!is_array($uid))
        $uid = array($uid);
    $sqlin = implode(",", $uid);
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (defined('UC_API')) {
            include_once(QISHI_ROOT_PATH . 'uc_client/client.php');
            foreach ($uid as $tuid) {
                $userinfo = get_user($tuid);
                $uc_user = uc_get_user($userinfo['username']);
                $uc_uid_arr[] = $uc_user[0];
            }
            uc_user_delete($uc_uid_arr);
        }
        if (!$db->query("Delete from " . table('members') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_info') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_log') . " WHERE log_uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_points') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('order') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        if (!$db->query("Delete from " . table('members_setmeal') . " WHERE uid IN (" . $sqlin . ")"))
            return false;
        return true;
    }
    return false;
}

//******************************����**********************************
//��ȡ��Ա��Ϣ�������û����������Ϣ
function get_user_points($uid) {
    global $db;
    $sql = "select * from " . table('members_points') . " where uid = " . intval($uid) . "  LIMIT 1 ";
    $points = $db->getone($sql);
    return $points['points'];
}

//��ȡ���ֹ���
function get_points_rule() {
    global $db;
    $sql = "select * from " . table('members_points_rule') . " WHERE utype='1' order BY id asc";
    $list = $db->getall($sql);
    return $list;
}


//-------------------------------------------------------
//�����ͨ
function order_paid($v_oid) {
    global $db, $timestamp, $_CFG;
    $order = $db->getone("select * from " . table('order') . " WHERE oid ='{$v_oid}' AND is_paid= '1' LIMIT 1 ");
    if ($order) {
        $user = get_user($order['uid']);
        $sql = "UPDATE " . table('order') . " SET is_paid= '2',payment_time='{$timestamp}' WHERE oid='{$v_oid}' LIMIT 1 ";
        if (!$db->query($sql))
            return false;
        if ($order['amount'] == '0.00') {
            $ismoney = 1;
        } else {
            $ismoney = 2;
        }
        if ($order['days'] > 0) {
            set_members_limit_points($order['uid'], 1, $order['points'], $order['days']);
            $user_points = get_user_points($order['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $notes = "�����ˣ�{$_SESSION['admin_name']},˵����ȷ���տ�տ��{$order['amount']} ��" . date('Y-m-d H:i', time()) . "ͨ����" . get_payment_info($order['payment_name'], true) . " �ɹ���ֵ " . $order['amount'] . "Ԫ��(+{$order['points']}��ʱ����)��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points}),����:{$v_oid}";
            write_memberslog($order['uid'], 1, 9001, $user['username'], $notes);
            //��Ա�ײͱ����¼����Ա����ɹ���2��ʾ����Ա�Լ�����
            write_setmeallog($order['uid'], $user['username'], $notes, 2, $order['amount'], $ismoney, 1);
        } elseif ($order['points'] > 0) {
            report_deal($order['uid'], 1, $order['points']);
            $user_points = get_user_points($order['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $notes = "�����ˣ�{$_SESSION['admin_name']},˵����ȷ���տ�տ��{$order['amount']} ��" . date('Y-m-d H:i', time()) . "ͨ����" . get_payment_info($order['payment_name'], true) . " �ɹ���ֵ " . $order['amount'] . "Ԫ��(+{$order['points']})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points}),����:{$v_oid}";
            write_memberslog($order['uid'], 1, 9001, $user['username'], $notes);
            //��Ա�ײͱ����¼������Ա��̨���û�Ա��������ɹ���4��ʾ������Ա��̨��ͨ
            write_setmeallog($order['uid'], $user['username'], $notes, 4, $order['amount'], $ismoney, 1, 1);
        }
        if ($order['setmeal'] > 0) {
            set_members_setmeal($order['uid'], $order['setmeal']);
            $setmeal = get_setmeal_one($order['setmeal']);
            $notes = "�����ˣ�{$_SESSION['admin_name']},˵����ȷ���տ�տ��{$order['amount']} ��" . date('Y-m-d H:i', time()) . "ͨ����" . get_payment_info($order['payment_name'], true) . " �ɹ���ֵ " . $order['amount'] . "Ԫ����ͨ{$setmeal['setmeal_name']}";
            write_memberslog($order['uid'], 1, 9002, $user['username'], $notes);
            //��Ա�ײͱ����¼������Ա��̨���û�Ա��������ɹ���4��ʾ������Ա��̨��ͨ
            write_setmeallog($order['uid'], $user['username'], $notes, 4, $order['amount'], $ismoney, 2, 1);
        }
        //�����ʼ�
        $mailconfig = get_cache('mailconfig');
        if ($mailconfig['set_payment'] == "1" && $user['email_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_mail.php?uid=" . $order['uid'] . "&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //�����ʼ����
        //sms
        $sms = get_cache('sms_config');
        if ($sms['open'] == "1" && $sms['set_payment'] == "1" && $user['mobile_audit'] == "1") {
            dfopen($_CFG['site_domain'] . $_CFG['site_dir'] . "plus/asyn_sms.php?uid=" . $order['uid'] . "&key=" . asyn_userkey($order['uid']) . "&act=set_payment");
        }
        //sms
        return true;
    }
    return true;
}

function get_user_limit_points($uid) {
    global $db;
    $uid = intval($uid);
    $points = $db->getone("select * from " . table('members_points_limit') . " where uid ='{$uid}' LIMIT 1");
    if (!empty($points['id'])) {
        $up_limit['points'] = $points['endtime'] > time() ? $points['points'] : 0;
        updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        $points['points'] = $up_limit['points'];
    } else {
        $points = array();
    }
    return $points;
}

function set_members_limit_points($uid, $i_type = 1, $points = 0, $days = 0) {
    global $db, $timestamp;
    $p = 0;
    $points = intval($points);
    $uid = intval($uid);
    $limit_info = get_user_limit_points($uid);
    $up_limit = array();
    if (!empty($limit_info['id'])) {
        if ($limit_info['endtime'] > 0) {
            if ($i_type == 1) {
                $up_limit['points'] = $limit_info['points'] + $points;
            } elseif ($i_type == 2) {
                if ($limit_info['endtime'] > time() && $limit_info['endtime'] > 0) {
                    $up_limit['points'] = $limit_info['points'] - $points;
                    $p = $up_limit['points'] < 0 ? abs($up_limit['points']) : 0;
                    $up_limit['points'] = $up_limit['points'] < 0 ? 0 : $up_limit['points'];
                }
            }
            $up_limit['endtime'] = $limit_info['endtime'] > time() ? $limit_info['endtime'] + ($days * 86400) : time() + ($days * 86400);
            $up_limit['points'] = $up_limit['endtime'] > time() ? $up_limit['points'] : 0;
            updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        } else {
            $up_limit['points'] = 0;
            updatetable(table('members_points_limit'), $up_limit, " uid='{$uid}' LIMIT 1 ");
        }
    } elseif ($days > 0) {
        $up_limit['uid'] = $uid;
        $up_limit['points'] = $points;
        $up_limit['addtime'] = time();
        $up_limit['endtime'] = time() + ($days * 86400);
        inserttable(table('members_points_limit'), $up_limit);
    }
    return $p;
}

function report_deal($uid, $i_type = 1, $points = 0, $limit = 1) {
    global $db, $timestamp;
    $points = intval($points);
    $uid = intval($uid);
    if ($i_type == 2 && $limit == 1) {
        $points = set_members_limit_points($uid, 2, $points);
    }
    $points_val = get_user_points($uid);
    if ($i_type == 1) {
        $points_val = $points_val + $points;
    }
    if ($i_type == 2) {
        $points_val = $points_val - $points;
        $points_val = $points_val < 0 ? 0 : $points_val;
    }
    $sql = "UPDATE " . table('members_points') . " SET points= '{$points_val}' WHERE uid='{$uid}' LIMIT 1";
    if (!$db->query($sql)) {
        return false;
    }
    return true;
}

function gift_points($uid, $gift, $ptype, $points) {
    global $db;
    $operator = $ptype == "1" ? "+" : "-";
    $time = time();
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $uid)) {
        $uid = explode(',', $uid);
    }
    if (!is_array($uid))
        $uid = array($uid);
    if (!empty($uid) && is_array($uid)) {
        foreach ($uid as $vuid) {
            $vuid = intval($vuid);
            report_deal($vuid, $ptype, $points);
            $user = get_user($vuid);
            $mypoints = get_user_points($vuid);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            if ($gift == 'companyauth') {
                if ($operator == "+") {
                    write_memberslog($vuid, 1, 9001, $user['username'], " ����֤��ҵ����({$operator}{$points})��(ʣ�����:{$mypoints}��ʣ����ʱ����:{$user_limit_points})", 1, 1013, "��֤��ҵ����", "{$operator}{$points}", "{$mypoints}");
                } else {
                    write_memberslog($vuid, 1, 9001, $user['username'], " ��ȡ����ҵ��֤�ʸ�({$operator}{$points})��(ʣ�����:{$mypoints}��ʣ����ʱ����:{$user_limit_points})", 1, 1013, "ȡ����ҵ��֤�ʸ�", "{$operator}{$points}", "{$mypoints}");
                }
            } elseif ($gift == 'companylicense') {
                if ($operator == "+") {
                    write_memberslog($vuid, 1, 9001, $user['username'], " ����֤��ҵӪҵִ��({$operator}{$points})��(ʣ�����:{$mypoints}��ʣ����ʱ����:{$user_limit_points})", 1, 1013, "��֤Ӫҵִ��", "{$operator}{$points}", "{$mypoints}");
                } else {
                    write_memberslog($vuid, 1, 9001, $user['username'], " ��ȡ��ִ����֤�ʸ�({$operator}{$points})��(ʣ�����:{$mypoints}��ʣ����ʱ����:{$user_limit_points})", 1, 1013, "ȡ��ִ����֤�ʸ�", "{$operator}{$points}", "{$mypoints}");
                }
            }
        }
    }
}

function get_setmeal($apply = true) {
    global $db;
    if ($apply == true) {
        $where = "";
    } else {
        $where = " WHERE display=1 ";
    }
    $sql = "select * from " . table('setmeal') . $where . "  order BY display desc,show_order desc,id asc";
    return $db->getall($sql);
}

function get_setmeal_one($id) {
    global $db;
    $sql = "select * from " . table('setmeal') . "  WHERE id=" . intval($id) . "";
    return $db->getone($sql);
}

function get_points_setmeal_one($id) {
    global $db;
    $sql = "select * from " . table('jiaoshi_setmeal') . "  WHERE id=" . intval($id) . "";
    return $db->getone($sql);
}

function get_points_setmeal() {
    global $db;
    $sql = "select * from " . table('jiaoshi_setmeal') . " order by setmeal_order desc";
    $setmeal_list = $db->getall($sql);
    $setmeal_list2 = array();
    foreach ($setmeal_list as $setmeal) {
        $sql = "select * from " . table('jiaoshi_setmeal_activity') . " where setmeal_id=" . $setmeal['id'] . " order by activity_order desc";
        $activity_list = $db->getall($sql);
        $setmeal['activity_list'] = $activity_list;
        $setmeal_list2[] = $setmeal;
    }
    return $setmeal_list2;
}

function del_points_setmeal_activity_one($id) {
    global $db;
    if (!$db->query("Delete from " . table('jiaoshi_setmeal_activity') . " WHERE id=" . intval($id) . " "))
        return false;
    return true;
}

function del_points_setmeal_one($id) {
    global $db;
    if (!$db->query("Delete from " . table('jiaoshi_setmeal') . " WHERE id=" . intval($id) . " "))
        return false;
    return true;
}

function get_user_setmeal($uid) {
    global $db;
    $sql = "select * from " . table('members_setmeal') . "  WHERE uid=" . intval($uid) . " AND  effective=1 LIMIT 1";
    return $db->getone($sql);
}

function del_setmeal_one($id) {
    global $db;
    if (!$db->query("Delete from " . table('setmeal') . " WHERE id=" . intval($id) . " "))
        return false;
    return true;
}

function set_members_setmeal($uid, $setmealid) {
    global $db, $timestamp;
    $setmeal = $db->getone("select * from " . table('setmeal') . " WHERE id = " . intval($setmealid) . " AND display=1 LIMIT 1");
    if (empty($setmeal))
        return false;
    $setsqlarr['effective'] = 1;
    $setsqlarr['setmeal_id'] = $setmeal['id'];
    $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
    $setsqlarr['days'] = $setmeal['days'];
    $setsqlarr['starttime'] = $timestamp;
    if ($setmeal['days'] > 0) {
        $setsqlarr['endtime'] = strtotime("" . $setmeal['days'] . " days");
    } else {
        $setsqlarr['endtime'] = "0";
    }
    $setsqlarr['expense'] = $setmeal['expense'];
    $setsqlarr['jobs_ordinary'] = $setmeal['jobs_ordinary'];
    $setsqlarr['download_resume_ordinary'] = $setmeal['download_resume_ordinary'];
    $setsqlarr['download_resume_senior'] = $setmeal['download_resume_senior'];
    $setsqlarr['interview_ordinary'] = $setmeal['interview_ordinary'];
    $setsqlarr['interview_senior'] = $setmeal['interview_senior'];
    $setsqlarr['talent_pool'] = $setmeal['talent_pool'];
    $setsqlarr['recommend_num'] = $setmeal['recommend_num'];
    $setsqlarr['recommend_days'] = $setmeal['recommend_days'];
    $setsqlarr['stick_num'] = $setmeal['stick_num'];
    $setsqlarr['stick_days'] = $setmeal['stick_days'];
    $setsqlarr['emergency_num'] = $setmeal['emergency_num'];
    $setsqlarr['emergency_days'] = $setmeal['emergency_days'];
    $setsqlarr['highlight_num'] = $setmeal['highlight_num'];
    $setsqlarr['highlight_days'] = $setmeal['highlight_days'];
    $setsqlarr['change_templates'] = $setmeal['change_templates'];
    $setsqlarr['jobsfair_num'] = $setmeal['jobsfair_num'];
    $setsqlarr['map_open'] = $setmeal['map_open'];

    $setsqlarr['added'] = $setmeal['added'];
    $setsqlarr['refresh_jobs_space'] = $setmeal['refresh_jobs_space'];
    $setsqlarr['refresh_jobs_time'] = $setmeal['refresh_jobs_time'];
    if (!updatetable(table('members_setmeal'), $setsqlarr, " uid=" . $uid . ""))
        return false;
    $setmeal_jobs['setmeal_deadline'] = $setsqlarr['endtime'];
    $setmeal_jobs['setmeal_id'] = $setsqlarr['setmeal_id'];
    $setmeal_jobs['setmeal_name'] = $setsqlarr['setmeal_name'];
    if (!updatetable(table('jobs'), $setmeal_jobs, " uid=" . intval($uid) . " AND add_mode='2' "))
        return false;
    if (!updatetable(table('jobs_tmp'), $setmeal_jobs, " uid=" . intval($uid) . " AND add_mode='2' "))
        return false;
    distribution_jobs_uid($uid);
    return true;
}

//��ҵ�ƹ�
function get_promotion_cat($available = '') {
    global $db;
    if ($available <> "") {
        $wheresql = " WHERE cat_available='$available' ";
    }
    $sql = "select * from " . table('promotion_category') . " {$wheresql} order BY cat_order desc";
    return $db->getall($sql);
}

function get_promotion_cat_one($id) {
    global $db;
    $sql = "select * from " . table('promotion_category') . "  WHERE cat_id='" . intval($id) . "' LIMIT 1";
    return $db->getone($sql);
}

function get_promotion($offset, $perpage, $get_sql = '') {
    global $db, $timestamp;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT p.*,j.subsite_id,j.jobs_name,j.addtime,j.companyname,j.company_id,j.company_addtime,j.recommend,j.emergency,j.highlight,j.stick,c.cat_name FROM " . table('promotion') . " AS p " . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['jobs_name'] = cut_str($row['jobs_name'], 10, 0, "...");
        if (!empty($row['highlight'])) {
            $row['jobs_name'] = "<span style=\"color:{$row['highlight']}\">{$row['jobs_name']}</span>";
        }
        $row['jobs_url'] = url_rewrite('QS_jobsshow', array('id' => $row['cp_jobid']), true, $row['subsite_id']);
        $row['companyname'] = cut_str($row['companyname'], 15, 0, "...");
        $row['company_url'] = url_rewrite('QS_companyshow', array('id' => $row['company_id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

function del_promotion($id) {
    global $db;
    $n = 0;
    if (!is_array($id))
        $id = array($id);
    foreach ($id as $did) {
        $info = $db->getone("select p.*,m.username from " . table('promotion') . " AS p INNER JOIN  " . table('members') . " as m ON p.cp_uid=m.uid WHERE p.cp_id='" . intval($did) . "' LIMIT 1");
        write_memberslog($info['cp_uid'], 1, 3006, $info['username'], "����Աȡ���ƹ㣬ְλID:{$info['cp_jobid']}");
        cancel_promotion($info['cp_jobid'], $info['cp_promotionid']);
        $db->query("Delete from " . table('promotion') . " WHERE cp_id ='" . intval($did) . "'");
        $n+=$db->affected_rows();
    }
    return $n;
}

function get_promotion_one($id) {
    global $db;
    $sql = "select * from " . table('promotion') . "  WHERE cp_id='" . intval($id) . "' LIMIT 1";
    return $db->getone($sql);
}

function check_promotion($jobid, $promotionid) {
    global $db;
    $sql = "select * from " . table('promotion') . "  WHERE cp_jobid='" . intval($jobid) . "' AND cp_promotionid='" . intval($promotionid) . "' LIMIT 1";
    return $db->getone($sql);
}

function set_job_promotion($jobid, $promotionid, $val = '') {
    global $db;
    if ($promotionid == "1") {
        $db->query("UPDATE " . table('jobs') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET recommend=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "2") {
        $db->query("UPDATE " . table('jobs') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET emergency=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "3") {
        $db->query("UPDATE " . table('jobs') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET stick=1 WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "4") {
        $db->query("UPDATE " . table('jobs') . " SET highlight='{$val}' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_tmp') . " SET highlight='{$val}' WHERE id='{$jobid}' LIMIT 1");
        return true;
    }
}

function cancel_promotion($jobid, $promotionid) {
    global $db;
    if ($promotionid == "1") {
        $db->query("UPDATE " . table('jobs') . " SET recommend='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET recommend='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET recommend='0' WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "2") {
        $db->query("UPDATE " . table('jobs') . " SET emergency='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET emergency='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_hot') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_key') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_rtime') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_scale') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        $db->query("UPDATE " . table('jobs_search_wage') . " SET emergency='0' WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "3") {
        $db->query("UPDATE " . table('jobs') . " SET stick='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET stick='0' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_search_stickrtime') . " SET stick='0' WHERE id='{$jobid}' LIMIT 1");
        return true;
    } elseif ($promotionid == "4") {
        $db->query("UPDATE " . table('jobs') . " SET highlight='' WHERE id='{$jobid}'  LIMIT 1 ");
        $db->query("UPDATE " . table('jobs_tmp') . " SET highlight='' WHERE id='{$jobid}'  LIMIT 1 ");
        return true;
    }
}

function get_comment($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query($get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['content_'] = cut_str($row['content'], 60, 0, "...");
        $row_arr[] = $row;
    }
    return $row_arr;
}

function comment_del($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    $result = $db->query("SELECT * FROM " . table('comment') . " WHERE id IN ({$sqlin})");
    while ($row = $db->fetch_array($result)) {
        $db->query("update " . table('company_profile') . " set comment=comment-1 WHERE id='{$row['company_id']}'  LIMIT 1");
    }
    if (!$db->query("Delete from " . table('comment') . " WHERE id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

//��ȡְλ�������־
function get_jobsaudit_one($jobs_id) {
    global $db;
    $sql = "select * from " . table('audit_reason') . "  WHERE jobs_id='" . intval($jobs_id) . "' ORDER BY id DESC";
    return $db->getall($sql);
}

function get_comaudit_one($company_id) {
    global $db;
    $uid = $db->getone("select uid from " . table('company_profile') . " where id='" . intval($company_id) . "' limit 1");
    $sql = "select * from " . table('audit_reason') . "  WHERE company_id='" . intval($uid['uid']) . "' ORDER BY id DESC";
    return $db->getall($sql);
}

function reasonaudit_del($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('audit_reason') . " WHERE id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

//��ȡ��ҵͼƬ
function get_company_img($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->getall("SELECT i.*,c.companyname,c.telephone,c.email  FROM " . table('company_img') . " AS i " . $get_sql . $limit);
    return $result;
}

//ɾ����˾ͼƬ
function del_company_img($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;

    $result = $db->query("SELECT * FROM " . table('company_img') . " WHERE id IN ({$sqlin})");
    while ($list = $db->fetch_array($result)) {
        @unlink("../data/companyimg/original/" . $list['img']); //ɾ��ԭͼ
        @unlink("../data/companyimg/thumb/" . $list['img']); //ɾ������ͼ
    }
    if (!$db->query("Delete from " . table('company_img') . " WHERE id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

//��˹�˾ͼƬ
//�޸�ְλ���״̬
function edit_img_audit($id, $audit, $reason, $pms_notice = '1') {
    global $db;
    $audit = intval($audit);
    $reason = trim($reason);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('company_img') . " SET audit={$audit}  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        if ($audit == '1') {
            $note = '�ɹ�ͨ����վ����Ա���!';
        } elseif ($audit == '2') {
            $note = '���������!';
        } else {
            $note = 'δͨ����վ����Ա��ˣ�';
        }
        $reason = $reason == '' ? '��' : $reason;
        //����վ����
        if ($pms_notice == '1') {
            $result = $db->query("SELECT uid,title,img FROM " . table('company_img') . " WHERE id IN ({$sqlin}) ");
            while ($list = $db->fetch_array($result)) {
                $list['title'] = $list['title'] == '' ? $list['img'] : $list['title'];
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = "���ϴ���ͼƬ������Ϊ��{$list['title']}," . $note . " ����˵����" . $reason;
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
            }
        }
        return $return;
    }
}

function get_company_news($offset, $perpage, $sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT n.*,c.companyname,c.telephone,c.email  FROM " . table('company_news') . " AS n " . $sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row['news_url'] = url_rewrite('QS_companynewsshow', array('id' => $row['id']), false);
        $row_arr[] = $row;
    }
    return $row_arr;
}

//��ҵ�������
function edit_news_audit($id, $audit, $reason, $pms_notice = '1') {
    global $db;
    $audit = intval($audit);
    $reason = trim($reason);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $return = 0;
    if (preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin)) {
        if (!$db->query("update  " . table('company_news') . " SET audit={$audit}  WHERE id IN ({$sqlin})"))
            return false;
        $return = $return + $db->affected_rows();
        if ($audit == '1') {
            $note = '�ɹ�ͨ����վ����Ա���!';
        } elseif ($audit == '2') {
            $note = '���������!';
        } else {
            $note = 'δͨ����վ����Ա��ˣ�';
        }
        $reason = $reason == '' ? '��' : $reason;
        //����վ����
        if ($pms_notice == '1') {
            $result = $db->query("SELECT uid,title FROM " . table('company_news') . " WHERE id IN ({$sqlin}) ");
            while ($list = $db->fetch_array($result)) {
                $user_info = get_user($list['uid']);
                $setsqlarr['message'] = "����ӵ����ţ�����Ϊ��{$list['title']}," . $note . " ����˵����" . $reason;
                $setsqlarr['msgtype'] = 1;
                $setsqlarr['msgtouid'] = $user_info['uid'];
                $setsqlarr['msgtoname'] = $user_info['username'];
                $setsqlarr['dateline'] = time();
                $setsqlarr['replytime'] = time();
                $setsqlarr['new'] = 1;
                inserttable(table('pms'), $setsqlarr);
            }
        }
        return $return;
    }
}

//ɾ����˾����
function del_company_news($id) {
    global $db;
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("Delete from " . table('company_news') . " WHERE id IN ({$sqlin})"))
        return false;
    return $db->affected_rows();
}

function get_news_one($id) {
    global $db;
    $sql = "select * from " . table('company_news') . "  WHERE id='" . intval($id) . "' LIMIT 1";
    return $db->getone($sql);
}

//ְλ�������
function comment_audit($id, $audit) {
    global $db;
    $audit = intval($audit);
    if (!is_array($id))
        $id = array($id);
    $sqlin = implode(",", $id);
    $return = 0;
    if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/", $sqlin))
        return false;
    if (!$db->query("update  " . table('comment') . " SET audit={$audit}  WHERE id IN ({$sqlin})"))
        return false;
    $return = $return + $db->affected_rows();
    return $return;
}

function edit_setmeal_notes($setarr, $setmeal) {
    $diff_arr = array_diff_assoc($setarr, $setmeal);
    if ($diff_arr) {
        foreach ($diff_arr as $key => $value) {
            if ($key == 'jobs_ordinary') {
                $str.="��ְͨλ��{$setmeal['jobs_ordinary']}-{$setarr['jobs_ordinary']}";
            } elseif ($key == 'download_resume_ordinary') {
                $str.=",������ͨ�˲ż�����{$setmeal['download_resume_ordinary']}-{$setarr['download_resume_ordinary']}";
            } elseif ($key == 'download_resume_senior') {
                $str.=",���ظ߼��˲ż�����{$setmeal['download_resume_senior']}-{$setarr['download_resume_senior']}";
            } elseif ($key == 'interview_ordinary') {
                $str.=",������ͨ�˲���������{$setmeal['interview_ordinary']}-{$setarr['interview_ordinary']}";
            } elseif ($key == 'interview_senior') {
                $str.=",����߼��˲���������{$setmeal['interview_senior']}-{$setarr['interview_senior']}";
            } elseif ($key == 'jobs_hunter') {
                $str.=",�߼�ְλ��{$setmeal['jobs_hunter']}-{$setarr['jobs_hunter']}";
            } elseif ($key == 'download_resume_huntered') {
                $str.=",���ؾ����˲ż�����{$setmeal['download_resume_huntered']}-{$setarr['download_resume_huntered']}";
            } elseif ($key == 'interview_huntered') {
                $str.=",���뾭���˲���������{$setmeal['interview_huntered']}-{$setarr['interview_huntered']}";
            } elseif ($key == 'talent_pool') {
                $str.=",�˲ſ�������{$setmeal['talent_pool']}-{$setarr['talent_pool']}";
            } elseif ($key == 'recommend_num') {
                $str.=",�����Ƽ�ְλ����{$setmeal['recommend_num']}-{$setarr['recommend_num']}";
            } elseif ($key == 'recommend_days') {
                $str.=",�Ƽ�ְλ�����趨��{$setmeal['recommend_days']}-{$setarr['recommend_days']}";
            } elseif ($key == 'stick_num') {
                $str.=",�����ö�ְλ����{$setmeal['stick_num']}-{$setarr['stick_num']}";
            } elseif ($key == 'stick_days') {
                $str.=",�ö������趨��{$setmeal['stick_days']}-{$setarr['stick_days']}";
            } elseif ($key == 'emergency_num') {
                $str.=",�������ְλ����{$setmeal['emergency_num']}-{$setarr['emergency_num']}";
            } elseif ($key == 'emergency_days') {
                $str.=",����ְλ�����趨��{$setmeal['emergency_days']}-{$setarr['emergency_days']}";
            } elseif ($key == 'highlight_num') {
                $str.=",������ɫְλ����{$setmeal['highlight_num']}-{$setarr['highlight_num']}";
            } elseif ($key == 'highlight_days') {
                $str.=",��ɫְλ�����趨��{$setmeal['highlight_days']}-{$setarr['highlight_days']}";
            } elseif ($key == 'jobsfair_num') {
                $str.=",�μ���Ƹ������{$setmeal['jobsfair_num']}-{$setarr['jobsfair_num']}";
            } elseif ($key == 'change_templates') {
                $flag = $setmeal['change_templates'] == '1' ? '����' : '������';
                $flag1 = $setarr['change_templates'] == '1' ? '����' : '������';
                $str.=",�����л�ģ�壺{$flag}-{$flag1}";
            } elseif ($key == 'map_open') {
                $flag = $setmeal['map_open'] == '1' ? '����' : '������';
                $flag1 = $setarr['map_open'] == '1' ? '����' : '������';
                $str.=",���ӵ�ͼ��{$flag}-{$flag1}";
            } elseif ($key == 'endtime') {
                if ($setarr['endtime'] == '1970-01-01')
                    $setarr['endtime'] = '������';
                $str.=",�޸��ײ͵���ʱ�䣺{$setmeal['endtime']}~{$setarr['endtime']}";
            }elseif ($key == 'log_amount' && $value) {
                $str.=",��ȡ�ײͽ�{$value} Ԫ";
            }
        }
        $strend = $str ? "�����ˣ�{$_SESSION['admin_name']}��˵����" . $str : '';
        return $strend;
    } else {
        return '';
    }
}

function export_jobs($yid) {
    global $db;
    $yid_str = implode(",", $yid);
    $oederbysql = " order BY refreshtime desc ";
    $wheresql = empty($wheresql) ? " id in ({$yid_str}) " : " and id in ({$yid_str}) ";
    if (!empty($wheresql)) {
        $wheresql = " WHERE " . ltrim(ltrim($wheresql), 'AND');
    }
    $data = $db->getall("select * from " . table('jobs') . $wheresql);
    $data_tmp = $db->getall("select * from " . table('jobs_tmp') . $wheresql);

    if (!empty($data)) {
        $result = $data;
    } elseif (!empty($data_tmp)) {
        $result = $data_tmp;
    }
    if (!empty($result)) {
        foreach ($result as $key => $value) {
            $arr[$key]['num'] = $key;
            $arr[$key]['jobs_id'] = $value['id'];
            $arr[$key]['jobs_name'] = $value['jobs_name'];
            $arr[$key]['companyname'] = $value['companyname'];
            $arr[$key]['nature_cn'] = $value['nature_cn'];
            $arr[$key]['sex_cn'] = $value['sex_cn'];
            $arr[$key]['amount'] = $value['amount'];
            $arr[$key]['category_cn'] = $value['category_cn'];
            $arr[$key]['trade_cn'] = $value['trade_cn'];
            $arr[$key]['scale_cn'] = $value['scale_cn'];
            $arr[$key]['district_cn'] = $value['district_cn'];
            $arr[$key]['street_cn'] = $value['street_cn'];
            $arr[$key]['education_cn'] = $value['education_cn'];
            $arr[$key]['experience_cn'] = $value['experience_cn'];
            $arr[$key]['wage_cn'] = $value['wage_cn'];
            $arr[$key]['contents'] = str_replace("\n", "", str_replace("\r", "", $value['contents']));
            $arr[$key]['addtime'] = date("Y-m-d", $value['addtime']);
            $arr[$key]['deadline'] = date("Y-m-d", $value['deadline']);
            $arr[$key]['refreshtime'] = date("Y-m-d", $value['refreshtime']);
            $contact = $db->getone("select * from " . table('jobs_contact') . " where pid='" . $value['id'] . "' LIMIT 1");
            $arr[$key]['contact'] = $contact['contact'];
            $arr[$key]['qq'] = $contact['qq'];
            $arr[$key]['telephone'] = $contact['telephone'];
            $arr[$key]['address'] = $contact['address'];
            $arr[$key]['email'] = $contact['email'];
        }
        $top_str = "���\tְλID\tְλ����\t��˾����\tְλ����\t�Ա�\t��Ƹ����\tְλ���\t��ҵ���\t��˾��ģ\t��������\t���ڽֵ�\t����д��¥\tѧ��Ҫ��\t��������\tн��\tְλ����\t���ʱ��\t����ʱ��\tˢ��ʱ��\t��ϵ��\t��ϵQQ\t��ϵ�绰\t��ϵ��ַ\t��ϵ����\t\n";
        create_excel($top_str, $arr);
        return true;
    } else {
        return false;
    }
}

function export_company($yid) {
    global $db;
    $yid_str = implode(",", $yid);
    $oederbysql = " order BY refreshtime desc ";
    $wheresql = empty($wheresql) ? " uid in ({$yid_str}) " : " and uid in ({$yid_str}) ";

    if (!empty($wheresql)) {
        $wheresql = " WHERE " . ltrim(ltrim($wheresql), 'AND');
    }
    $data = $db->getall("select * from " . table('company_profile') . $wheresql);
    $result = $data;
    if (!empty($result)) {
        foreach ($result as $key => $value) {
            $arr[$key]['num'] = $key;
            $user = $db->getone("select username from " . table('members') . " where uid=" . $value['uid']);
            $arr[$key]['username'] = $user['username'];
            $arr[$key]['company_id'] = $value['id'];
            $arr[$key]['companyname'] = $value['companyname'];
            $arr[$key]['nature_cn'] = $value['nature_cn'];
            $arr[$key]['trade_cn'] = $value['trade_cn'];
            $arr[$key]['scale_cn'] = $value['scale_cn'];
            $arr[$key]['district_cn'] = $value['district_cn'];
            $arr[$key]['street_cn'] = $value['street_cn'];
            $arr[$key]['registered'] = $value['registered'] . $value['currency'];
            $arr[$key]['contact'] = $value['contact'];
            $arr[$key]['website'] = $value['website'];
            $arr[$key]['telephone'] = $value['telephone'];
            $arr[$key]['address'] = $value['address'];
            $arr[$key]['email'] = $value['email'];
            $arr[$key]['contents'] = str_replace("\n", "", str_replace("\r", "", $value['contents']));
        }
        $top_str = "���\t������Ա\t��˾ID\t��˾����\t��˾����\t��ҵ���\t��˾��ģ\t���ڵ���\t���ڽֵ�\t����д��¥\tע���ʽ�\t��ϵ��\t��ַ\t��ϵ�绰\t��ϵ��ַ\t��ϵ����\t��˾���\t\n";
        create_excel($top_str, $arr);
        return true;
    } else {
        return false;
    }
}

function get_consultant($offset, $perpage, $get_sql = '') {
    global $db;
    $row_arr = array();
    $limit = " LIMIT " . $offset . ',' . $perpage;
    $result = $db->query("SELECT * FROM " . table('consultant') . $get_sql . $limit);
    while ($row = $db->fetch_array($result)) {
        $row_arr[] = $row;
    }
    return $row_arr;
}

function get_consultant_one($id) {
    global $db;
    $result = $db->getone("SELECT * FROM " . table('consultant') . " where id=" . $id);
    return $result;
}

function del_consultant($id) {
    global $db;
    $db->query("delete from " . table('consultant') . " where id=" . $id);
    return true;
}

function set_user_status($status, $uid) {
    global $db;
    $status = intval($status);
    $uid = intval($uid);
    if (!$db->query("UPDATE " . table('members') . " SET status= {$status} WHERE uid={$uid} LIMIT 1"))
        return false;
    if (!$db->query("UPDATE " . table('company_profile') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    if (!$db->query("UPDATE " . table('jobs') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    if (!$db->query("UPDATE " . table('jobs_tmp') . " SET user_status= {$status} WHERE uid={$uid} "))
        return false;
    distribution_jobs_uid($uid);
    return true;
}

?>