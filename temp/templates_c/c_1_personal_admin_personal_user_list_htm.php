<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-22 10:21 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript" src="js/jquery.userinfotip-min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#ButDel").QSdialog({
	DialogTitle:"请选择",
	DialogContent:"#GetDelInfo",
	DialogContentType:"id",
	DialogAddObj:"#DelSel",	
	DialogWidth:"500",
	DialogAddType:"html"	
	});
});
</script>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
  <div class="seltpye_x">
		<div class="left">验证类型</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("verification:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['verification'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("verification:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['verification'] == "1"): ?>class="select"<?php endif; ?>>邮箱已验证</a>
		<a href="<?php echo $this->_run_modifier("verification:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['verification'] == "2"): ?>class="select"<?php endif; ?>>邮箱未验证</a>
		<a href="<?php echo $this->_run_modifier("verification:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['verification'] == "3"): ?>class="select"<?php endif; ?>>手机已验证</a>
		<a href="<?php echo $this->_run_modifier("verification:4", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['verification'] == "4"): ?>class="select"<?php endif; ?>>手机未验证</a>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
<div class="seltpye_x">
		<div class="left">注册时间</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("settr:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("settr:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("settr:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("settr:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
		<a href="<?php echo $this->_run_modifier("settr:180", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "180"): ?>class="select"<?php endif; ?>>半年内</a>
		<a href="<?php echo $this->_run_modifier("settr:360", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "360"): ?>class="select"<?php endif; ?>>一年内</a>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
  <form id="form1" name="form1" method="post" action="?act=delete_user">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td  width="20%" class="admin_list_tit admin_list_first" >
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>用户名</label></td>
      <td  width="20%" class="admin_list_tit">email</td>
	  <td  align="center"   class="admin_list_tit">手机</td>
      <td align="center"   class="admin_list_tit">注册时间</td>
	  <td    align="center"   class="admin_list_tit">注册IP</td>
      <td   align="center"   class="admin_list_tit">最后登录时间</td>
	  
      <td width="13%"  align="center"  class="admin_list_tit" >操作</td>
    </tr>
	<?php if (count((array)$this->_vars['member'])): foreach ((array)$this->_vars['member'] as $this->_vars['list']): ?>
      <tr>
      <td class="admin_list admin_list_first">
        <input name="tuid[]" type="checkbox"   value="<?php echo $this->_vars['list']['uid']; ?>
"/><?php echo $this->_vars['list']['username']; ?>
</td>
        <td class="admin_list"><?php echo $this->_vars['list']['email']; ?>

		<?php if ($this->_vars['list']['email_audit'] == "1"): ?><span style="color:#009900">[验]</span><?php endif; ?>
		</td>
		<td align="center" class="admin_list">
		 <?php if ($this->_vars['list']['mobile']):  echo $this->_vars['list']['mobile'];  else: ?>未填写<?php endif; ?>
		<?php if ($this->_vars['list']['mobile_audit'] == "1"): ?><span style="color:#009900">[验]</span><?php endif; ?>
		</td>   
        <td align="center" class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['reg_time'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>
		</td>
        <td align="center" class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['reg_ip'], 'default', 'plugin', 1, " - - - -"); ?>
</td>
		<td align="center" class="admin_list">
		<?php if ($this->_vars['list']['last_login_time']): ?>
		<?php echo $this->_run_modifier($this->_vars['list']['last_login_time'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php else: ?>
		从未登录
		<?php endif; ?>
		</td>
		    
        <td align="center" class="admin_list">
		<a href="?act=user_edit&tuid=<?php echo $this->_vars['list']['uid']; ?>
">修改</a>
		  &nbsp;
		<a href="?act=management&id=<?php echo $this->_vars['list']['uid']; ?>
"  target="_blank" class="userinfo"  id="<?php echo $this->_vars['list']['uid']; ?>
">管理</a>
		</td>
      </tr>
      <?php endforeach; endif; ?>
    </table>
	<span id="DelSel"></span>
  </form>
	<?php if (! $this->_vars['member']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
         <input type="button" name="ButAdd" value="添加会员" class="admin_submit"  onclick="window.location.href='?act=members_add'"/>
		<input type="button" name="ButDel"  id="ButDel" value="删除会员" class="admin_submit"/>
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "用户名"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="用户名">用户名</li>
														<li id="2" title="UID">UID</li>
														<li id="3" title="email">email</li>
														<li id="4" title="手机号">手机号</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="members_list" />
				<input type="submit" name="" class="sbt" id="sbt" value="搜索"/>
				</div>
				<div class="clear"></div>
		  </div>
		  </form>
		  <script type="text/javascript">$(document).ready(function(){showmenu("#key_type_cn","#sehmenu","#key_type");});	</script>
	    </td>
      </tr>
  </table>
<div class="page link_bk"><?php echo $this->_vars['page']; ?>
</div>
	<div id="GetDelInfo" style="display: none" >
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="6" >
		<tr>
		  <td width="30" height="30">&nbsp;</td>
		  <td height="30"><strong  style="color:#0066CC; font-size:14px;">你确定要删除吗？</strong></td>
		</tr>
			  <tr>
		  <td width="27" height="25">&nbsp;</td>
		  <td><label>
			<input name="delete_user" type="checkbox" id="delete_user" value="yes" checked="checked" />
			删除此会员 <span style="color:#666666">(删除后此会员将无法登录，无法管理已发布的信息等)<span></label></td>
		</tr>
		<tr>
		  <td width="27" height="25">&nbsp;</td>
		  <td width="425"><label>
			<input name="delete_resume" type="checkbox" id="delete_resume" value="yes" checked="checked" />
			删除此会员发布的简历</label></td>
		</tr>
		<tr>
		  <td height="25">&nbsp;</td>
		  <td><input type="submit" name="delete" value="确定删除" class="admin_submit"/></td>
		  </tr>
	  </table>
	  </div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>