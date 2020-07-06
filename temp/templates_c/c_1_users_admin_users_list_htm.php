<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:37 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"><?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("users/admin_users_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>提示：</h2>
<p>
通过管理员设置，您可以进行编辑管理员资料、权限、密码以及删除管理员等操作；
</p>
</div> 
  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
    <tr>
      <td class="admin_list_tit admin_list_first">用户名</td>
      <td  class="admin_list_tit">头衔</td>
      <td  class="admin_list_tit">创建时间</td>
      <td  class="admin_list_tit">最后登录时间</td>
      <td  class="admin_list_tit">最后登录ip</td>
      <td width="230" align="center"  class="admin_list_tit">操作</td>
    </tr>
	 <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	   <tr>
      <td  class="admin_list admin_list_first"><?php echo $this->_vars['li']['admin_name']; ?>
</td>
      <td   class="admin_list"><?php echo $this->_vars['li']['rank']; ?>
</td>
      <td   class="admin_list"><?php echo $this->_run_modifier($this->_vars['li']['add_time'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>
 </td>
      <td   class="admin_list">
	  <?php if ($this->_vars['li']['last_login_time'] == "0"): ?>
		从未
		<?php else: ?>
		<?php echo $this->_run_modifier($this->_vars['li']['last_login_time'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php endif; ?>
	  </td>
      <td   class="admin_list"><?php echo $this->_vars['li']['last_login_ip']; ?>
</td>
      <td   class="admin_list">
	  <a href="?act=loglist&adminname=<?php echo $this->_vars['li']['admin_name']; ?>
" >登录日志</a>
		&nbsp;&nbsp;
		<a href="?act=users_set&id=<?php echo $this->_vars['li']['admin_id']; ?>
">权限</a>
		&nbsp;&nbsp;
		<a href="?act=edit_users&id=<?php echo $this->_vars['li']['admin_id']; ?>
" >详情</a>
		&nbsp;&nbsp;
		<a href="?act=edit_users_pwd&id=<?php echo $this->_vars['li']['admin_id']; ?>
" >密码</a>
		<?php if ($this->_vars['admin_purview'] == "all"): ?>
		&nbsp;&nbsp;
		<a href="?act=del_users&id=<?php echo $this->_vars['li']['admin_id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('确定要删除吗？')">删除</a>
		<?php endif; ?>		
	  </td>
    </tr>
	  <?php endforeach; endif; ?>
  </table>
  <?php if ($this->_vars['admin_purview'] == "all"): ?>
   <table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>

          <input type="button" name="add" value="添加管理员" class="admin_submit"   onclick="window.location.href='?act=add_users'" />
        	
		</td>
        <td width="305" align="right">
		 
		
	    </td>
      </tr>
  </table>
  <?php endif; ?>
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