<?php
/*
 * 74cms  延期职位
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
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
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

if ($_SESSION['utype'] != '1') {
    exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td class="ajax_app">
					必须是企业会员才可以延期职位！
				</td>
		    </tr>
		</table>');
}
require_once(QISHI_ROOT_PATH . 'include/fun_company.php');
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
$id = !empty($_GET['id']) ? trim($_GET['id']) : exit("出错了");
$id = explode(",", $id);
if (!is_array($id))
    $id = array($id);
$count_id = count($id);
$sqlin = implode(",", $id);
$jobs = get_jobs_by_idstr($sqlin, $_SESSION['uid']);
if ($_CFG['operation_mode'] == "1") {
    $operation_mode = 1;
    $points_rule = get_cache('points_rule');
    $day_points = $points_rule['jobs_daily']['value'];
    $mypoints = get_user_points($_SESSION['uid']);
    $tip = "提示：延期职位，每条职位每天消耗<span> {$day_points}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}，您目前共有<span> {$mypoints}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}";
} elseif ($_CFG['operation_mode'] == "3") {
    $operation_mode = 1;
    $setmeal = get_user_setmeal($_SESSION['uid']);
    $points_rule = get_cache('points_rule');
    $day_points = $points_rule['jobs_daily']['value'];
    $mypoints = get_user_points($_SESSION['uid']);
    $tip = "提示：延期职位，每条职位每天消耗<span> {$day_points}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}，您目前共有<span> {$mypoints}</span>{$_CFG['points_quantifier']}{$_CFG['points_byname']}";
    if (empty($setmeal) || ($setmeal['endtime'] < time() && $setmeal['endtime'] <> '0')) {
        if ($_CFG['setmeal_to_points'] == "1") {
            $operation_mode = 1;
        } else {
            $str = "<a href=\"" . get_member_url(1, true) . "company_service.php?act=setmeal_list\">[申请服务]</a>";
            exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td class="ajax_app">
						您的服务已到期,不能延期职位。您可以 ' . $str . '
					</td>
			    </tr>
			</table>');
        }
    }
}

if ($act == "delay") {
    ?>
    <script type="text/javascript">
        $(".but100").hover(function() {
            $(this).addClass("but100_hover")
        }, function() {
            $(this).removeClass("but100_hover")
        });
        var operation_mode = "<?php echo $operation_mode; ?>";
        if (operation_mode == '1') {
            $("#days").keyup(function() {
                if ((/^(\+|-)?\d+$/.test($(this).val())))
                {
                    var days_points = "<?php echo $day_points; ?>";
                    var user_points = "<?php echo $mypoints; ?>";
                    var total_count = "<?php echo $count_id ?>";
                    var total_points = days_points * $(this).val() * total_count;
                    var points_quantifier = "<?php echo $_CFG['points_quantifier']; ?>";
                    var points_byname = "<?php echo $_CFG['points_byname']; ?>";
                    var tip = "提示：您延期" + total_count + "个职位,延期天数<span>" + $(this).val() + "天</span>,需要扣除：" + "<span>" + total_points + "</span>" + points_quantifier + points_byname + "，您目前共有<span>" + user_points + "</span>" + points_quantifier + points_byname;
                    $(".ajax_delay_tip").html(tip)
                } else {
                    var tip = "<?php echo $tip; ?>";
                    $(".ajax_delay_tip").html(tip)
                }
            });
        }
        $("#ajax_delay_r").click(function() {
            var id = "<?php echo $sqlin ?>";
            var days = $("#days").val();
            var tsTimeStamp = new Date().getTime();
            $("#ajax_delay_r").val("处理中...");
            $("#ajax_delay_r").attr("disabled", "disabled");
            $.get("<?php echo $_CFG['site_dir'] ?>user/user_delay_jobs.php", {"id": id, "days": days, "time": tsTimeStamp, "act": "delay_save"},
            function(data, textStatus)
            {
                if (data == "ok")
                {
                    $(".ajax_delay_tip").hide();
                    $("#ajax_delay_table").hide();
                    $("#delay_ok").show();
                    $("#delay_ok .closed").click(function() {
                        DialogClose();
                    });
                }
                else
                {
                    $(".ajax_delay_tip").html(data);
                    $("#ajax_delay_table").hide();
                }
                $("#ajax_delay_r").val("延期职位");
                $("#ajax_delay_r").attr("disabled", "");
            })
        });
        function DialogClose()
        {
            $("#FloatBg").hide();
            $("#FloatBox").hide();
        }
    </script>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
        <tr>
            <td class="ajax_delay_tip"><?php echo $tip ?></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="ajax_delay_table">
        <tr>
            <td width="120" align="right">延期天数：</td>
            <td><input type="text" name="days"  id="days" class="input_text_200" maxlength="3" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="button" name="Submit"  id="ajax_delay_r" class="but130lan" value="延期职位" />
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="delay_ok" style="display:none">
        <tr>
            <td width="140" align="right"><img height="100" src="<?php echo $_CFG['site_template'] ?>images/14.gif" /></td>
            <td>
                <strong style="font-size:14px ; color:#0066CC;margin-left:20px">延期成功!</strong>
                <div style="border-top:1px #CCCCCC solid; line-height:180%; margin-top:10px; padding-top:10px; height:50px;margin-left:20px"  class="dialog_closed">
                    <a href="<?php echo get_member_url(1, true) ?>company_jobs.php?act=jobs&jobtype=1" >查看已延期的职位</a><br />
                </div>
            </td>
        </tr>
    </table>
    <?php
} elseif ($act == "delay_save") {
    $id = trim($_GET['id']) ? trim($_GET['id']) : exit("出错了");
    $days = intval($_GET['days']) ? intval($_GET['days']) : exit("出错了");
    // $olddeadline=intval($_GET['olddeadline'])?intval($_GET['olddeadline']):exit("出错了");
    if ($operation_mode == "1") {
        $points_rule = get_cache('points_rule');
        $day_points = $points_rule['jobs_daily']['value'];
        $ptype = $points_rule['jobs_daily']['type'];
        $mypoints = get_user_points($_SESSION['uid']);
        $user_limit_points = get_user_limit_points($_SESSION['uid']);
        $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
        $mypoints = $mypoints + $user_limit_points;
        if ($points_rule['jobs_daily']['type'] == "2" && $points_rule['jobs_daily']['value'] > 0) {
            $points = $day_points * $days * $count_id;
        }
        if ($mypoints < $points) {
            $str = "<a href=\"" . get_member_url(1, true) . "company_service.php?act=order_add\">[充值{$_CFG['points_byname']}]</a>";
            exit("你的" . $_CFG['points_byname'] . "不足，请" . $str);
        }
        if (delay_jobs($id, $_SESSION['uid'], $days)) {
            if ($points > 0) {
                report_deal($_SESSION['uid'], $ptype, $points);
                $user_points = get_user_points($_SESSION['uid']);
                $user_limit_points = get_user_limit_points($_SESSION['uid']);
                $user_limit_points = empty($user_limit_points['points']) ? 0 : $user_limit_points['points'];
                $operator = $ptype == "1" ? "+" : "-";
                write_memberslog($_SESSION['uid'], 1, 9001, $_SESSION['username'], "延长职位({$id})有效期为{$days}天，({$operator}{$points})，(剩余积分:{$user_points}，剩余限时积分:{$user_limit_points})");
                write_memberslog($_SESSION['uid'], 1, 2007, $_SESSION['username'], "延长职位({$id}) 有效期为{$days}天"); //记录套餐操作
            }
            exit("ok");
        }
    }
}
?>