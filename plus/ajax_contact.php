<?php

/*
 * 74cms ajax 联系方式
 * ============================================================================
 * 版权所有: 骑士网络，并保留所有权利。
 * 网站地址: http://www.74cms.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
define('IN_QISHI', true);
require_once(dirname(dirname(__FILE__)) . '/include/plus.common.inc.php');
$act = !empty($_GET['act']) ? trim($_GET['act']) : '';
if ($act == 'jobs_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showjobcontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showjobcontact'] == '1') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<div class=\"log_box\"><div class=\"log_tip\"><div>个人注册并登录后才能查看企业的联系方式，现在<a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[立即登录]</a>或者<a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></div></div></div>";
            }
        } elseif ($_CFG['showjobcontact'] == '2') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $val = $db->getone("select uid from " . table('resume') . " where uid='{$_SESSION['uid']}' LIMIT 1");
                if (!empty($val)) {
                    $show = true;
                } else {
                    $show = false;
                    $html = "<div class=\"log_box\"><div class=\"log_tip\"><div>您没有发布简历或者简历无效，发布简历后才可以查看联系方式。<a href=\"" . get_member_url($_SESSION['utype'], true) . "personal_resume.php?act=resume_list\">[查看我的简历]</a></div></div></div>";
                }
            } else {
                $show = false;
                $html = "<div class=\"log_box\"><div class=\"log_tip\"><div>个人注册并登录后才能查看企业的联系方式，现在<a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[立即登录]</a>或者<a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></div></div></div>";
            }
        }
        if ($show) {
            $sql = "select * from " . table('jobs_contact') . " where pid='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_job'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $ul = "<div class=\"contact_con\">";
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=jobs_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=jobs_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 电 话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=jobs_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $ull = "<div class=\"tip\"><div>提示：在应聘过程中用人单位以任何名义向应聘者收取费用都属于违法内容！</div></div></div>";
                $html = $ul . $contact . $telephone . $email . $ull;
            } else {
                $ul = "<div class=\"contact_con\">";
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：{$val['contact']}</p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联系电话：{$val['telephone']}</p>" : "<p>联系电话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：{$val['email']}</p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $ull = "<div class=\"tip\"><div>提示：在应聘过程中用人单位以任何名义向应聘者收取费用都属于违法内容！</div></div></div>";
                $html = $ul . $contact . $telephone . $email . $ull;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
} elseif ($act == 'company_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showjobcontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showjobcontact'] == '1') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<p>个人请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[登录]</a> 后查看联系方式，如果您还没有帐号，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></p>";
            }
        } elseif ($_CFG['showjobcontact'] == '2') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $val = $db->getone("select uid from " . table('resume') . " where uid='{$_SESSION['uid']}' LIMIT 1");
                if (!empty($val)) {
                    $show = true;
                } else {
                    $show = false;
                    $html = "<p>您没有发布简历或者简历无效，发布简历后才可以查看联系方式。<a href=\"" . get_member_url($_SESSION['utype'], true) . "personal_resume.php?act=resume_list\">[查看我的简历]</a></p>";
                }
            } else {
                $show = false;
                $html = "<p>个人请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[登录]</a> 后查看联系方式，如果您还没有帐号，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></p>";
            }
        }
        if ($show) {
            $sql = "select contact,contact_show,telephone,telephone_show,email,email_show,address,address_show,website FROM " . table('company_profile') . " where id='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_com'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=company_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 人：企业设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=company_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 电 话：企业设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=company_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系邮箱：企业设置不对外公开</li>";
                $ull = "</ul>";
                $html = $ul . $contact . $telephone . $email . $ull;
            } else {
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：{$val['contact']}</li>" : "<li>联 系 人：企业设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联系电话：{$val['telephone']}</li>" : "<li>联系电话：企业设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：{$val['email']}</li>" : "<li>联系邮箱：企业设置不对外公开</li>";
                $ull.="</ul>";
                $html = $ul . $contact . $telephone . $email . $ull;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
} elseif ($act == 'resume_contact') {
    $id = intval($_GET['id']);
    $show = false;
    if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '1' && $_CFG['showapplycontact'] == '1') {
        $has = $db->getone("select * from " . table('personal_jobs_apply') . " where company_uid=" . intval($_SESSION['uid']) . " and resume_id=" . $id);
        if ($has['did'] > 0) {
            $show = true;
        }
    }
    if ($show == false) {
        if ($_CFG['showresumecontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showresumecontact'] == '1') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '1') {
                $show = true;
            } else {
                $show = false;
                $html = "<div class=\"btnIsLogin\"><a class=\"download\" href=\"javascript:void(0);\">查看联系方式</a></div>";
            }
        } elseif ($_CFG['showresumecontact'] == '2') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '1') {
                $sql = "select did from " . table('company_down_resume') . " WHERE company_uid = {$_SESSION['uid']} AND resume_id='{$id}' LIMIT 1";
                $info = $db->getone($sql);
                if (!empty($info)) {
                    $show = true;
                } else {
                    $show = false;
                    $html = "<div class=\"btnIsLogin\"><a class=\"download\" href=\"javascript:void(0);\">查看联系方式</a></div>";
                }
            } else {
                $show = false;
                $html = "<div class=\"btnIsLogin\"><a class=\"download\" href=\"javascript:void(0);\">查看联系方式</a></div>";
            }
        }
    }

    if ($show) {
        $val = $db->getone("select fullname,telephone,email from " . table('resume') . " WHERE  id='{$id}'  LIMIT 1");

        if ($_CFG['contact_img_resume'] == '2') {
            $token = md5($val['fullname'] . $id . $val['telephone']);
            $html = "<ul>";
            $html.="<li>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=resume_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>";
            $html.="<li>联系电话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=resume_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>";
            $html.="<li>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=resume_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>";
            $html.="</ul>";
        } else {
            $html = "<p><span>手机：</span><label>" . $val['telephone'] . "</label></p>";
            $html.="<p><span>邮箱：</span><label>" . $val['email'] . "</label></p>";
        }
        exit($html);
    } else {
        exit($html);
    }
}
//3.4
elseif ($act == 'course_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showcoursecontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showcoursecontact'] == '1') {
            //登录后，查看权限
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<p>请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[登录]</a> 后查看联系方式，如果您还没有帐号，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></p>";
            }
        }
        if ($show) {
            $sql = "select * from " . table('course_contact') . " where pid='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_course'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=course_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 人：机构设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=course_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 电 话：机构设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=course_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系邮箱：机构设置不对外公开</li>";
                $ull = "</ul>";
                $html = $ul . $contact . $telephone . $email . $ull;
            } else {
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：{$val['contact']}</li>" : "<li>联 系 人：机构设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联系电话：{$val['telephone']}</li>" : "<li>联系电话：机构设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：{$val['email']}</li>" : "<li>联系邮箱：机构设置不对外公开</li>";
                $ull = "</ul>";
                $html = $ul . $contact . $telephone . $email . $ull;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
} elseif ($act == 'train_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showcoursecontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showcoursecontact'] == '1') {
            //需要注意
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<p>请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[登录]</a>  后查看联系方式，如果您没有您没有帐号，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></div>";
            }
        }
        if ($show) {
            $sql = "select contact,contact_show,telephone,telephone_show,email,email_show,address,address_show,website FROM " . table('train_profile') . " where id='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_train'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=train_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 人：机构设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=train_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 电 话：机构设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=train_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系邮箱：机构设置不对外公开</li>";
                $address = $val['address_show'] == '1' ? "<li>联系地址：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=train_contact&type=4&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系地址：机构设置不对外公开</li>";
                $website.="<li>机构网址：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=train_contact&type=5&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>";
                $ull = "</ul>";
                $html = $ul . $contact . $telephone . $email . $address . $website . $ull;
            } else {
                $ul = "<ul>";
                $contact = $val['contact_show'] == '1' ? "<li>联 系 人：{$val['contact']}</li>" : "<li>联 系 人：机构设置不对外公开</li>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联系电话：{$val['telephone']}</li>" : "<li>联系电话：机构设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：{$val['email']}</li>" : "<li>联系邮箱：机构设置不对外公开</li>";
                $address = $val['address_show'] == '1' ? "<li>联系地址：{$val['address']}</li>" : "<li>联系地址：机构设置不对外公开</li>";
                $website = "<li>机构网址：{$val['website']}</li>";
                $ull.="</ul>";
                $html = $ul . $contact . $telephone . $email . $address . $website . $ull;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
} elseif ($act == 'teacher_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showcoursecontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showcoursecontact'] == '1') {
            //需要注意
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<p>请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">[登录]</a> 后查看联系方式，如果您没有您没有帐号，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">[免费注册]</a></p>";
            }
        }
        if ($show) {
            $sql = "select telephone,telephone_show,email,email_show,address,address_show,qq,qq_show,website FROM " . table('train_teachers') . " where id='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_train'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $ul = "<ul>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=teacher_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联 系 电 话：讲师设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=teacher_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系邮箱：讲师设置不对外公开</li>";
                $address = $val['address_show'] == '1' ? "<li>联系地址：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=teacher_contact&type=4&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></li>" : "<li>联系地址：讲师设置不对外公开</li>";
                $ull = "</ul>";
                $html = $ul . $contact . $telephone . $email . $address . $ull;
            } else {
                $ul = "<ul>";
                $telephone = $val['telephone_show'] == '1' ? "<li>联系电话：{$val['telephone']}</li>" : "<li>联系电话：讲师设置不对外公开</li>";
                $email = $val['email_show'] == '1' ? "<li>联系邮箱：{$val['email']}</li>" : "<li>联系邮箱：讲师设置不对外公开</li>";
                $address = $val['address_show'] == '1' ? "<li>联系地址：{$val['address']}</li>" : "<li>联系地址：讲师设置不对外公开</li>";
                $ull.="</ul>";
                $html = $ul . $contact . $telephone . $email . $address . $ull;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
}
//高级职位
elseif ($act == 'hunterjobs_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showhunterjobcontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showhunterjobcontact'] == '1') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<p>个人会员请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">登录</a>  查看联系方式！如果您不是个人会员，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">免费注册</a> 成为个人会员！</p>";
            }
        }
        //是否创建高级经理人简历
        elseif ($_CFG['showhunterjobcontact'] == '2') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $val = $db->getone("select uid from " . table('manager_resume') . " where uid='{$_SESSION['uid']}' and complete=1 and audit=1 LIMIT 1");
                if (!empty($val)) {
                    $show = true;
                } else {
                    $show = false;
                    $html = "<p>您没有发布经理人简历或者简历无效，发布经理人简历后才可以查看联系方式。<a href=\"" . get_member_url($_SESSION['utype'], true) . "personal_managerresume.php?act=resume_list\">[查看我的经理人简历]</a></p>";
                }
            } else {
                $show = false;
                $html = "<p>个人会员请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">登录</a>  查看联系方式！如果您不是个人会员，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">免费注册</a> 成为个人会员！</p>";
            }
        }

        if ($show) {
            $sql = "select telephone,telephone_show,email,email_show,address,address_show,contact,contact_show,qq,qq_show from " . table('hunter_jobs') . " where id='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_hunterjob'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 电 话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $address = $val['address_show'] == '1' ? "<p>联系地址：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=4&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系地址：企业设置不对外公开</p>";
                $qq = $val['qq_show'] == '1' ? "<p>联系Q Q： <img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=5&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系Q Q：企业设置不对外公开 </p>";
                $html = $contact . $telephone . $email . $address . $qq;
            } else {
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：{$val['contact']}</p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联系电话：{$val['telephone']}</p>" : "<p>联系电话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：{$val['email']}</p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $address = $val['address_show'] == '1' ? "<p>联系地址：{$val['address']}</p>" : "<p>联系地址：企业设置不对外公开</p>";
                $qq = $val['qq_show'] == '1' ? "<p>联系Q Q：{$val['qq']}</p>" : "<p>联系Q Q：企业设置不对外公开</p>";
                $html = $contact . $telephone . $email . $address . $qq;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
} elseif ($act == 'hunter_contact') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $show = false;
        if ($_CFG['showhunterjobcontact'] == '0') {
            $show = true;
        } elseif ($_CFG['showhunterjobcontact'] == '1') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $show = true;
            } else {
                $show = false;
                $html = "<div class=\"hunter_log\">个人会员请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">登录</a>  查看联系方式！如果您不是个人会员，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">免费注册</a> 成为个人会员！</div>";
            }
        }
        //是否创建高级简历
        elseif ($_CFG['showhunterjobcontact'] == '2') {
            if ($_SESSION['uid'] && $_SESSION['username'] && $_SESSION['utype'] == '2') {
                $val = $db->getone("select uid from " . table('resume') . " where uid='{$_SESSION['uid']}' and complete=1 and audit=1 and talent=2 LIMIT 1");
                if (!empty($val)) {
                    $show = true;
                } else {
                    $show = false;
                    $html = "<p>您没有发布高级简历或者简历无效，发布高级简历后才可以查看联系方式。<a href=\"" . get_member_url($_SESSION['utype'], true) . "personal_managerresume.php?act=resume_list\">[查看我的经理人简历]</a></p>";
                }
            } else {
                $show = false;
                $html = "<p>个人会员请 <a href=\"" . url_rewrite('QS_login', array('url' => $_SERVER['HTTP_REFERER']), false, NULL, false) . "\">登录</a>  查看联系方式！如果您不是个人会员，请先 <a href=\"" . $_CFG['main_domain'] . "user/user_reg.php\">免费注册</a> 成为个人会员！</p>";
            }
        }

        if ($show) {
            $sql = "select telephone,telephone_show,email,email_show,address,address_show,contact,contact_show,qq,qq_show from " . table('hunter_jobs') . " where id='{$id}' LIMIT 1";
            $val = $db->getone($sql);
            if ($_CFG['contact_img_hunterjob'] == '2') {
                $token = md5($val['contact'] . $id . $val['telephone']);
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=1&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联 系 电 话：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=2&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联 系 电 话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=3&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $address = $val['address_show'] == '1' ? "<p>联系地址：<img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=4&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系地址：企业设置不对外公开</p>";
                $qq = $val['qq_show'] == '1' ? "<p>联系Q Q： <img src=\"{$_CFG['main_domain']}plus/contact_img.php?act=hunterjobs_contact&type=5&id={$id}&token={$token}\"  border=\"0\" align=\"absmiddle\"/></p>" : "<p>联系Q Q：企业设置不对外公开 </p>";
                $html = $contact . $telephone . $email . $address . $qq;
            } else {
                $contact = $val['contact_show'] == '1' ? "<p>联 系 人：{$val['contact']}</p>" : "<p>联 系 人：企业设置不对外公开</p>";
                $telephone = $val['telephone_show'] == '1' ? "<p>联系电话：{$val['telephone']}</p>" : "<p>联系电话：企业设置不对外公开</p>";
                $email = $val['email_show'] == '1' ? "<p>联系邮箱：{$val['email']}</p>" : "<p>联系邮箱：企业设置不对外公开</p>";
                $address = $val['address_show'] == '1' ? "<p>联系地址：{$val['address']}</p>" : "<p>联系地址：企业设置不对外公开</p>";
                $qq = $val['qq_show'] == '1' ? "<p>联系Q Q：{$val['qq']}</p>" : "<p>联系Q Q：企业设置不对外公开</p>";
                $html = $contact . $telephone . $email . $address . $qq;
            }
            exit($html);
        } else {
            exit($html);
        }
    }
}
?>