<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 10:19 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("promotion/admin_promotion_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
系统内置方案不能删除<br />
会员申请推广方案后，系统将自动开通，到期后自动取消。
</p>
</div>
 
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
          <tr>
            <td  class="admin_list_tit admin_list_first">方案名称</td>
			<td width="15%" align="center" class="admin_list_tit">积分</td>
            <td width="15%" align="center" class="admin_list_tit">状态</td>
            <td width="15%" align="center" class="admin_list_tit" >类型</td>
            <td width="15%" align="center" class="admin_list_tit">排序</td>

			
            <td width="15%" align="center" class="admin_list_tit">编辑</td>
            </tr>
			<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
			
			  <tr>
            <td  class="admin_list admin_list_first">
		 
			 <a href="?act=edit_category&id=<?php echo $this->_vars['li']['cat_id']; ?>
"><?php echo $this->_vars['li']['cat_name']; ?>
</a>
			</td>
			 <td align="center" class="admin_list">
			<?php echo $this->_vars['li']['cat_points']; ?>
/天
			</td>
            <td class="admin_list" align="center">
			<?php if ($this->_vars['li']['cat_available'] == "1"): ?>
			正常
			<?php else: ?>
			<span style="color:#999999">禁用</span>
			<?php endif; ?>
			</td>
            <td align="center" class="admin_list" >	
			
			<?php if ($this->_vars['li']['cat_type'] == "1"): ?>
			<span style="color:#FF6600">内置</span>
			<?php else: ?>
			自定义
			<?php endif; ?>					
			
			</td>
            <td align="center" class="admin_list">
			<?php echo $this->_vars['li']['cat_order']; ?>

			</td>
		 
			
            <td align="center" class="admin_list">
	 <a href="?act=edit_category&id=<?php echo $this->_vars['li']['cat_id']; ?>
">修改</a>
	 
	  <?php if ($this->_vars['li']['cat_type'] == "1"): ?>
	  <span style="color:#999999">删除</span>
	  <?php else: ?>
		 <a href="?act=del_ad&id=<?php echo $this->_vars['li']['id']; ?>
"  onclick="return confirm('你确定要删除吗？')">删除</a>
			<?php endif; ?>	
				</td>
            </tr>
			<?php endforeach; endif; ?>
  </table>
  <br />
<br />
<br />

<!--		<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
      <td>
       <input type="button" name="Submit22" value="新增方案" class="admin_submit"    onclick="window.location='?act=ad_category_add'"/> 
	  </td>
      <td width="360">	  
	  </td>
     </tr>
  </table> -->
 
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>