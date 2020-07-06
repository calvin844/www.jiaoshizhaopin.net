<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-09 14:40 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function()
{
	$("#ButAudit").QSdialog({
	DialogTitle:"请选择",
	DialogContent:"#AuditSet",
	DialogContentType:"id",
	DialogAddObj:"#OpAudit",	
	DialogAddType:"html"	
	});
	//删除
	$("#ButDel").click(function(){
		if (confirm('你确定要删除吗？'))
		{
			$("form[name=form1]").attr("action","?act=simple_del");
			$("form[name=form1]").submit()
		}
	});
	//刷新
	$("#Butrefresh").click(function(){
			$("form[name=form1]").attr("action","?act=simple_refresh");
			$("form[name=form1]").submit()
	});
	
		
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("simple/admin_simple_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
 
 <div class="toptip">
	<h2>提示：</h2>
	<p>
系统将每天自动清除过期信息，可在计划任务中编辑清除周期。
</p>
</div>

 
 
 
 
<div class="seltpye_y">

	<div class="tit link_lan">
	<strong>微招聘列表</strong><span>(共找到<?php echo $this->_vars['total']; ?>
条)</span>
	<a href="?act=">[恢复默认]</a>
	<div class="pli link_bk"><u>每页显示：</u>
	<a href="<?php echo $this->_run_modifier("perpage:10", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['perpage'] == "10"): ?>class="select"<?php endif; ?>>10</a>
	<a href="<?php echo $this->_run_modifier("perpage:20", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['perpage'] == "20"): ?>class="select"<?php endif; ?>>20</a>
	<a href="<?php echo $this->_run_modifier("perpage:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['perpage'] == "30"): ?>class="select"<?php endif; ?>>30</a>
	<a href="<?php echo $this->_run_modifier("perpage:60", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['perpage'] == "60"): ?>class="select"<?php endif; ?>>60</a>
	<div class="clear"></div>
	</div>
	</div>	
    <div class="list">
	  <div class="t">审核状态</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("audit:", 'qishi_parse_url', 'plugin', 1); ?>
"  <?php if ($_GET['audit'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("audit:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "1"): ?>class="select"<?php endif; ?>>审核通过</a>
		<a href="<?php echo $this->_run_modifier("audit:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "2"): ?>class="select"<?php endif; ?>>等待审核</a>
		<a href="<?php echo $this->_run_modifier("audit:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "3"): ?>class="select"<?php endif; ?>>审核未通过</a>
	  </div>
    </div>
	
	<div class="list">
	  <div class="t">添加时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("addtime:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addtime'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("addtime:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addtime'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("addtime:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addtime'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("addtime:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addtime'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
	  </div>
    </div>
	
	<div class="list">
	  <div class="t">到期时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("deadline:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("deadline:0", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "0"): ?>class="select"<?php endif; ?>>已经到期</a>
		<a href="<?php echo $this->_run_modifier("deadline:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "3"): ?>class="select"<?php endif; ?>>三天内到期</a>
		<a href="<?php echo $this->_run_modifier("deadline:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "7"): ?>class="select"<?php endif; ?>>一周内到期</a>
	  </div>
    </div>
	
	<div class="list">
	  <div class="t">刷新时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("refreshtime:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['refreshtime'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("refreshtime:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['refreshtime'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("refreshtime:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['refreshtime'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("refreshtime:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['refreshtime'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
	  </div>
    </div>
	
	 
	
	
	<div class="clear"></div>
</div>

 
  
  <form id="form1" name="form1" method="post" action="?act=simple_audit">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="link_lan" id="list">
    <tr>
      <td height="26" class="admin_list_tit admin_list_first" >
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>招聘职位</label>
	  </td>
      <td  class="admin_list_tit">公司名称</td>
	   <td width="10%"   class="admin_list_tit"> 审核状态 </td>
      <td width="10%"    align="center" class="admin_list_tit"> 刷新时间 </td>
      <td width="10%"    align="center" class="admin_list_tit">发布时间</td>
      <td width="10%"   align="center" class="admin_list_tit" >到期日期</td>
      <td width="10%"    align="center" class="admin_list_tit" >操作</td>
    </tr>
	  <?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['list']): ?>
      <tr>
      <td  class="admin_list admin_list_first">
        <input name="id[]" type="checkbox" id="id" value="<?php echo $this->_vars['list']['id']; ?>
"/>    
		<a href="?act=simple_edit&id=<?php echo $this->_vars['list']['id']; ?>
"><?php echo $this->_vars['list']['jobname']; ?>
</a>
	    </td>
		 <td  class="admin_list" >
		<?php echo $this->_vars['list']['comname']; ?>

		 </td>
		  <td  class="admin_list" >
		<?php if ($this->_vars['list']['audit'] == "1"): ?>
		<span style="color:#009900">审核通过</span>
		<?php elseif ($this->_vars['list']['audit'] == "2"): ?>
		等待审核
		<?php elseif ($this->_vars['list']['audit'] == "3"): ?>
		<span style="color: #999999">审核未通过</span>
		<?php endif; ?>
		 </td>
        <td align="center"  class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['refreshtime'], 'date_format', 'plugin', 1, "%m-%d  %H:%M"); ?>
		
		</td>
        <td align="center"  class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['addtime'], 'date_format', 'plugin', 1, "%m-%d  %H:%M"); ?>

		</td>
        <td align="center"  class="admin_list">
		<?php if ($this->_vars['list']['deadline'] == "0"): ?>
		长期
		<?php else: ?>
		<?php echo $this->_run_modifier($this->_vars['list']['deadline'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php endif; ?>
		</td>
        <td align="center"  class="admin_list">
		<a href="?act=simple_edit&id=<?php echo $this->_vars['list']['id']; ?>
">修改</a> &nbsp;&nbsp;
		<a href="?act=simple_del&id=<?php echo $this->_vars['list']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要删除吗？')">删除</a></td>
      </tr>
      <?php endforeach; endif; ?>
    </table>
	<span id="OpAudit"></span>
  </form>
	<?php if (! $this->_vars['list']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>	
<table width="100%" border="0" cellspacing="10"  class="admin_list_btm">
<tr>
        <td>
        <input name="ButADD" type="button" class="admin_submit" id="ButADD" value="新增"  onclick="window.location='?act=simple_add'"/>
		<input name="ButAudit" type="button" class="admin_submit" id="ButAudit" value="审核" />
		 <input name="Butrefresh" type="button" class="admin_submit" id="Butrefresh" value="刷新" />
		<input name="ButDel" type="button" class="admin_submit" id="ButDel"  value="删除"/>
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "职位"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="职位">职位</li>
														<li id="2" title="公司">公司</li>
														<li id="3" title="电话">电话</li>
														<li id="4" title="联系人">联系人</li>
														<li id="5" title="邮箱">邮箱</li>
														<li id="6" title="QQ">QQ</li>
														<li id="7" title="地址">地址</li>
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

<div style="display:none" id="AuditSet">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="6">
    <tr>
      <td width="20" height="30">&nbsp;</td>
      <td height="30"><strong  style="color:#0066CC; font-size:14px;">将所选信息置为：</strong></td>
    </tr>
	      <tr>
      <td width="27" height="25">&nbsp;</td>
      <td>
                      <label><input name="audit" type="radio" value="1" checked="checked"  />
                      审核通过</label></td>
    </tr>
    <tr>
      <td width="27" height="25">&nbsp;</td>
      <td>
                      <label><input type="radio" name="audit" value="3"  />
                       审核未通过</label></td>
    </tr>
    <tr>
      <td height="25">&nbsp;</td>
      <td>
	  <input type="submit" name="set_audit" value="确定" class="admin_submit"/>
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