<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-09 14:41 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function()
{
	//点击批量取消	
	$("#ButDel").click(function(){
		if (confirm('你确定要删除吗？'))
		{
			$("form[name=form1]").submit()
		}
	});
		
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("jobfair/admin_jobfair_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
 


 <div class="seltpye_x">
		<div class="left">添加时间</div>	
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
  
  
   <div class="seltpye_x">
		<div class="left">预定状态</div>	
		<div class="right">
		<a href="<?php echo $this->_run_modifier("predetermined_status:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['predetermined_status'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("predetermined_status:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['predetermined_status'] == "1"): ?>class="select"<?php endif; ?>>允许预定</a>
		<a href="<?php echo $this->_run_modifier("predetermined_status:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['predetermined_status'] == "2"): ?>class="select"<?php endif; ?>>停止预定</a>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
  
  <form id="form1" name="form1" method="post" action="?act=jobfair_del">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_bk">
    <tr>
      <td height="26" class="admin_list_tit admin_list_first" width="120">
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>举办时间</label>
	  </td>
      <td  class="admin_list_tit">招聘会标题</td>
      <td width="80"   align="center" class="admin_list_tit"> 预定状态 </td>
      <td width="180"   align="center" class="admin_list_tit">预定期限</td>
      <td width="120"   align="center" class="admin_list_tit" >添加日期</td>
      <td width="100"   align="center" class="admin_list_tit" >操作</td>
    </tr>
	  <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['list']): ?>
      <tr>
      <td  class="admin_list admin_list_first">
        <input name="id[]" type="checkbox" id="id" value="<?php echo $this->_vars['list']['id']; ?>
"/>    
		<?php if ($this->_vars['list']['holddates'] > $this->_vars['time']): ?>  
		<span style="color:#009900"><?php echo $this->_run_modifier($this->_vars['list']['holddates'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>
</span>
		<?php else: ?>
		<span style="color: #999999"><?php echo $this->_run_modifier($this->_vars['list']['holddates'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>
</span>
		<?php endif; ?>
	    </td>
		 <td  class="admin_list" >
		<a href="?act=jobfair_edit&id=<?php echo $this->_vars['list']['id']; ?>
"><?php echo $this->_vars['list']['title']; ?>
</a>
		 </td>
        <td align="center"  class="admin_list">
		<?php if ($this->_vars['list']['predetermined_status'] == "1"): ?>  
		允许预定
		<?php else: ?>
		<span style="color: #999999">禁止预定</span>
		<?php endif; ?>
		
		</td>
        <td align="center"  class="admin_list">
		<?php if ($this->_vars['list']['predetermined_start']): ?>
		<?php echo $this->_run_modifier($this->_vars['list']['predetermined_start'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php else: ?>
		不限制
		<?php endif; ?>
		≈
		<?php if ($this->_vars['list']['predetermined_end']): ?>
		<?php echo $this->_run_modifier($this->_vars['list']['predetermined_end'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php else: ?>
		不限制
		<?php endif; ?>
		</td>
        <td align="center"  class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		</td>
        <td align="center"  class="admin_list">
		<a href="?act=jobfair_edit&id=<?php echo $this->_vars['list']['id']; ?>
">修改</a> &nbsp;&nbsp;
		<a href="?act=jobfair_del&id=<?php echo $this->_vars['list']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要删除吗？')">删除</a></td>
      </tr>
      <?php endforeach; endif; ?>
    </table>
  </form>
	<?php if (! $this->_vars['list']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>	
<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
        <td>
        <input name="ButADD" type="button" class="admin_submit" id="ButADD" value="新增"  onclick="window.location='?act=jobfair_add'"/>
		<input name="ButDel" type="button" class="admin_submit" id="ButDel"  value="删除所选"/>
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "标题"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="标题">标题</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="" />
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
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>