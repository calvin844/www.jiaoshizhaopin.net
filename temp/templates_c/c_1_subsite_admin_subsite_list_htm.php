<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("subsite/admin_subsite_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
<h2>提示：</h2>
<p>
新增分站后，需要将分站域名解析至主站，同时主网站需绑定此的分站域名；
</p>
</div> 
<form action="?act=subsite_del" method="post"  name="form1" id="form1">
<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
    <tr>
      <td width="180" class="admin_list_tit admin_list_first"><label id="chkAll"><input type="checkbox" name="chkAll"  id="chk" title="全选/反选" />分站名称</label></td>
      <td  class="admin_list_tit">域名</td>
      <td width="100"  class="admin_list_tit">状态</td>
      <td width="150"  class="admin_list_tit">地区</td>
      <td width="150"  class="admin_list_tit">模版</td>
      <td width="150" align="center"  class="admin_list_tit">操作</td>
    </tr>
	 <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	   <tr>
      <td  class="admin_list admin_list_first"><input type="checkbox" name="id[]" value="<?php echo $this->_vars['li']['s_id']; ?>
"/> <?php echo $this->_vars['li']['s_sitename']; ?>
 
	  <span style="color: #999999">(id:<?php echo $this->_vars['li']['s_id']; ?>
)</span>
	  </td>
      <td   class="admin_list"><a href="http://<?php echo $this->_vars['li']['s_domain']; ?>
" target="_blank"><?php echo $this->_vars['li']['s_domain']; ?>
</a></td>
      <td   class="admin_list"><?php if ($this->_vars['li']['s_effective'] == "1"): ?>可用<?php else: ?><span style="color:#CCCCCC">禁用</span><?php endif; ?> </td>
      <td   class="admin_list">
	   <?php echo $this->_vars['li']['s_districtname']; ?>

	  </td>
      <td   class="admin_list">
	  <?php if ($this->_vars['li']['s_tpl'] == ""): ?>默认<?php else:  echo $this->_vars['li']['s_tpl'];  endif; ?>
	  </td>
      <td align="center"   class="admin_list">
	  <a href="?act=edit&id=<?php echo $this->_vars['li']['s_id']; ?>
" >修改</a>
		&nbsp;&nbsp;
		<a href="?act=subsite_del&id=<?php echo $this->_vars['li']['s_id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('删除后无法恢复，您确定要删除吗？')">删除</a>	  </td>
    </tr>
	  <?php endforeach; endif; ?>
  </table>
	<?php if (! $this->_vars['list']): ?>
<div class="admin_list_no_info">没有任何信息！</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input type="button" class="admin_submit" id="ButAudit" value="添加分站"  onclick="window.location='?act=add'"/>
<input name=" " type="submit" class="admin_submit" id="ButAudit"   value="删除"/>
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