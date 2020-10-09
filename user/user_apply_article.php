<?php
/*
 * 发送邮件到简章对应的邮箱
 */
define('IN_QISHI', true);
require_once(dirname(__FILE__) . '/../include/common.inc.php');
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'app';
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
if ($_SESSION['utype'] != '2') {
    exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
		    <tr>
				<td width="20" align="right"></td>
				<td class="ajax_app">
					必须是个人会员才可以投简历！
				</td>
		    </tr>
		</table>');
}
/* if (empty($_GET['email'])) {
  exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
  <tr>
  <td width="20" align="right"></td>
  <td class="ajax_app">
  该简章暂无投递邮箱，请按照简章上的招聘方案投递简历！
  </td>
  </tr>
  </table>');
  } */
require_once(QISHI_ROOT_PATH . 'include/fun_personal.php');
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
if ($act == "app") {
    $id = isset($_GET['id']) ? $_GET['id'] : exit("id 丢失");
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    //这里开始发送邮件，先检查简章是否有邮箱，没有邮箱则提示直接打电话咨询或登录相关网站
    $article = app_get_article($id);
    if (empty($article)) {
        exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td class="ajax_app">
						投简历失败！
					</td>
			    </tr>
			</table>');
    }
    //再检查简历是否存在，不存在则提示相关信息
    $resume_list = get_auditresume_list($_SESSION['uid']);
    if (empty($resume_list)) {
        $str = "<a href=\"" . get_member_url(2, true) . "personal_resume.php?act=resume_list\">[查看我的简历]</a>";
        exit('<p style="text-align:center;font-size:16px;padding:20px;">
					投简历失败，您没有填写简历或者简历不可见 ' . $str . '
			</p>');
    }
    $sql = "select * from " . table("jiaoshi_article_jobs") . " where article_id = " . intval($_GET['id']);
    $article_jobs_list = get_mem_cache($sql, "get_all");
    ?>
    <script type="text/javascript">
        $(".but80").hover(function() {
            $(this).addClass("but80_hover")
        }, function() {
            $(this).removeClass("but80_hover")
        });
        //计算今天申请数量
        var app_max = "<?php echo $_CFG['apply_jobs_max'] ?>";
        var app_today = "<?php echo get_now_applyjobs_num($_SESSION['uid']) ?>";
        $(".ajax_app_tip > span:eq(0)").html(app_max);
        $(".ajax_app_tip > span:eq(1)").html(app_today);
        $(".ajax_app_tip > span:eq(2)").html(app_max - app_today);
        //验证
        $("#ajax_app").click(function() {
            /*if (app_max-app_today==0 || app_max-app_today<0 )
             {
             alert("您今天投简历数量已经超出最大限制！");
             }
             else if ($("#app :checkbox[checked]").length>(app_max-app_today))
             {
             alert("您今天还可以投递"+(app_max-app_today)+"个简历，已选职位超出了最大限制！");
             }
             else if ($("#app :checkbox[checked]").length==0)
             {
             alert("请选择投递的职位！");
             }
             else if ($("#app :radio[checked]").length==0)
             {
             alert("请选择你的简历！");
             }
             else
             {*/
            $("#app").hide();
            $("#notice").hide();
            $("#waiting").show();
            var tsTimeStamp = new Date().getTime();
            var jidArr = new Array();

            var pms_notice = $("#pms_notice").attr("checked");
            if (pms_notice)
                pms_notice = 1;
            else
                pms_notice = 0;
            $("#app :checkbox[checked][name='jobsid']").each(function(index) {
                jidArr[index] = $(this).val();
            });
            //这里发邮件
            //
            $.post("<?php echo $_CFG['website_dir'] ?>user/user_apply_article.php", {"resumeid": $("#app :radio[checked]").val(), "email": $("#email").val(), "article_job": $("#article_job").val(), "note": $("#note").val(), "article_id": <?php echo $_GET['id']; ?>, "time": tsTimeStamp, "act": "app_save"},
            function(data, textStatus)
            {
                if (data == "ok")
                {
                    $("#app").hide();
                    $("#notice").hide();
                    $("#waiting").hide();
                    $("#app_ok").show();
                    $("#app_ok .closed").click(function() {
                    });
                }
                else if (data == "repeat")
                {
                    $("#app").show();
                    $("#notice").show();
                    $("#waiting").hide();
                    $("#app_ok").hide();
                    alert("您投递过此职位，不能重复投递");
                }
                else
                {
                    $("#app").show();
                    $("#notice").show();
                    $("#waiting").hide();
                    $("#app_ok").hide();
                    alert("投递失败！" + data);
                }
            })
            //}
        });
        function DialogClose()
        {
            $("#FloatBg").hide();
            $("#FloatBox").hide();
        }
    </script>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="app">
        <tr>
            <td width="120" align="right">要投递的邮箱：</td>
            <td class="ajax_app">
                <input type="text" name="email" id="email" value="<?php echo $_GET['email']; ?>" style="width:295px;padding:6px 4px;border:1px solid #bbb;">
            </td>
        </tr>
        <?php if (!empty($article_jobs_list)): ?>
            <tr>
                <td width="120" align="right">选择职位：</td>
                <td>
                    <select id="article_job" style="border:1px solid #ccc; padding:0px 5px 0px 0px;" name="article_job">
                        <?php
                        foreach ($article_jobs_list as $jobs) {
                            $select_str = $jobs['id'] == $job_id && $job_id > 0 ? "selected" : "";
                            echo "<option value = '" . $jobs['id'] . "' " . $select_str . ">" . $jobs['job_name'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td width="120" align="right">选择简历：</td>
            <td>
                <ul>
                    <?php
                    foreach ($resume_list as $resume) {
                        ?>
                        <li><label><input name="resumeid" checked="checked" type="radio" value="<?php echo $resume['id'] ?>"  /><?php echo $resume['title'] ?></label>&nbsp;&nbsp;
                            <a href="<?php echo url_rewrite('QS_resumeshow', array('id' => $resume['id'])) ?>" target="_blank">[预览]</a>
                            <?php
                        }
                        ?>
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">附加说明：</td>
            <td>
                <textarea id="note" style="width:295px; height: 60px; border:1px solid #ccc;" name="note"></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="button" name="Submit"  id="ajax_app" class="but130lan" value="投递" />
            </td>
        </tr>
    </table>
    <table id="notice" width="100%" border="0" style="border-top:1px #CCCCCC dotted;background-color: #EEEEEE; line-height: 230%;padding: 15px; margin-top: 10px; ">
        <tbody><tr>
                <td class="ajax_app_tip">系统将把您的简历发送到相应的事业单位
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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="app_ok" style="display:none">
        <tr>
            <td width="140" align="right"><img height="100" src="<?php echo $_CFG['site_template'] ?>images/14.gif" /></td>
            <td>
                <strong style="font-size:14px ; color:#0066CC;margin-left:20px">投递成功!</strong>
                <div style="border-top:1px #CCCCCC solid; line-height:180%; margin-top:10px; padding-top:10px; height:50px;margin-left:20px"  class="dialog_closed">
                    <a href="<?php echo get_member_url(2, true) ?>personal_apply.php?act=apply_jobs" >查看已投递的职位</a><br />
                    <a href="javascript:void(0)"  class="DialogClose">投递完成</a>
                </div>
            </td>

        </tr>
        <tr>
            <td width="140" align="right"><img height="100" src="<?php echo $_CFG['site_template'] ?>images/m_weixin.jpg" /></td>
            <td>
                <strong style="font-size:14px ; color:#0066CC;margin-left:20px">绑定公众号，获取最新职位动态。</strong>
            </td>
        </tr>
    </table>
    <?php
} elseif ($act == "app_save") {
    $article_id = isset($_POST['article_id']) ? $_POST['article_id'] : exit("出错了");
    $resumeid = isset($_POST['resumeid']) ? intval($_POST['resumeid']) : exit("出错了");
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $job = isset($_POST['article_job']) ? trim($_POST['article_job']) : exit("出错了");
    $note = isset($_POST['note']) ? trim($_POST['note']) : "";

    $sql = "select * from " . table("jiaoshi_article_jobs") . " where id = " . intval($job);
    $article_jobs = get_mem_cache($sql, "getone");
    if (empty($article_jobs)) {
        exit("职位丢失");
    }
    $article = app_get_article($article_id);
    if (empty($article)) {
        exit("简章丢失");
    }
    $resume = get_resume_basic($_SESSION['uid'], $resumeid);
    if (empty($resume)) {
        exit("简历丢失");
    }
    $resume_work = get_resume_work($_SESSION['uid'], $resumeid);
    $age = intval($resume['birthdate']) > 0 ? date('Y', time()) - $resume['birthdate'] : "未填写";
    if (!check_article_apply($_SESSION['uid'], $job, $resumeid)) {
        $from_email = $resume['email'];
        $word_content = "";
        $attachment_arr = "";
        if (!empty($resume_work)) {
            foreach ($resume_work as $rw) {
                $word_content .= '<div style="width:100%;float:left;display:block;">';
                $word_content .= '<p>' . $rw['startyear'] . '.' . $rw['startmonth'] . '至' . $rw['endyear'] . '.' . $rw['endmonth'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['jobs'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['companyname'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['achievements'] . '</p>';
                $word_content .= '</div>';
            }
        }
        $content = '<style>
				a.logo {
									background:url(../images/header_logo.png) no-repeat;
					background:url("' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png") no-repeat;
					display: block;
					width: 160px;
					height: 44px;
					margin: 10px 40px;
					float: left;
				}
				.jl_peo_infor_li {
					float: left;
					width: 48.1%;
					padding-right: 9px;
					font-size: 14px;
					height: 30px;
					line-height: 30px;
					color: #333;
					overflow: hidden;
					zoom: 1;
				}
				.jl_peo_name {
					margin-bottom: 15px;
					float: left;
					color: #333;
					font-size: 28px;
					font-family: "微软雅黑";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="教师招聘网" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">您收到一份来自“教师招聘网”的简历投递</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>教师招聘网，中国最大的教师招聘专业网站，立即<a href="' . $_CFG['main_domain'] . '/user/reg.php">注册</a>免费发布教师职位。已有帐号，请<a href="' . $_CFG['main_domain'] . '/user/login.php">登录</a>。</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>本简历投递来自：<a href="' . $_CFG['main_domain'] . '/morejobs/jobshow_' . $article['id'] . '.html">' . $article['title'] . '</a></p>
						<p>投递职位：' . $article_jobs['job_name'] . '</p>
						<p>附加说明：' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>年　　龄：</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>性　　别：</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>最高学历：</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>工作经验：</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>联系方式：</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>电子邮箱：</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望地区：</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望薪酬：</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>工作经历</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            ' . $word_content . '
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>自我介绍</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            <p style="width:100%;float:left;display:block;">
                                                            ' . $resume['specialty'] . '
                                                            </p>
                                                            <div class="jl_peo_infor_li"></div>
                                                        </div>
							<div style="clear:both;"></div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . '/ProfileBank/ShowResume.shtml?ID=' . $resume['id'] . '" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看完整简历</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
        $attachment_content = '<style>
				a.logo {
									background:url(../images/header_logo.png) no-repeat;
					background:url("' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png") no-repeat;
					display: block;
					width: 160px;
					height: 44px;
					margin: 10px 40px;
					float: left;
				}
				.jl_peo_infor_li {
					float: left;
					width: 48.1%;
					padding-right: 9px;
					font-size: 14px;
					height: 30px;
					line-height: 30px;
					color: #333;
					overflow: hidden;
					zoom: 1;
				}
				.jl_peo_name {
					margin-bottom: 15px;
					float: left;
					color: #333;
					font-size: 28px;
					font-family: "微软雅黑";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="教师招聘网" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">您收到一份来自“教师招聘网”的简历投递</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>教师招聘网，中国最大的教师招聘专业网站，立即<a href="' . $_CFG['main_domain'] . '/user/reg.php">注册</a>免费发布教师职位。已有帐号，请<a href="' . $_CFG['main_domain'] . '/user/login.php">登录</a>。</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>本简历投递来自：<a href="' . $_CFG['main_domain'] . '/morejobs/jobshow_' . $article['id'] . '.html">' . $article['title'] . '</a></p>
						<p>投递职位：' . $article_jobs['job_name'] . '</p>
						<p>附加说明：' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>年　　龄：</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>性　　别：</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>最高学历：</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>工作经验：</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>联系方式：</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>电子邮箱：</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望地区：</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>期望薪酬：</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <p style="font-size:16px; color:red; font-weight:bold; text-align:left;">简历详情请下载附件查看</p>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
        if ($resume['default_resume'] == 1) {
            $content = $attachment_content;
            $sql = "select * from " . table("resume_attachment") . " where uid = " . $_SESSION['uid'] . " limit 1";
            $attachment = $db->getone($sql);
            $attachment_arr[0]['path'] = '..' . $attachment['path'] . $attachment['file_name'];
            $attachment_arr[0]['name'] = $attachment['file_name'];
        }
        $success = smtp_mail($email, $resume['fullname'] . '-应聘' . $article_jobs['job_name'], $content, $from_email, '教师招聘网', $attachment_arr);
    }
    if (empty($success)) {
        exit("repeat");
    } else {
        $addarr['resume_id'] = $resumeid;
        $addarr['personal_uid'] = intval($_SESSION['uid']);
        $addarr['resume_name'] = $resume['title'];
        $addarr['article_id'] = $article_id;
        $addarr['article_job_id'] = $job;
        $addarr['title'] = $article['title'];
        $addarr['apply_addtime'] = time();
        $addarr['personal_look'] = 1;
        $addarr['to_email'] = $email;
        if (inserttable(table('jiaoshi_article_apply'), $addarr)) {
            $sql = "select * from " . table('jiaoshi_statistics_all') . " where name = 'article_apply' LIMIT 1";
            $article_apply = $db->getone($sql);
            $setsqlarr1['total_count'] = intval($article_apply['total_count']) + 1;
            $setsqlarr1['new_count'] = intval($article_apply['new_count']) + 1;
            $wheresql1 = " name='article_apply' ";
            updatetable(table('jiaoshi_statistics_all'), $setsqlarr1, $wheresql1);

            $date = date('Y-m-d', time());
            $sql = "select * from " . table('jiaoshi_statistics_day') . " where name = 'article_apply' and date = '" . $date . "' LIMIT 1";
            $article_apply = $db->getone($sql);
            if (!empty($article_apply)) {
                $setsqlarr2['web_count'] = intval($article_apply['web_count']) + 1;
                $wheresql2 = " name='article_apply' and date='" . $date . "' ";
                updatetable(table('jiaoshi_statistics_day'), $setsqlarr2, $wheresql2);
            } else {
                $setsqlarr2['web_count'] = 1;
                $setsqlarr2['name'] = 'article_apply';
                $setsqlarr2['date'] = $date;
                $setsqlarr2['count'] = 0;
                inserttable(table('jiaoshi_statistics_day'), $setsqlarr2);
            }
            write_memberslog($_SESSION['uid'], 2, 1301, $_SESSION['username'], "投递了简历，简章:{$article['title']}");
        }
        exit("ok");
    }
}
?>
