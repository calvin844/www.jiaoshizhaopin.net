<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sms/admin_sms_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
	<div class="toptip">
	<h2>提示：</h2>
		<p>短信模板请勿添加HTML。<br />
短信内容不能超过60个字符，超过将会自动删除多余字符。
</p>
		</div>
	<div class="toptit">邮件发送规则</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
       <tr>
        <td height="30" style="font-size:12px;padding-left:20px;">
		<form action="admin_mail.php?act=email_config_save" method="post"   name="form1" id="form1">
		<table width="242" border="0" cellspacing="7" cellpadding="1" style=" margin-bottom:3px;" class="link_lan">
            <tr>
              <td align="right" width="137">申请职位：</td>
              <td><a href="?act=edit_tpl&templates_name=set_applyjobs&thisname=申请职位">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">邀请面试：</td>
              <td><a href="?act=edit_tpl&templates_name=set_invite&thisname=邀请面试">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">新增订单：</td>
              <td><a href="?act=edit_tpl&templates_name=set_order&thisname=申请充值">[新增订单]</a></td>
            </tr>
            <tr>
              <td align="right">充值成功：</td>
              <td><a href="?act=edit_tpl&templates_name=set_payment&thisname=充值成功">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">修改密码：</td>
              <td><a href="?act=edit_tpl&templates_name=set_editpwd&thisname=修改密码">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">职位审核通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_jobsallow&thisname=职位审核通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">职位审核未通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_jobsnotallow&thisname=职位审核未通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">营业执照审核通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_licenseallow&thisname=营业执照审核通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">营业执照审核未通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_licensenotallow&thisname=营业执照审核未通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">开通电子地图：</td>
              <td><a href="?act=edit_tpl&templates_name=set_addmap&thisname=开通电子地图">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">简历审核通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_resumeallow&thisname=简历审核通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td align="right">简历审核未通过：</td>
              <td><a href="?act=edit_tpl&templates_name=set_resumenotallow&thisname=简历审核未通过">[编辑模板]</a></td>
            </tr>
            <tr>
              <td height="55" align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
		  </form>		  </td>
      </tr>
    </table>
  
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>