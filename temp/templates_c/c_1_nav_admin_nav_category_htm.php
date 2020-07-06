<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:41 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("nav/admin_nav_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>提示：</h2>
<p>
系统内置分类不可以编辑或者删除<br />自定义分类调用名不可以以 “QS_”开头
</p>
</div>

		  <table width="100%" border="0" cellpadding="2" cellspacing="0" id="list" class="link_lan">
          <tr>
            <td width="15%" class="admin_list_tit admin_list_first">调用名称</td>
            <td class="admin_list_tit">分类名称</td>
            <td width="15%" align="center" class="admin_list_tit">类型</td>
            <td width="15%" align="center" class="admin_list_tit">编辑</td>
            </tr>
			<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
          <tr>
            <td    class="admin_list admin_list_first"><strong><?php echo $this->_vars['li']['alias']; ?>
</strong></td>
            <td class="admin_list">
			<?php echo $this->_vars['li']['categoryname']; ?>
			</td>
            <td align="center" class="admin_list">			
			<?php if ($this->_vars['li']['admin_set'] == "1"): ?>
			<span style="color:#FF6600">系统内置</span>
			<?php else: ?>
			自定义
			<?php endif; ?>			</td>
            <td align="center" class="admin_list">
			<?php if ($this->_vars['li']['admin_set'] != "1"): ?>
			<a href="?act=site_navigation_category_edit&alias=<?php echo $this->_vars['li']['alias']; ?>
"  >修改</a>
			&nbsp;&nbsp;&nbsp;
			<a href="?act=site_navigation_category_del&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要删除吗？')">删除</a>
			<?php else: ?>
			<span style="color:#999999">修改</span>&nbsp;&nbsp;&nbsp;
			<span style="color:#999999">删除</span>
			
			<?php endif; ?>
&nbsp;			</td>
          </tr>
		  <?php endforeach; endif; ?>
  </table>
		  <table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
 <input type="button" name="Submit22" value="新增类别" class="admin_submit"    onclick="window.location='?act=site_navigation_category_add'"/>
		</td>
        <td width="305" align="right">		
	    </td>
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