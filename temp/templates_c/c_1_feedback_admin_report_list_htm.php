<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-09 14:42 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
 <div class="seltpye_x">
		<div class="left">举报时间</div>	
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
  <form id="form1" name="form1" method="post" action="?act=del_report">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td width="15%"  class="admin_list_tit admin_list_first" >
	  <label id="chkAll"><input type="checkbox" name="chkAll"  id="chk" title="全选/反选" />投诉职位</label></td>
	       
            <td class="admin_list_tit">投诉内容</td>
		    <td width="180" align="center"  class="admin_list_tit">投诉者</td>
        <td width="160" align="center"   class="admin_list_tit">投诉时间</td>
	
      </tr>
	 <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	 <tr> 
      <td  class="admin_list admin_list_first">
      <input name="id[]" type="checkbox" id="id" value="<?php echo $this->_vars['li']['id']; ?>
" />
	 
	 <a href="<?php echo $this->_vars['li']['jobs_url']; ?>
" target="_blank"><?php echo $this->_vars['li']['jobs_name']; ?>
</a>	 </td>
	        
            <td  class="admin_list vtip" title="<?php echo $this->_run_modifier($this->_vars['li']['content'], 'nl2br', 'PHP', 1); ?>
" ><?php echo $this->_vars['li']['content']; ?>
</td>
			<td align="center"  class="admin_list">
			<?php echo $this->_vars['li']['username']; ?>
			</td>
        <td align="center"  class="admin_list"><?php echo $this->_run_modifier($this->_vars['li']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d | %H:%M"); ?>
</td>
	
      </tr>
	 <?php endforeach; endif; ?>
  </table>
	  <?php if (! $this->_vars['list']): ?>
<div class="admin_list_no_info">没有任何信息！</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
<input name="del" type="submit" class="admin_submit" id="ButDel" value="删除所选" onclick="return confirm('你确定要删除吗？')"/>
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