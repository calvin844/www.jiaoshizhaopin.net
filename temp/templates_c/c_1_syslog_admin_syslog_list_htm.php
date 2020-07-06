<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">

  

<div class="pagetit">
	<div class="ptit"><?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
您可以通过错误日志查看系统运行错误信息。 
</p>
</div>
<div class="seltpye_x">
		<div class="left">日志类型</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("type_id:,l_type:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['l_type'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("type_id:,l_type:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['l_type'] == "1"): ?>class="select"<?php endif; ?>>MYSQL</a>
		<a href="<?php echo $this->_run_modifier("type_id:,l_type:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['l_type'] == "2"): ?>class="select"<?php endif; ?>>MAIL</a> 
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
</div>
 <div class="seltpye_x">
		<div class="left">创建时间</div>	
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
  <form id="form1" name="form1" method="post" action="?act=del_syslog">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
    <tr>
      <td height="26" width="90"  class="admin_list_tit admin_list_first" >
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>类型</label></td>
      <td width="120"  align="center"   class="admin_list_tit">创建时间</td>
      <td width="100" align="center"  class="admin_list_tit"> IP </td>
      <td width="300"     class="admin_list_tit">文件</td>
      <td   class="admin_list_tit" >错误描述</td>
    </tr>
	  <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
      <tr>
      <td  class="admin_list admin_list_first">
        <input name="id[]" type="checkbox" id="id" value="<?php echo $this->_vars['li']['l_id']; ?>
"/>
		<?php echo $this->_vars['li']['l_type_name']; ?>

	 
	    </td>
		 <td  align="center" class="admin_list" >
		 <?php echo $this->_run_modifier($this->_vars['li']['l_time'], 'date_format', 'plugin', 1, "%Y-%m-%d %H:%M"); ?>

	    </td>
        <td align="center"  class="admin_list"><?php echo $this->_vars['li']['l_ip']; ?>
</td>
        <td  class="admin_list vtip"   title="<?php echo $this->_vars['li']['l_page']; ?>
"><?php echo $this->_vars['li']['l_page']; ?>
</td>
        <td   class="admin_list vtip"  title="<?php echo $this->_vars['li']['l_str']; ?>
"><?php echo $this->_vars['li']['l_str']; ?>
</td>
      </tr>
      <?php endforeach; endif; ?>
    </table>
	<?php if (! $this->_vars['list']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>	
<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
        <td><input name="ButDel" type="submit" class="admin_submit" id="ButDel"  value="删除所选"/>
		</td>
        <td width="305" align="right">
	    </td>
      </tr>
  </table>
  </form>
<div class="page link_bk"><?php echo $this->_vars['page']; ?>
</div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>