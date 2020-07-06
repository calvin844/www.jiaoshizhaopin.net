<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:28 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("category/admin_category_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
系统默认分类不能删除！<br />
删除分类组将同时删除此组下的所有分类。
</p>
</div>
  <form id="form1" name="form1" method="post" action="?act=del_group">
  <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan"  >
    <tr>
      <td height="26" class="admin_list_tit admin_list_first" >
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>分类组名称</label></td>
	  <td  class="admin_list_tit">调用名</td>
	  <td   align="center"  class="admin_list_tit">类型</td>
      <td width="20%"   class="admin_list_tit">操作</td>
    </tr>
	<?php if (count((array)$this->_vars['group'])): foreach ((array)$this->_vars['group'] as $this->_vars['li']): ?>
     <tr>
      <td   class="admin_list admin_list_first" >
      <input type="checkbox" name="alias[]" value="<?php echo $this->_vars['li']['g_alias']; ?>
"  <?php if ($this->_vars['li']['g_sys'] == "1"): ?>disabled="disabled"<?php endif; ?> />
	  <a href="?act=show_category&alias=<?php echo $this->_vars['li']['g_alias']; ?>
"><?php echo $this->_vars['li']['g_name']; ?>
</a>
	  </td>
	   <td  class="admin_list">
	   <?php echo $this->_vars['li']['g_alias']; ?>

	    
</td>
			    <td align="center"  class="admin_list">
	   <?php if ($this->_vars['li']['g_sys'] == "1"): ?>
	    系统分类
	   <?php else: ?>
	   自定义分类组
	   <?php endif; ?>
 
		 </td>
      <td class="admin_list">
	  <a href="?act=show_category&alias=<?php echo $this->_vars['li']['g_alias']; ?>
">查看</a>&nbsp;&nbsp;
	  		<?php if ($this->_vars['li']['g_sys'] <> "1"): ?>
				 <a href="?act=edit_group&alias=<?php echo $this->_vars['li']['g_alias']; ?>
">修改</a>&nbsp;&nbsp;			 
			 <a onclick="return confirm('删除后无法恢复，你确定要删除吗？')" href="?act=del_group&alias=<?php echo $this->_vars['li']['g_alias']; ?>
&<?php echo $this->_vars['urltoken']; ?>
">删除</a>
		 <?php endif; ?>
		</td>
    </tr>
	 <?php endforeach; endif; ?>
	</table>
	<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
        <td>
        <input name="ButADD" type="button" class="admin_submit" id="ButADD" value="添加分类组"  onclick="window.location='?act=add_group'"/>
		<input name="ButDel" type="submit" class="admin_submit" id="ButDel"  value="删除所选" onclick="return confirm('删除分类组同时也会删除此分类组下个分类，你确定要删除吗？')"/>
		</td>
        <td width="305" align="right">
	  
	    </td>
      </tr>
  </table>
  </form>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>