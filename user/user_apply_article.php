<?php
/*
 * �����ʼ������¶�Ӧ������
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
					�����Ǹ��˻�Ա�ſ���Ͷ������
				</td>
		    </tr>
		</table>');
}
/* if (empty($_GET['email'])) {
  exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
  <tr>
  <td width="20" align="right"></td>
  <td class="ajax_app">
  �ü�������Ͷ�����䣬�밴�ռ����ϵ���Ƹ����Ͷ�ݼ�����
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
					�����˺Ŵ�����ͣ״̬������ϵ����Ա��Ϊ��������в�����
				</td>
		    </tr>
		</table>');
}
if ($act == "app") {
    $id = isset($_GET['id']) ? $_GET['id'] : exit("id ��ʧ");
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
    //���￪ʼ�����ʼ����ȼ������Ƿ������䣬û����������ʾֱ�Ӵ�绰��ѯ���¼�����վ
    $article = app_get_article($id);
    if (empty($article)) {
        exit('<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall">
			    <tr>
					<td width="20" align="right"></td>
					<td class="ajax_app">
						Ͷ����ʧ�ܣ�
					</td>
			    </tr>
			</table>');
    }
    //�ټ������Ƿ���ڣ�����������ʾ�����Ϣ
    $resume_list = get_auditresume_list($_SESSION['uid']);
    if (empty($resume_list)) {
        $str = "<a href=\"" . get_member_url(2, true) . "personal_resume.php?act=resume_list\">[�鿴�ҵļ���]</a>";
        exit('<p style="text-align:center;font-size:16px;padding:20px;">
					Ͷ����ʧ�ܣ���û����д�������߼������ɼ� ' . $str . '
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
        //���������������
        var app_max = "<?php echo $_CFG['apply_jobs_max'] ?>";
        var app_today = "<?php echo get_now_applyjobs_num($_SESSION['uid']) ?>";
        $(".ajax_app_tip > span:eq(0)").html(app_max);
        $(".ajax_app_tip > span:eq(1)").html(app_today);
        $(".ajax_app_tip > span:eq(2)").html(app_max - app_today);
        //��֤
        $("#ajax_app").click(function() {
            /*if (app_max-app_today==0 || app_max-app_today<0 )
             {
             alert("������Ͷ���������Ѿ�����������ƣ�");
             }
             else if ($("#app :checkbox[checked]").length>(app_max-app_today))
             {
             alert("�����컹����Ͷ��"+(app_max-app_today)+"����������ѡְλ������������ƣ�");
             }
             else if ($("#app :checkbox[checked]").length==0)
             {
             alert("��ѡ��Ͷ�ݵ�ְλ��");
             }
             else if ($("#app :radio[checked]").length==0)
             {
             alert("��ѡ����ļ�����");
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
            //���﷢�ʼ�
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
                    alert("��Ͷ�ݹ���ְλ�������ظ�Ͷ��");
                }
                else
                {
                    $("#app").show();
                    $("#notice").show();
                    $("#waiting").hide();
                    $("#app_ok").hide();
                    alert("Ͷ��ʧ�ܣ�" + data);
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
            <td width="120" align="right">ҪͶ�ݵ����䣺</td>
            <td class="ajax_app">
                <input type="text" name="email" id="email" value="<?php echo $_GET['email']; ?>" style="width:295px;padding:6px 4px;border:1px solid #bbb;">
            </td>
        </tr>
        <?php if (!empty($article_jobs_list)): ?>
            <tr>
                <td width="120" align="right">ѡ��ְλ��</td>
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
            <td width="120" align="right">ѡ�������</td>
            <td>
                <ul>
                    <?php
                    foreach ($resume_list as $resume) {
                        ?>
                        <li><label><input name="resumeid" checked="checked" type="radio" value="<?php echo $resume['id'] ?>"  /><?php echo $resume['title'] ?></label>&nbsp;&nbsp;
                            <a href="<?php echo url_rewrite('QS_resumeshow', array('id' => $resume['id'])) ?>" target="_blank">[Ԥ��]</a>
                            <?php
                        }
                        ?>
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td width="120" align="right">����˵����</td>
            <td>
                <textarea id="note" style="width:295px; height: 60px; border:1px solid #ccc;" name="note"></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="button" name="Submit"  id="ajax_app" class="but130lan" value="Ͷ��" />
            </td>
        </tr>
    </table>
    <table id="notice" width="100%" border="0" style="border-top:1px #CCCCCC dotted;background-color: #EEEEEE; line-height: 230%;padding: 15px; margin-top: 10px; ">
        <tbody><tr>
                <td class="ajax_app_tip">ϵͳ�������ļ������͵���Ӧ����ҵ��λ
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="5" cellpadding="0" id="waiting"  style="display:none">
        <tr>
            <td align="center" height="60"><img src="<?php echo $_CFG['site_template'] ?>images/30.gif"  border="0"/></td>
        </tr>
        <tr>
            <td align="center" >���Ժ�...</td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableall" id="app_ok" style="display:none">
        <tr>
            <td width="140" align="right"><img height="100" src="<?php echo $_CFG['site_template'] ?>images/14.gif" /></td>
            <td>
                <strong style="font-size:14px ; color:#0066CC;margin-left:20px">Ͷ�ݳɹ�!</strong>
                <div style="border-top:1px #CCCCCC solid; line-height:180%; margin-top:10px; padding-top:10px; height:50px;margin-left:20px"  class="dialog_closed">
                    <a href="<?php echo get_member_url(2, true) ?>personal_apply.php?act=apply_jobs" >�鿴��Ͷ�ݵ�ְλ</a><br />
                    <a href="javascript:void(0)"  class="DialogClose">Ͷ�����</a>
                </div>
            </td>

        </tr>
        <tr>
            <td width="140" align="right"><img height="100" src="<?php echo $_CFG['site_template'] ?>images/m_weixin.jpg" /></td>
            <td>
                <strong style="font-size:14px ; color:#0066CC;margin-left:20px">�󶨹��ںţ���ȡ����ְλ��̬��</strong>
            </td>
        </tr>
    </table>
    <?php
} elseif ($act == "app_save") {
    $article_id = isset($_POST['article_id']) ? $_POST['article_id'] : exit("������");
    $resumeid = isset($_POST['resumeid']) ? intval($_POST['resumeid']) : exit("������");
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $job = isset($_POST['article_job']) ? trim($_POST['article_job']) : exit("������");
    $note = isset($_POST['note']) ? trim($_POST['note']) : "";

    $sql = "select * from " . table("jiaoshi_article_jobs") . " where id = " . intval($job);
    $article_jobs = get_mem_cache($sql, "getone");
    if (empty($article_jobs)) {
        exit("ְλ��ʧ");
    }
    $article = app_get_article($article_id);
    if (empty($article)) {
        exit("���¶�ʧ");
    }
    $resume = get_resume_basic($_SESSION['uid'], $resumeid);
    if (empty($resume)) {
        exit("������ʧ");
    }
    $resume_work = get_resume_work($_SESSION['uid'], $resumeid);
    $age = intval($resume['birthdate']) > 0 ? date('Y', time()) - $resume['birthdate'] : "δ��д";
    if (!check_article_apply($_SESSION['uid'], $job, $resumeid)) {
        $from_email = $resume['email'];
        $word_content = "";
        $attachment_arr = "";
        if (!empty($resume_work)) {
            foreach ($resume_work as $rw) {
                $word_content .= '<div style="width:100%;float:left;display:block;">';
                $word_content .= '<p>' . $rw['startyear'] . '.' . $rw['startmonth'] . '��' . $rw['endyear'] . '.' . $rw['endmonth'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['jobs'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['companyname'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['achievements'] . '</p>';
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
					font-family: "΢���ź�";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="��ʦ��Ƹ��" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">���յ�һ�����ԡ���ʦ��Ƹ�����ļ���Ͷ��</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>��ʦ��Ƹ�����й����Ľ�ʦ��Ƹרҵ��վ������<a href="' . $_CFG['main_domain'] . '/user/reg.php">ע��</a>��ѷ�����ʦְλ�������ʺţ���<a href="' . $_CFG['main_domain'] . '/user/login.php">��¼</a>��</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>������Ͷ�����ԣ�<a href="' . $_CFG['main_domain'] . '/morejobs/jobshow_' . $article['id'] . '.html">' . $article['title'] . '</a></p>
						<p>Ͷ��ְλ��' . $article_jobs['job_name'] . '</p>
						<p>����˵����' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ꡡ���䣺</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ԡ�����</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>���ѧ����</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�������飺</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>��ϵ��ʽ��</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>�������䣺</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����������</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����н�꣺</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>��������</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            ' . $word_content . '
                                                        </div>
							<div style="width:100%;float:left;display:block;">
                                                            <div class="jl_peo_infor_li"><b>���ҽ���</b></div>
                                                            <div class="jl_peo_infor_li"></div>
                                                            <p style="width:100%;float:left;display:block;">
                                                            ' . $resume['specialty'] . '
                                                            </p>
                                                            <div class="jl_peo_infor_li"></div>
                                                        </div>
							<div style="clear:both;"></div>
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $_CFG['main_domain'] . '/ProfileBank/ShowResume.shtml?ID=' . $resume['id'] . '" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">�鿴��������</a>
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
					font-family: "΢���ź�";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="��ʦ��Ƹ��" class="logo" href="' . $_CFG['main_domain'] . '"><img src="' . $_CFG['main_domain'] . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">���յ�һ�����ԡ���ʦ��Ƹ�����ļ���Ͷ��</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>��ʦ��Ƹ�����й����Ľ�ʦ��Ƹרҵ��վ������<a href="' . $_CFG['main_domain'] . '/user/reg.php">ע��</a>��ѷ�����ʦְλ�������ʺţ���<a href="' . $_CFG['main_domain'] . '/user/login.php">��¼</a>��</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>������Ͷ�����ԣ�<a href="' . $_CFG['main_domain'] . '/morejobs/jobshow_' . $article['id'] . '.html">' . $article['title'] . '</a></p>
						<p>Ͷ��ְλ��' . $article_jobs['job_name'] . '</p>
						<p>����˵����' . utf8_to_gbk($note) . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
							<div style="width:100%;float:left;display:block;">
								<div class="jl_peo_name">
									' . $resume['fullname'] . '
								</div>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ꡡ���䣺</label><span>' . $age . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�ԡ�����</label><span>' . $resume['sex_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>���ѧ����</label><span>' . $resume['education_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>�������飺</label><span>' . $resume['experience_cn'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>��ϵ��ʽ��</label><span>' . $resume['telephone'] . '</span>
							</div>
							<div title="33.107" class="jl_peo_infor_li">
								<label>�������䣺</label><span>' . $resume['email'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����������</label><span>' . $resume['district_cn'] . '</span>
							</div>
							<div class="jl_peo_infor_li">
								<label>����н�꣺</label><span>' . $resume['wage_cn'] . '</span>
							</div>
							<div style="width:100%;float:left;display:block;">
                                                            <p style="font-size:16px; color:red; font-weight:bold; text-align:left;">�������������ظ����鿴</p>
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
        $success = smtp_mail($email, $resume['fullname'] . '-ӦƸ' . $article_jobs['job_name'], $content, $from_email, '��ʦ��Ƹ��', $attachment_arr);
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
            write_memberslog($_SESSION['uid'], 2, 1301, $_SESSION['username'], "Ͷ���˼���������:{$article['title']}");
        }
        exit("ok");
    }
}
?>
