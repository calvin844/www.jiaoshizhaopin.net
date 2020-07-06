<?php
/*
 * 74cms 下载经理人简历
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'download';
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '') {
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->display('plus/ajax_login.htm');
    exit();
}
if ($_SESSION['utype'] != '1' && $_SESSION['utype'] != '3') {
    exit("必须是企业|猎头会员才可以下载经理人简历！");
}
//简历ＩＤ
$id = !empty($_GET['id']) ? intval($_GET['id']) : exit("出错了");

if ($_SESSION['utype'] == '1') {
    require_once(QISHI_ROOT_PATH . 'include/fun_company.php');
    $user = get_user_info($_SESSION['uid']);
    $downresumeurl = "<a href=\"" . get_member_url(1, true) . "company_hunterjobs.php?act=down_resume_list\">[查看已下载的经理人简历]</a>";
    if ($user['status'] == '2') {
        $str = "<a href=\"" . get_member_url(1, true) . "company_user.php?act=user_status\">[设置帐号状态]</a>";
        exit("您的账号处于暂停状态，请先设为正常后进行操作！" . $str);
    }
    if (check_com_down_manager_resumeid($id, $_SESSION['uid'])) {
        $str = "<a href=\"" . get_member_url(1, true) . "company_hunterjobs.php?act=down_resume_list\">[查看我的下载的经理人简历]</a>";
        exit("您已经下载过此简历了！" . $str);
    }
} elseif ($_SESSION['utype'] == '3') {
    require_once(QISHI_ROOT_PATH . 'include/fun_hunter.php');
    $user = get_user_info($_SESSION['uid']);
    $downresumeurl = "<a href=\"" . get_member_url(3, true) . "hunter_recruitment.php?act=down_resume_lsit\">[查看已下载的经理人简历]</a>";
    if ($user['status'] == '2') {
        $str = "<a href=\"" . get_member_url(3, true) . "hunter_user.php?act=user_status\">[设置帐号状态]</a>";
        exit("您的账号处于暂停状态，请先设为正常后进行操作！" . $str);
    }

    if (check_hun_down_manager_resumeid($id, $_SESSION['uid'])) {
        $str = "<a href=\"" . get_member_url(3, true) . "hunter_recruitment.php?act=down_resume_lsit\">[查看我的下载的经理人简历]</a>";
        exit("您已经下载过此简历了！" . $str);
    }
}

if ($_CFG['down_manager_resume_limit'] == '1') {
    if ($_SESSION['utype'] == '1') {
        $user_jobs = get_com_audit_hunterjobs($_SESSION['uid']); //审核通过的高级职位
        $strurl = "<a href=\"" . get_member_url(1, true) . "company_hunterjobs.php?act=hunterjobs\">[职位管理]</a>";
    } elseif ($_SESSION['utype'] == '3') {
        $user_jobs = get_hun_audit_jobs($_SESSION['uid']);
        $strurl = "<a href=\"" . get_member_url(3, true) . "hunter_jobs.php?act=jobs\">[职位管理]</a>";
    }
    if (empty($user_jobs)) {
        exit("你没有发布高级职位或审核未通过导致无法下载经理人简历。" . $strurl);
    }
}
//简历信息
$resumeshow = get_manager_resume_basic($id);
if ($resumeshow['display_name'] == "2") {
    $resumeshow['resume_name'] = "N" . str_pad($resumeshow['id'], 7, "0", STR_PAD_LEFT);
} elseif ($resumeshow['display_name'] == "3") {
    $resumeshow['resume_name'] = cut_str($resumeshow['fullname'], 1, 0, "**");
} else {
    $resumeshow['resume_name'] = $resumeshow['fullname'];
}
if ($act == 'download') {
    if ($_SESSION['utype'] == '1') {
        if ($_CFG['operation_mode'] == '2') {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if (empty($setmeal) || ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0")) {
                $str = "<a href=\"" . get_member_url(1, true) . "company_service.php?act=setmeal_list\">[申请服务]</a>";
                exit("您的服务已到期。您可以 {$str}");
            } elseif ($setmeal['download_resume_huntered'] <= 0) {
                $str = "<a href=\"" . get_member_url(1, true) . "company_service.php?act=setmeal_list\">[申请服务]</a>";
                exit("你下载经理人才简历数量已经超出了限制。您可以{$str}");
            }

            $tip = "提示：您还可以下载<span> {$setmeal['download_resume_huntered']}</span>份高级人才简历";
        } elseif ($_CFG['operation_mode'] == '1') {
            $points_rule = get_cache('points_rule');
            $points = $points_rule['resume_download_huntered']['value'];
            $mypoints = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $mypoints = $mypoints + $user_limit_points;
            if ($mypoints < $points) {
                $str = "<a href=\"" . get_member_url(1, true) . "company_service.php?act=order_add\">[充值{$_CFG['points_byname']}]</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                exit("你的" . $_CFG['points_byname'] . "不足，请充值后下载。" . $str);
            }
            $tip = "下载此份简历将扣除<span> {$points}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}，您目前共有<span> {$mypoints}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}";
        }
    } elseif ($_SESSION['utype'] == '3') {
        if ($_CFG['operation_hunter_mode'] == '2') {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if (empty($setmeal) || ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0")) {
                $str = "<a href=\"" . get_member_url(3, true) . "hunter_service.php?act=setmeal_list\">[申请服务]</a>";
                exit("您的服务已到期。您可以 {$str}");
            } elseif ($setmeal['download_resume_manager'] <= 0) {
                $str = "<a href=\"" . get_member_url(3, true) . "hunter_service.php?act=setmeal_list\">[申请服务]</a>";
                exit("你下载经理人才简历数量已经超出了限制。您可以{$str}");
            }

            $tip = "提示：您还可以下载<span> {$setmeal['download_resume_manager']}</span>份高级人才简历";
        } elseif ($_CFG['operation_hunter_mode'] == '1') {
            $points_rule = get_cache('points_rule');
            $points = $points_rule['hunter_resume_download_huntered']['value'];
            $mypoints = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $mypoints = $mypoints + $user_limit_points;
            if ($mypoints < $points) {
                $str = "<a href=\"" . get_member_url(3, true) . "hunter_service.php?act=order_add\">[充值{$_CFG['hunter_points_byname']}]</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                exit("你的" . $_CFG['hunter_points_byname'] . "不足，请充值后下载。" . $str);
            }
            $tip = "下载此份简历将扣除<span> {$points}</span>{$_CFG['hunter_points_quantifier']}{$_CFG['hunter_points_byname']}，您目前共有<span> {$mypoints}</span>{$_CFG['hunter_points_quantifier']}{$_CFG['hunter_points_byname']}";
        }
    }
    ?>
    <script type="text/javascript">
        $(".but100").hover(function() {
            $(this).addClass("but100_hover")
        }, function() {
            $(this).removeClass("but100_hover")
        });

        $("#ajax_download_r").click(function() {
            var id = "<?php echo $id ?>";
            var tsTimeStamp = new Date().getTime();
            $("#ajax_download_r").val("处理中...");
            $("#ajax_download_r").attr("disabled", "disabled");
            var pms_notice = $("#pms_notice").attr("checked");
            if (pms_notice)
                pms_notice = 1;
            else
                pms_notice = 0;
            $.get("<?php echo $_CFG['site_dir'] ?>user/user_download_manager_resume.php", {"id": id, "pms_notice": pms_notice, "time": tsTimeStamp, "act": "download_save"},
            function(data, textStatus)
            {
                if (data == "ok")
                {
                    $(".ajax_download_tip").hide();
                    $("#ajax_download_table").hide();
                    $("#download_ok").show();
                    $("#download_ok .closed").click(function() {
                        DialogClose();
                    });
                    //刷新联系地址
                    $.get("<?php echo $_CFG['site_dir'] ?>plus/ajax_contact.php", {"id": id, "time": tsTimeStamp, "act": "manager_resume_contact"},
                    function(data, textStatus)
                    {
                        $("#resume_contact").html(data);
                    }
                    );
                }
                else
                {
                    alert(data);
                }
                $("#ajax_download_r").val("下载简历");
                $("#ajax_download_r").attr("disabled", "");
            })
        });
        function DialogClose()
        {
            $("#FloatBg").hide();
            $("#FloatBox").hide();
        }
    </script>
    <div class="ajax_download_tip"><?php echo $tip ?></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" id="ajax_download_table">
        <tr>
            <td align="right" >站内信通知对方：</td>
            <td>
                <label><input type="checkbox" name="pms_notice" id="pms_notice" value="1"  checked="checked"/>
                    站内信通知
                </label>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2"><input type="button" name="Submit"  id="ajax_download_r" class="but100" value="下载简历" /></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="8" cellpadding="0" id="download_ok"  style="display:none">
        <tr>
            <td width="80" height="120" align="right" valign="top"><img src="<?php echo $_CFG['site_template'] ?>images/13.gif" /></td>
            <td>
                <strong style=" font-size:14px ; color:#0066CC;">下载成功!</strong>
                <div style="border-top:1px #CCCCCC solid; line-height:180%; margin-top:10px; padding-top:10px; height:100px;"  class="dialog_closed">
                    <?php echo $downresumeurl; ?><br />

                    <a href="javascript:void(0)"  class="DialogClose">下载完成</a>

                </div>
            </td>
        </tr>
    </table>
    <?php
} elseif ($act == "download_save") {
    $ruser = get_user_info($resumeshow['uid']);
    $pms_notice = intval($_GET['pms_notice']);
    if ($_SESSION['utype'] == '1') {
        if ($_CFG['operation_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if ($setmeal['download_resume_huntered'] > 0 && add_com_down_manager_resume($id, $_SESSION['uid'], $resumeshow['uid'], $resumeshow['resume_name'])) {
                action_user_setmeal($_SESSION['uid'], "download_resume_huntered");
                $setmeal = get_user_setmeal($_SESSION['uid']);
                write_memberslog($_SESSION['uid'], 1, 9002, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历,还可以下载 {$setmeal['download_resume_huntered']} 份经理人简历");
                write_memberslog($_SESSION['uid'], 1, 4001, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历");
                //站内信
                if ($pms_notice == '1') {
                    $company = $db->getone("select id,companyname  from " . table('company_profile') . " where uid ={$_SESSION['uid']} limit 1");
                    $user = $db->getone("select username from " . table('members') . " where uid ={$resumeshow['uid']} limit 1");
                    $resume_url = url_rewrite('QS_manager_resumeshow', array('id' => $id));
                    $company_url = url_rewrite('QS_companyshow', array('id' => $company['id']));
                    $message = $_SESSION['username'] . "下载了您发布的经理人简历：<a href=\"{$resume_url}\" target=\"_blank\">{$resumeshow['resume_name']}</a>，<a href=\"$company_url\" target=\"_blank\">点击查看公司详情</a>";
                    write_pmsnotice($resumeshow['uid'], $user['username'], $message);
                }
                exit("ok");
            }
        } elseif ($_CFG['operation_mode'] == "1") {
            $points_rule = get_cache('points_rule');
            $points = $points_rule['resume_download_huntered']['value'];
            $ptype = $points_rule['resume_download_huntered']['type'];
            $mypoints = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $mypoints = $mypoints + $user_limit_points;
            if ($mypoints < $points) {
                exit("err");
            }
            if (add_com_down_manager_resume($id, $_SESSION['uid'], $resumeshow['uid'], $resumeshow['resume_name'])) {
                if ($points > 0) {
                    report_deal($_SESSION['uid'], $ptype, $points);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $ptype == "1" ? "+" : "-";
                    write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历({$operator}{$points}),(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                    write_memberslog($_SESSION['uid'], 1, 4001, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历");
                    //站内信
                    if ($pms_notice == '1') {
                        $company = $db->getone("select id,companyname  from " . table('company_profile') . " where uid ={$_SESSION['uid']} limit 1");
                        $user = $db->getone("select username from " . table('members') . " where uid ={$resumeshow['uid']} limit 1");
                        $resume_url = url_rewrite('QS_manager_resumeshow', array('id' => $id));
                        $company_url = url_rewrite('QS_companyshow', array('id' => $company['id']));
                        $message = $_SESSION['username'] . "下载了您发布的经理人简历：<a href=\"{$resume_url}\" target=\"_blank\">{$resumeshow['resume_name']}</a>，<a href=\"$company_url\" target=\"_blank\">点击查看公司详情</a>";
                        write_pmsnotice($resumeshow['uid'], $user['username'], $message);
                    }
                }
                exit("ok");
            }
        }
    } elseif ($_SESSION['utype'] == '3') {
        if ($_CFG['operation_hunter_mode'] == "2") {
            $setmeal = get_user_setmeal($_SESSION['uid']);
            if ($setmeal['download_resume_manager'] > 0 && add_hun_down_manager_resume($id, $_SESSION['uid'], $resumeshow['uid'], $resumeshow['resume_name'])) {
                action_user_setmeal($_SESSION['uid'], "download_resume_manager");
                $setmeal = get_user_setmeal($_SESSION['uid']);
                write_memberslog($_SESSION['uid'], 3, 9202, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历,还可以下载 {$setmeal['download_resume_huntered']} 份经理人简历");
                write_memberslog($_SESSION['uid'], 3, 4001, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历");
                //站内信
                if ($pms_notice == '1') {
                    $hunter = $db->getone("select id,huntername  from " . table('hunter_profile') . " where uid ={$_SESSION['uid']} limit 1");
                    $user = $db->getone("select username from " . table('members') . " where uid ={$resumeshow['uid']} limit 1");
                    $resume_url = url_rewrite('QS_manager_resumeshow', array('id' => $id));
                    $message = $_SESSION['username'] . "下载了您发布的经理人简历：<a href=\"{$resume_url}\" target=\"_blank\">{$resumeshow['resume_name']}</a>，猎头姓名：{$hunter['huntername']}";
                    write_pmsnotice($resumeshow['uid'], $user['username'], $message);
                }
                exit("ok");
            }
        } elseif ($_CFG['operation_hunter_mode'] == "1") {
            $points_rule = get_cache('points_rule');
            $points = $points_rule['hunter_resume_download_huntered']['value'];
            $ptype = $points_rule['hunter_resume_download_huntered']['type'];
            $mypoints = get_user_points($_SESSION['uid']);
            $user_limit_points = get_user_limit_points($_SESSION['uid']);
            $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
            $mypoints = $mypoints + $user_limit_points;
            if ($mypoints < $points) {
                exit("err");
            }
            if (add_hun_down_manager_resume($id, $_SESSION['uid'], $resumeshow['uid'], $resumeshow['resume_name'])) {
                if ($points > 0) {
                    report_deal($_SESSION['uid'], $ptype, $points);
                    $user_points = get_user_points($_SESSION['uid']);
                    $user_limit_points = get_user_limit_points($_SESSION['uid']);
                    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                    $operator = $ptype == "1" ? "+" : "-";
                    write_memberslog($_SESSION['uid'], 3, 9201, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历({$operator}{$points}),(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                    write_memberslog($_SESSION['uid'], 3, 4001, $_SESSION['username'], "下载了 {$ruser['username']} 发布的经理人简历");
                    //站内信
                    if ($pms_notice == '1') {
                        $hunter = $db->getone("select id,huntername  from " . table('hunter_profile') . " where uid ={$_SESSION['uid']} limit 1");
                        $user = $db->getone("select username from " . table('members') . " where uid ={$resumeshow['uid']} limit 1");
                        $resume_url = url_rewrite('QS_manager_resumeshow', array('id' => $id));
                        $hunter_url = url_rewrite('QS_huntershow', array('id' => $hunter['id']));
                        $message = $_SESSION['username'] . "下载了您发布的经理人简历：<a href=\"{$resume_url}\" target=\"_blank\">{$resumeshow['resume_name']}</a>，猎头姓名：{$hunter['huntername']}";
                        write_pmsnotice($resumeshow['uid'], $user['username'], $message);
                    }
                }
                exit("ok");
            }
        }
    }
}
?>