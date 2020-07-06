<?php

/*
 * 74cms ��ҵ��Ա����ajax������
 * ============================================================================
 * ��Ȩ����: ��ʿ���磬����������Ȩ����
 * ��վ��ַ: http://www.74cms.com��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/company_common.php');
if ($act == "company_profile_save_succeed") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_companyprofile_save_succeed_box.htm";
    $contents = file_get_contents($tpl);
    exit($contents);
}
// ������������
elseif ($act == "interview_detail") {
    global $db;
    $did = $_GET['did'] ? intval($_GET['did']) : exit('ID��ʧ��');
    $interview_info = $db->getone("SELECT * FROM " . table('company_interview') . " WHERE did=" . $did . " LIMIT 1 ");
    if ($interview_info) {
        if (empty($interview_info['notes'])) {
            $interview_info['notes'] = '��֪ͨ���ݣ�';
        }
        $htm = '<div class="interview-dialog dialog-block">
					<div class="dialog-item clearfix">
						<div class="d-type f-left">���������</div>
						<div class="d-content f-left">' . $interview_info['resume_name'] . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">����ְλ��</div>
						<div class="data-filter d-content f-left">
							<div class="dropdown">' . $interview_info['jobs_name'] . '</div>
				            <input type="hidden" name="jobsid" value="" id="jobsid">
						</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">�������ڣ�</div>
						<div class="d-content f-left">' . date('Y-m-d', $interview_info['interview_addtime']) . '</div>
					</div>
					<div class="dialog-item clearfix">
						<div class="d-type f-left">֪ͨ���ݣ�</div>
						<div class="d-content f-left">' . $interview_info['notes'] . '</div>
					</div>
				</div>';
        exit($htm);
    } else {
        exit('�޴����ݣ�');
    }
} elseif ($act == "user_email") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_email_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_email_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$email#}', $user["email"], $contents);
    $contents = str_replace('{#$send_email_key#}', $_SESSION['send_email_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "user_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "old_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_old_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $user["hid_mobile"] = substr($user["mobile"], 0, 3) . "*****" . substr($user["mobile"], 7, 4);
    $contents = str_replace('{#$mobile#}', $user["mobile"], $contents);
    $contents = str_replace('{#$hid_mobile#}', $user["hid_mobile"], $contents);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "edit_mobile") {
    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_authenticate_edit_mobile_box.htm";
    $contents = file_get_contents($tpl);
    $_SESSION['send_mobile_key'] = mt_rand(100000, 999999);
    $contents = str_replace('{#$send_mobile_key#}', $_SESSION['send_mobile_key'], $contents);
    $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
    exit($contents);
} elseif ($act == "set_promotion") {
    $catid = intval($_GET['catid']) ? intval($_GET['catid']) : exit("catid��������");
    $jobid = intval($_GET['jobid']) ? intval($_GET['jobid']) : 0;
    $uid = intval($_SESSION['uid']) ? intval($_SESSION['uid']) : exit("uid��������");
    if ($jobid > 0) {
        $jobinfo = get_jobs_one($jobid);
    }
    $promotion = get_promotion_category_one($catid);

    if ($_CFG['operation_mode'] == '2') {
        $setmeal = get_user_setmeal($uid); //��ȡ��Ա�ײ�
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            $end = 1; //�ж��ײ��Ƿ���
            $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_promotion_end.htm";
        } else {
            $data = get_setmeal_promotion($uid, $catid); //��ȡ��Աĳ���ƹ��ʣ�����������������ƣ�������
            $operation_mode = 2;
            $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
        }
    } elseif ($_CFG['operation_mode'] == '1') {
        $points = get_user_points($uid);
        $operation_mode = 1;
        $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']); //��ȡ��Ա�ײ�
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') {
            if ($_CFG['setmeal_to_points'] != 1) {
                $end = 1; //�ж��ײ��Ƿ���
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_promotion_end.htm";
            } else {
                $operation_mode = 1;
                $points = get_user_points($uid);
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
            }
        } else {
            $data = get_setmeal_promotion($uid, $catid); //��ȡ��Աĳ���ƹ��ʣ�����������������ƣ�������
            if ($data['num'] < 1) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    $operation_mode = 1;
                    $points = get_user_points($uid);
                    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_points_promotion.htm";
                } else {
                    $operation_mode = 2;
                    $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
                }
            } else {
                $operation_mode = 2;
                $tpl = '../../templates/' . $_CFG['template_dir'] . "member_company/ajax_set_setmeal_promotion.htm";
            }
        }
    }
    $contents = file_get_contents($tpl);
    if ($end != 1) {
        if ($catid == "4") {
            $color = get_color();
            $color_list = '<tr>
				<td height="50">ѡ����ɫ��</td>
				<td>
					<div style="position:relateve;">
	             	 	<div id="val_menu" class="input_text_70_bg">��ѡ��</div>	
	             	 	<div class="menu" id="menu1">
		              		<ul style="width:88px;">';
            foreach ($color as $key => $value) {
                $color_list.='<li id="' . $value["id"] . '" title="' . $value["value"] . '" style="background-color:' . $value["value"] . '">&nbsp;</li>';
            }
            $color_list.='</ul>
		              	</div>
		            </div>
		            <input type="hidden" name="val" value="" id="val">
				</td>
				<td></td>
			</tr>';
            $contents = str_replace('{#$color_list#}', $color_list, $contents);
        } else {
            $contents = str_replace('{#$color_list#}', "", $contents);
        }
        $contents = str_replace('{#$jobid#}', $jobid, $contents);
        $contents = str_replace('{#$catid#}', $catid, $contents);
        $contents = str_replace('{#$jobs_name#}', $jobinfo['jobs_name'], $contents);
        $contents = str_replace('{#$promotion_name#}', $promotion['cat_name'], $contents);
        $contents = str_replace('{#$site_template#}', $_CFG['site_template'], $contents);
        if ($operation_mode == 1) {
            if ($promotion['cat_minday'] == "0") {
                $promotion['cat_minday'] = "������";
            }
            if ($promotion['cat_maxday'] == "0") {
                $promotion['cat_maxday'] = "������";
            }
            if ($promotion['cat_points'] == "0") {
                $promotion['cat_points'] = "���";
            }
            $contents = str_replace('{#$user_points#}', $points, $contents);
            $contents = str_replace('{#$points_perday#}', $promotion['cat_points'], $contents);
            $contents = str_replace('{#$points_quantifier#}', $_CFG['points_quantifier'], $contents);
            $contents = str_replace('{#$points_byname#}', $_CFG['points_byname'], $contents);
            $contents = str_replace('{#$cat_minday#}', $promotion['cat_minday'], $contents);
            $contents = str_replace('{#$cat_maxday#}', $promotion['cat_maxday'], $contents);
        } elseif ($operation_mode == 2) {
            $contents = str_replace('{#$days#}', $data['days'], $contents);
            $contents = str_replace('{#$setmeal_name#}', $setmeal['setmeal_name'], $contents);
            $contents = str_replace('{#$num#}', $data['num'], $contents);
            $contents = str_replace('{#$pro_name#}', $data['name'], $contents);
            $contents = str_replace('{#$cat_minday#}', $promotion['cat_minday'], $contents);
            $contents = str_replace('{#$cat_maxday#}', $promotion['cat_maxday'], $contents);
        }
    }
    exit($contents);
} elseif ($act == "promotion_add_save") {
    $form = intval($_POST['form']) ? intval($_POST['form']) : 0;

    if (intval($_POST['catid'])) {
        $catid = intval($_POST['catid']);
    } else {
        $form > 0 ? exit("<script>alert('�����������');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_POST['jobid'])) {
        $jobid = intval($_POST['jobid']);
    } else {
        $form > 0 ? exit("<script>alert('ְλ��������');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_POST['days'])) {
        $days = intval($_POST['days']);
    } else {
        $form > 0 ? exit("<script>alert('������������');history.go(-1);</script>") : exit("-1");
    }
    if (intval($_SESSION['uid'])) {
        $uid = intval($_SESSION['uid']);
    } else {
        $form > 0 ? exit("<script>alert('�û���������');history.go(-1);</script>") : exit("-1");
    }

    if ($catid == 4) {
        $val = intval($_POST['val']) ? intval($_POST['val']) : exit("-1");
        $color = get_color_one($val);
        $val_code = $color['value'];
    } else {
        $val_code = "";
    }
    $jobs = get_jobs_one($jobid, $uid);
    if ($jobs['deadline'] < time()) {
        $form > 0 ? exit("<script>alert('��ְλ�ѵ��ڣ��������ڣ�');history.go(-1);</script>") : exit("��ְλ�ѵ��ڣ��������ڣ�");
    }
    if ($jobid > 0 && $days > 0) {
        $pro_cat = get_promotion_category_one($catid);
        if ($_CFG['operation_mode'] == '3') {
            $setmeal = get_setmeal_promotion($uid, $catid); //��ȡ��Ա�ײ�
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                if ($_CFG['setmeal_to_points'] == 1) {
                    if ($pro_cat['cat_points'] > 0) {
                        $points = $pro_cat['cat_points'] * $days;
                        $user_points = get_user_points($uid);
                        $user_limit_points = get_user_limit_points($_SESSION['uid']);
                        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                        $user_points = $user_points + $user_limit_points;
                        if ($points > $user_points) {
                            $form > 0 ? exit("<script>alert('���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��');history.go(-1);</script>") : exit("���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��");
                        } else {
                            $_CFG['operation_mode'] = 1;
                        }
                    } else {
                        $_CFG['operation_mode'] = 2;
                    }
                } else {
                    $form > 0 ? exit("<script>alert('����ײ��ѵ��ڻ��ײ���ʣ��" . $pro_cat['cat_name'] . "�������뾡�쿪ͨ���ײ�');history.go(-1);</script>") : exit("����ײ��ѵ��ڻ��ײ���ʣ��{$pro_cat['cat_name']}�������뾡�쿪ͨ���ײ�");
                }
            } else {
                $_CFG['operation_mode'] = 2;
            }
        } elseif ($_CFG['operation_mode'] == '1') {
            if ($pro_cat['cat_points'] > 0) {
                $points = $pro_cat['cat_points'] * $days;
                $user_points = get_user_points($uid);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $user_points = $user_points + $user_limit_points;
                if ($points > $user_points) {
                    $form > 0 ? exit("<script>alert('���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��');history.go(-1);</script>") : exit("���" . $_CFG['points_byname'] . "�������д˴β��������ȳ�ֵ��");
                }
            }
        } elseif ($_CFG['operation_mode'] == '2') {
            $setmeal = get_setmeal_promotion($uid, $catid); //��ȡ��Ա�ײ�
            $num = $setmeal['num'];
            if (($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0') || $num <= 0) {
                $form > 0 ? exit("<script>alert('����ײ��ѵ��ڻ��ײ���ʣ��" . $pro_cat['cat_name'] . "�������뾡�쿪ͨ���ײ�');history.go(-1);</script>") : exit("����ײ��ѵ��ڻ��ײ���ʣ��{$pro_cat['cat_name']}�������뾡�쿪ͨ���ײ�");
            }
        }
        $info = get_promotion_one($jobid, $uid, $catid);
        if (!empty($info)) {
            $form > 0 ? exit("<script>alert('��ְλ�����ƹ��У���ѡ������ְλ����������');history.go(-1);</script>") : exit("��ְλ�����ƹ��У���ѡ������ְλ����������");
        }
        $setsqlarr['cp_available'] = 1;
        $setsqlarr['cp_promotionid'] = $catid;
        $setsqlarr['cp_uid'] = $uid;
        $setsqlarr['cp_jobid'] = $jobid;
        $setsqlarr['cp_days'] = $days;
        $setsqlarr['cp_starttime'] = time();
        $setsqlarr['cp_endtime'] = strtotime("{$days} day");
        $setsqlarr['cp_val'] = $val_code;
        if ($setsqlarr['cp_promotionid'] == "4" && empty($setsqlarr['cp_val'])) {
            $form > 0 ? exit("<script>alert('��ѡ����ɫ��');history.go(-1);</script>") : exit("��ѡ����ɫ��");
        }
        if (inserttable(table('promotion'), $setsqlarr)) {
            set_job_promotion($jobid, $setsqlarr['cp_promotionid'], $val_code);
            $jobs = get_jobs_one($jobid, $uid);
            if ($_CFG['operation_mode'] == '1' && $pro_cat['cat_points'] > 0) {
                report_deal($uid, 2, $points);
                $user_points = get_user_points($uid);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                write_memberslog($uid, 1, 9001, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �죬(-{$points})��(ʣ�����:{$user_points}��ʣ����ʱ����:{$user_limit_points})", 1, 1018, "{$pro_cat['cat_name']}", "-{$points}", "{$user_points}");
            } elseif ($_CFG['operation_mode'] == '2') {
                $user_pname = trim($_POST['pro_name']);
                action_user_setmeal($uid, $user_pname); //�����ײ�����Ӧ�ƹ㷽ʽ������
                $setmeal = get_user_setmeal($uid); //��ȡ��Ա�ײ�
                write_memberslog($uid, 1, 9002, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �죬�ײ���ʣ��{$pro_cat['cat_name']}������{$setmeal[$user_pname]}����", 2, 1018, "{$pro_cat['cat_name']}", "-{$days}", "{$setmeal[$user_pname]}"); //9002���ײͲ���
            }
            write_memberslog($uid, 1, 3004, $_SESSION['username'], "{$pro_cat['cat_name']}��<strong>{$jobs['jobs_name']}</strong>���ƹ� {$days} �졣");
            $form > 0 ? exit("<script>alert('���óɹ���');history.go(-1);</script>") : exit("1");
        }
    } else {
        $form > 0 ? exit("<script>alert('��������');history.go(-1);</script>") : exit("-1");
    }
}
// ְλˢ��ajax
elseif ($act == "jobs_refresh_ajax") {
    global $db, $_CFG;
    $jobsid = $_GET['jobsid'] ? intval($_GET['jobsid']) : exit('ְλID��ʧ��');
    $jobs_info = get_jobs_one($jobsid, $_SESSION['uid']);
    if ($jobs_info['deadline'] < time()) {
        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">��ְλ�ѵ��ڣ�</span></div></div>');
    }
    if ($_CFG['operation_mode'] == '1') {
        $mode = 1;
    } elseif ($_CFG['operation_mode'] == '2') {
        $mode = 2;
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        //�û�Ա�ײ͹��� (�ײ͹��ں���û�����ˢ)
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            //��̨��ͨ  ������ʱ���û�������
            if ($_CFG['setmeal_to_points'] == '1') {
                $mode = 1;
            }
            //��̨û�п�ͨ  ������ʱ���û�������
            else {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">���ķ����Ѿ����ڣ������¿�ͨ</span></div></div>');
            }
        }
        //�û�Ա�ײ�δ���� 
        else {
            $points_rule = get_cache('points_rule');
            $user_points = get_user_points($_SESSION['uid']);
            //��ȡ����ˢ�µ�ְλ��(���ײ�ģʽ��ˢ�µ�)
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            //����ʣ��ˢ��ְλ��(���ײ�ģʽ��ˢ�µ�)
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            //ˢ��ְλ�� ���� ʣ��ˢ��ְλ�� (����)
            if ($surplus_time <= 0) {
                //��̨��ͨ  ������ʱ���û�������
                if ($_CFG['setmeal_to_points'] == '1') {
                    $mode = 1;
                } else {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">���ķ����Ѿ����ڣ������¿�ͨ</span></div></div>');
                }
            } else {
                $mode = 2;
            }
        }
    }
    //����ģʽ
    if ($mode == '1') {
        //����ˢ��ʱ��
        //���һ�ε�ˢ��ʱ��
        $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 1);
        $duringtime = time() - $refrestime['max(addtime)'];
        $space = $_CFG['com_pointsmode_refresh_space'] * 60;
        $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 1);
        if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($refresh_time['count(*)'] >= $_CFG['com_pointsmode_refresh_time'])) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ������ˢ�´����Ѵ����ޣ���Ҫ���˿죿 <a href="company_jobs.php?act=jobs" class="underline">ְλ�ƹ�</a> Ч������5����</span></div></div>');
        } elseif ($duringtime <= $space) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $_CFG['com_pointsmode_refresh_space'] . '�����ڲ����ظ�ˢ��ְλ��</span></div></div>');
        } else {
            $points_rule = get_cache('points_rule');
            if ($points_rule['jobs_refresh']['value'] > 0) {
                $user_points = get_user_points($_SESSION['uid']);
                $total_point = 1 * $points_rule['jobs_refresh']['value'];
                if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ������ʣ��' . $_CFG['points_byname'] . '���㣬�޷����д˲����������� <a href="company_service.php?act=order_add" class="underline">��ֵ</a>��</span></div></div>');
                } else {
                    // ���޴���
                    if ($_CFG['com_pointsmode_refresh_time'] == 0) {
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">����ˢ����Ҫ����<span style="color:#ff9900;">' . $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    } else {
                        //ʣ�����
                        $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">���컹���û���ˢ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�����ˢ����Ҫ����<span style="color:#ff9900;">' . $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    }
                }
            } else {
                // ���޴���
                if ($_CFG['com_pointsmode_refresh_time'] == 0) {

                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    //ʣ�����
                    $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">���컹���û���ˢ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
    //�ײ�ģʽ
    elseif ($mode == '2') {

        //����ˢ��ʱ��
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if (empty($setmeal)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">����û�п�ͨ�����뿪ͨ����</span></div></div>');
        } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">���ķ����Ѿ����ڣ������¿�ͨ</span></div></div>');
        } else {
            //���һ�ε�ˢ��ʱ��
            $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 2);
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time['count(*)'] >= $setmeal['refresh_jobs_time'])) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ������ˢ�´����Ѵ����ޣ���Ҫ���˿죿 <a href="company_jobs.php?act=jobs" class="underline">ְλ�ƹ�</a> Ч������5����</span></div></div>');
            } elseif ($duringtime <= $space) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $setmeal['refresh_jobs_space'] . '�����ڲ����ظ�ˢ��ְλ��</span></div></div>');
            } else {
                //ʣ�����
                $surplus = intval($setmeal['refresh_jobs_time']) - intval($refresh_time['count(*)']);
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">�������ˢ�´���ʣ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�����ˢ��<span style="color:#ff9900;">���</span>��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
            }
        }
    }
}
// ����ְλˢ��ajax
elseif ($act == "jobs_all_refresh_ajax") {
    global $db, $_CFG;
    $length = $_GET['length'] ? intval($_GET['length']) : exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">��ѡ��ְλ��</span></div></div>');
    if ($_CFG['operation_mode'] == '1') {
        $mode = 1;
    } elseif ($_CFG['operation_mode'] == '2') {
        $mode = 2;
    } elseif ($_CFG['operation_mode'] == '3') {
        $setmeal = get_user_setmeal($_SESSION['uid']);
        //�û�Ա�ײ͹��� (�ײ͹��ں���û�����ˢ)
        if ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">���ķ����Ѿ����ڣ������¿�ͨ</span></div></div>');
        }
        //�û�Ա�ײ�δ���� 
        else {
            $points_rule = get_cache('points_rule');
            $user_points = get_user_points($_SESSION['uid']);
            //��ȡ����ˢ�µ�ְλ��(���ײ�ģʽ��ˢ�µ�)
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            //����ʣ��ˢ��ְλ��(���ײ�ģʽ��ˢ�µ�)
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            //ˢ��ְλ�� ���� ʣ��ˢ��ְλ�� (����)
            if ($setmeal['refresh_jobs_time'] != 0 && ($length > $surplus_time)) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">������ˢ��ְλ���������ײ�ʣ�������뵥��ְλˢ�²�����</span></div></div>');
            } else {
                $mode = 2;
            }
        }
    }
    //����ģʽ
    if ($mode == '1') {
        //����ˢ��ʱ��
        //���һ�ε�ˢ��ʱ��
        $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 1);
        $duringtime = time() - $refrestime['max(addtime)'];
        $space = $_CFG['com_pointsmode_refresh_space'] * 60;
        $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 1);
        $surplus_time = $_CFG['com_pointsmode_refresh_time'] - $refresh_time['count(*)'];
        if ($_CFG['com_pointsmode_refresh_time'] != 0 && ($length > $surplus_time)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ��������ˢ��ְλ�������˻���ˢ��ʣ�������뵥��ְλˢ�²�����</span></div></div>');
        } elseif ($duringtime <= $space) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $_CFG['com_pointsmode_refresh_space'] . '�����ڲ����ظ�ˢ��ְλ��</span></div></div>');
        } else {
            $points_rule = get_cache('points_rule');
            if ($points_rule['jobs_refresh']['value'] > 0) {
                $user_points = get_user_points($_SESSION['uid']);
                $total_point = $length * $points_rule['jobs_refresh']['value'];
                if ($total_point > $user_points && $points_rule['jobs_refresh']['type'] == "2") {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ������ʣ��' . $_CFG['points_byname'] . '���㣬�޷����д˲����������� <a href="company_service.php?act=order_add" class="underline">��ֵ</a>��</span></div></div>');
                } else {
                    // ���޴���
                    if ($_CFG['com_pointsmode_refresh_time'] == 0) {
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">����ˢ����Ҫ����<span style="color:#ff9900;">' . $length * $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    } else {
                        //ʣ�����
                        $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                        exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">���컹���û���ˢ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�����ˢ����Ҫ����<span style="color:#ff9900;">' . $length * $points_rule['jobs_refresh']['value'] . '</span>' . $_CFG['points_byname'] . '��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                    }
                }
            } else {
                // ���޴���
                if ($_CFG['com_pointsmode_refresh_time'] == 0) {

                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    //ʣ�����
                    $surplus = intval($_CFG['com_pointsmode_refresh_time']) - intval($refresh_time['count(*)']);
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">���컹���û���ˢ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
    //�ײ�ģʽ
    elseif ($mode == '2') {
        //����ˢ��ʱ��
        $setmeal = get_user_setmeal($_SESSION['uid']);
        if (empty($setmeal)) {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">����û�п�ͨ�����뿪ͨ����</span></div></div>');
        } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
            exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">���ķ����Ѿ����ڣ������¿�ͨ</span></div></div>');
        } else {
            //���һ�ε�ˢ��ʱ��
            $refrestime = get_last_refresh_date($_SESSION['uid'], "1001", 2);
            $duringtime = time() - $refrestime['max(addtime)'];
            $space = $setmeal['refresh_jobs_space'] * 60;
            $refresh_time = get_today_refresh_times($_SESSION['uid'], "1001", 2);
            $surplus_time = $setmeal['refresh_jobs_time'] - $refresh_time['count(*)'];
            if ($setmeal['refresh_jobs_time'] != 0 && ($length > $surplus_time)) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">��Ǹ��������ˢ��ְλ���������ײ�ˢ��ʣ�������뵥��ְλˢ�²�����</span></div></div>');
            } elseif ($duringtime <= $space) {
                exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text">' . $setmeal['refresh_jobs_space'] . '�����ڲ����ظ�ˢ��ְλ��</span></div></div>');
            } else {
                //ʣ�����
                $surplus = intval($setmeal['refresh_jobs_time']) - intval($refresh_time['count(*)']);

                if ($setmeal['refresh_jobs_time'] == 0) {
                    exit('<div class="del-dialog"><div class="tip-block">����ˢ��<span style="color:#ff9900;">���</span>��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                } else {
                    exit('<div class="del-dialog"><div class="tip-block"><span class="del-tips-text" style="line-height:30px;">�������ˢ�´���ʣ��<span style="color:#ff9900;">' . $surplus . '</span>�Σ�����ˢ��<span style="color:#ff9900;">���</span>��ȷ��Ҫˢ����</span></div></div><div class="center-btn-wrap"><input type="button" value="ȷ��" class="btn-65-30blue btn-big-font DialogSubmit" /><input type="button" value="ȡ��" class="btn-65-30grey btn-big-font DialogClose" /></div>');
                }
            }
        }
    }
}
unset($smarty);
?>