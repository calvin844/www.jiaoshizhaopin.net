<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.cat.php'); $this->register_modifier("cat", "tpl_modifier_cat",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-10 11:00 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript" src="js/jquery.userinfotip-min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#Butdelay").QSdialog({
	DialogTitle:"系统提示",
	DialogContent:"#delayset",
	DialogContentType:"id",
	DialogAddObj:"#Opdelay",	
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
		<div class="left">套餐类型</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("setmeal_id:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['setmeal_id'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<?php if (count((array)$this->_vars['setmeal'])): foreach ((array)$this->_vars['setmeal'] as $this->_vars['list']): ?>
		<a href="<?php echo $this->_run_modifier($this->_run_modifier("setmeal_id:", 'cat', 'plugin', 1, $this->_vars['list']['id']), 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['setmeal_id'] == $this->_vars['list']['id']): ?>class="select"<?php endif; ?>><?php echo $this->_vars['list']['setmeal_name']; ?>
</a>
		<?php endforeach; endif; ?>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
  <div class="seltpye_x">
		<div class="left">到期时间</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("settr:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("settr:-1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "-1"): ?>class="select"<?php endif; ?>>已经到期</a>
		<a href="<?php echo $this->_run_modifier("settr:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "3"): ?>class="select"<?php endif; ?>>三天内到期</a>
		<a href="<?php echo $this->_run_modifier("settr:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "7"): ?>class="select"<?php endif; ?>>一周内到期</a>
		<a href="<?php echo $this->_run_modifier("settr:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "30"): ?>class="select"<?php endif; ?>>一月内到期</a>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
  <form id="form1" name="form1" method="post" action="?act=meal_delay">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"   class="link_lan">
    <tr>
      <td  class="admin_list_tit admin_list_first" >
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>用户名</label></td>
	   <td  class="admin_list_tit">套餐名称</td>
      <td  class="admin_list_tit">email</td>     
      <td     class="admin_list_tit">服务开始时间</td>
	  <td     class="admin_list_tit">服务到期时间</td>
      <td    align="center"  class="admin_list_tit" >操作</td>
    </tr>
	<?php if (count((array)$this->_vars['member'])): foreach ((array)$this->_vars['member'] as $this->_vars['list']): ?>
      <tr>
      <td class="admin_list admin_list_first">
        <input name="tuid[]" type="checkbox"   value="<?php echo $this->_vars['list']['uid']; ?>
"/><?php echo $this->_vars['list']['username']; ?>

		
		<span style="color:#CCCCCC">(uid:<?php echo $this->_vars['list']['uid']; ?>
)</span>
		</td>
		 <td class="admin_list">
		<?php echo $this->_vars['list']['setmeal_name']; ?>

		</td>
 		<td class="admin_list">
		<?php echo $this->_vars['list']['email']; ?>

		<?php if ($this->_vars['list']['email_audit'] == "1"): ?><span style="color:#009900">[验]</span><?php endif; ?>
		</td>
		
       
		<td   class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['starttime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		</td>
		<td   class="admin_list">
		<?php if ($this->_vars['list']['endtime'] == "0"): ?>
		不限制	
		<?php else: ?>
			<?php if ($this->_vars['list']['endtime'] > time()): ?>
			<?php echo $this->_run_modifier($this->_vars['list']['endtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

			<?php else: ?>
			<?php echo $this->_run_modifier($this->_vars['list']['endtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

			<span style="color:#FF6600">(已到期)</span>
			<?php endif; ?>
		<?php endif; ?>
		</td>       
        <td align="center" class="admin_list">
		<a href="?act=user_edit&tuid=<?php echo $this->_vars['list']['uid']; ?>
">修改</a>
		&nbsp;
		<a href="?act=management&id=<?php echo $this->_vars['list']['uid']; ?>
"  target="_blank"  class="userinfo"  id="<?php echo $this->_vars['list']['uid']; ?>
">管理</a>
		</td>
      </tr>
      <?php endforeach; endif; ?>
    </table>
 <span id="Opdelay"></span>
  </form>
	<?php if (! $this->_vars['member']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
        <input type="button" name="Butdelay"  id="Butdelay" value="延期" class="admin_submit"/>
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
				<input name="act" type="hidden" value="meal_members" />
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
		<div style="display:none" id="delayset">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="6">
			<tr>
			  <td width="20" height="30">&nbsp;</td>
			  <td height="30"><strong  style="color:#0066CC; font-size:14px;">延长服务到期时间：</strong></td>
			</tr>
				  <tr>
			  <td width="27" height="25">&nbsp;</td>
			  <td>
				<input name="days" type="text" class="input_text_150" id="days" value="1" maxlength="3"/>&nbsp;天</td>
			</tr>
		 
			<tr>
			  <td height="25">&nbsp;</td>
			  <td>
			  <input type="submit" name="set_delay" value="确定" class="admin_submit"/>
		 </td>
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