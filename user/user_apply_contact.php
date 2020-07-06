<?php
/*
 * 74cms 查看课程申请人联系方式
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
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'downtype';
require_once(QISHI_ROOT_PATH . 'include/mysql.class.php');
$db = new mysql($dbhost, $dbuser, $dbpass, $dbname);
if ((empty($_SESSION['uid']) || empty($_SESSION['username']) || empty($_SESSION['utype'])) && $_COOKIE['QS']['username'] && $_COOKIE['QS']['password'] && $_COOKIE['QS']['uid']) {
    require_once(QISHI_ROOT_PATH . 'include/fun_user.php');
    if (check_cookie($_COOKIE['QS']['uid'], $_COOKIE['QS']['username'], $_COOKIE['QS']['password'])) {
        update_user_info($_COOKIE['QS']['uid'], false, false);
        header("Location:" . get_member_url($_SESSION['utype']));
    } else {
        unset($_SESSION['uid'], $_SESSION['username'], $_SESSION['utype'], $_SESSION['uqqid'], $_SESSION['activate_username'], $_SESSION['activate_email'], $_SESSION["openid"]);
        setcookie("QS[uid]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[username]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie('QS[password]', "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
        setcookie("QS[utype]", "", time() - 3600, $QS_cookiepath, $QS_cookiedomain);
    }
}
if ($_SESSION['uid'] == '' || $_SESSION['username'] == '') {
    $captcha = get_cache('captcha');
    $smarty->assign('verify_userlogin', $captcha['verify_userlogin']);
    $smarty->display('plus/ajax_login.htm');
    exit();
}
if ($_SESSION['utype'] != '4') {
    exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td class="ajax_app">
					必须是培训会员才可以查看联系方式！
				</td>
		    </tr>
		</table>');
}
require_once(QISHI_ROOT_PATH . 'include/fun_train.php');
$user = get_user_info($_SESSION['uid']);
if ($user['status'] == "2") {
    exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
				    <tr>
						<td width="20" align="right"></td>
						<td class="ajax_app">
							您的账号处于暂停状态，请联系管理员设为正常后进行操作！
						</td>
				    </tr>
				</table>');
}
$id = !empty($_GET['id']) ? intval($_GET['id']) : exit("出错了");
$applyshow = get_apply_one($id);
if ($applyshow['download_type'] == '1') {
    $contact = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
				    <tr>
						<td width="80" align="right">姓名：</td>
						<td>
							' . $applyshow['realname'] . '
						</td>
				    </tr>
					<tr>
						<td width="80" align="right">电话：</td>
						<td>
							' . $applyshow['mobile'] . '
						</td>
				    </tr>
					<tr>
						<td width="80" align="right">邮箱：</td>
						<td>
							' . $applyshow['email'] . '
						</td>
				    </tr>
				</table>';

    exit($contact);
}
if ($_CFG['operation_train_mode'] == "2") {
    $setmeal = get_user_setmeal($_SESSION['uid']);
    if (empty($setmeal) || ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0')) {
        $str = "<a href=\"" . get_member_url(4, true) . "train_service.php?act=setmeal_list\">[申请服务]</a>";
        exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
				    <tr>
						<td width="20" align="right"></td>
						<td class="ajax_app">
							您的服务已到期。您可以 ' . $str . '
						</td>
				    </tr>
				</table>');
    } elseif ($setmeal['down_apply'] <= 0) {
        $str = "<a href=\"" . get_member_url(4, true) . "train_service.php?act=setmeal_list\">[申请服务]</a>";
        exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
				    <tr>
						<td width="20" align="right"></td>
						<td class="ajax_app">
							你的查看课程申请人联系方式数量已经超出了限制。您可以 ' . $str . '
						</td>
				    </tr>
				</table>');
    }
} elseif ($_CFG['operation_train_mode'] == "1") {
    $points_rule = get_cache('points_rule');
    $points = $points_rule['down_apply']['value'];
    $mypoints = get_user_points($_SESSION['uid']);
    $user_limit_points = get_user_limit_points($_SESSION['uid']);
    $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
    $mypoints = $mypoints + $user_limit_points;
    if ($mypoints < $points) {
        $str = "<a href=\"" . get_member_url(4, true) . "train_service.php?act=order_add\">[充值{$_CFG['train_points_byname']}]</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
				    <tr>
						<td width="20" align="right"></td>
						<td class="ajax_app">
							你的' . $_CFG['train_points_byname'] . '不足，请充值后下载。 ' . $str . '
						</td>
				    </tr>
				</table>');
    }
}
if ($act == "downtype") {
    if ($_CFG['operation_train_mode'] == "2") {
        $tip = "提示：你的查看课程申请人联系方式数量<span> {$setmeal['down_apply']}</span>份";
    } else {
        $tip = "查看联系方式将扣除<span> {$points} </span>{$_CFG['train_points_quantifier']}{$_CFG['train_points_byname']}，您目前共有<span> {$mypoints} </span>{$_CFG['train_points_quantifier']}{$_CFG['train_points_byname']}";
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
            $("#ajax_download_r").hide();
            $("#waiting").show();
            $.get("<?php echo $_CFG['site_dir'] ?>user/user_apply_contact.php", {"id": id, "time": tsTimeStamp, "act": "download_save"},
            function(data, textStatus)
            {
                if (data == "ok")
                {
                    $(".ajax_download_tip").hide();
                    $("#ajax_download_table").hide();
                    $("#notice").hide();
                    $("#waiting").hide();
                    //刷新联系地址
                    $.get("<?php echo $_CFG['site_dir'] ?>user/user_apply_contact.php", {"id": id, "time": tsTimeStamp, "act": "downtype"},
                    function(data, textStatus)
                    {
                        $("#download_ok").html(data);
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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="ajax_download_table">
        <tr>
            <td></td>
            <td align="center">
                <input type="button" name="Submit"  id="ajax_download_r" class="but130lan" value="查看联系方式" />
            </td>
        </tr>
    </table>
    <table id="notice" width="100%" border="0" style="border-top:1px #CCCCCC dotted;background-color: #EEEEEE; line-height: 230%;padding: 15px; margin-top: 10px; ">
        <tbody><tr>
                <td class="ajax_download_tip"><?php echo $tip ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="5" cellpadding="0" id="waiting"  style="display:none">
        <tr>
            <td align="center" height="60"><img src="<?php echo $_CFG['site_template'] ?>images/30.gif"  border="0"/></td>
        </tr>
        <tr>
            <td align="center" >请稍后...</td>
        </tr>
    </table>
    <div id="download_ok"></div>
    <?php
} elseif ($act == "download_save") {
    $ruser = get_user_info($applyshow['personal_uid']);
    if ($_CFG['operation_train_mode'] == '2') {
        if ($setmeal['down_apply'] > 0 && add_down_apply($id, $_SESSION['uid'])) {
            action_user_setmeal($_SESSION['uid'], "down_apply");
            $setmeal = get_user_setmeal($_SESSION['uid']);
            write_memberslog($_SESSION['uid'], 4, 9102, $_SESSION['username'], "查看了 {$ruser['username']} 的课程申请联系方式,还可以查看 {$setmeal['down_apply']} 个课程申请的联系方式");
            write_memberslog($_SESSION['uid'], 4, 8206, $_SESSION['username'], "查看了 {$ruser['username']} 的课程申请联系方式");

            $mailconfig = get_cache('mailconfig');
            $sms = get_cache('sms_config');
            $personuser = get_user_info($applyshow['personal_uid']);

            if ($mailconfig['set_downapp'] == '1' && $personuser['email_audit'] == '1') {
                dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_mail.php?uid={$_SESSION['uid']}&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_downapp&coursename={$applyshow['course_name']}&trainname={$applyshow['train_name']}&email={$personuser['email']}");
            }
            //sms	
            if ($sms['open'] == '1' && $sms['set_downapp'] == '1' && $personuser['mobile_audit'] == "1") {
                dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_sms.php?uid={$_SESSION['uid']}&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_downapp&coursename={$applyshow['course_name']}&trainname={$applyshow['train_name']}&mobile={$personuser['mobile']}");
            }

            exit("ok");
        }
    } elseif ($_CFG['operation_train_mode'] == "1") {
        $points_rule = get_cache('points_rule');
        $points = $points_rule['down_apply']['value'];
        $ptype = $points_rule['down_apply']['type'];
        $mypoints = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $mypoints = $mypoints + $user_limit_points;
        if ($mypoints < $points) {
            exit("err");
        }
        if (add_down_apply($id, $_SESSION['uid'])) {
            if ($points > 0) {
                report_deal($_SESSION['uid'], $ptype, $points);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $ptype == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 4, 9101, $_SESSION['username'], "查看了 {$ruser['username']} 的课程申请联系方式({$operator}{$points}),(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                write_memberslog($_SESSION['uid'], 4, 8206, $_SESSION['username'], "查看了 {$ruser['username']} 的课程申请联系方式");
            }

            $mailconfig = get_cache('mailconfig');
            $sms = get_cache('sms_config');
            $personuser = get_user_info($applyshow['personal_uid']);
            if ($mailconfig['set_downapp'] == '1' && $personuser['email_audit'] == '1') {
                dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_mail.php?uid={$_SESSION['uid']}&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_downapp&coursename={$applyshow['course_name']}&trainname={$applyshow['train_name']}&email={$personuser['email']}");
            }
            //sms	
            if ($sms['open'] == '1' && $sms['set_downapp'] == '1' && $personuser['mobile_audit'] == '1') {
                dfopen("{$_CFG['site_domain']}{$_CFG['site_dir']}plus/asyn_sms.php?uid={$_SESSION['uid']}&key=" . asyn_userkey($_SESSION['uid']) . "&act=set_downapp&coursename={$applyshow['course_name']}&trainname={$applyshow['train_name']}&mobile={$personuser['mobile']}");
            }

            exit("ok");
        }
    }
}
?>