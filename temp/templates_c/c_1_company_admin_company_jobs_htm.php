<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.cat.php'); $this->register_modifier("cat", "tpl_modifier_cat",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:42 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript" src="js/jquery.userinfotip-min.js"></script>
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
	$("#Butdelay").QSdialog({
	DialogTitle:"系统提示",
	DialogContent:"#delayset",
	DialogContentType:"id",
	DialogAddObj:"#Opdelay",	
	DialogAddType:"html"	
	});
	//点击批量删除	
	$("#ButDlete").click(function(){
		if (confirm('你确定要删除吗？'))
		{
			$("form[name=form1]").attr("action",$("form[name=form1]").attr("action")+"&delete=true");
			$("form[name=form1]").submit()
		}
	});
	//刷新
	$("#Butrefresh").click(function(){
			$("form[name=form1]").attr("action",$("form[name=form1]").attr("action")+"&refresh=ok");
			$("form[name=form1]").submit()
	});
	
	//纵向列表排序
	$(".listod .txt").each(function(i){
	var li=$(this).children(".select");
	var htm="<a href=\""+li.attr("href")+"\" class=\""+li.attr("class")+"\">"+li.text()+"</a>";
	li.detach();
	$(this).prepend(htm);
	});
		
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
有效职位是指：审核通过,有效期未到期,服务未到期,正常招聘的职位。反之为无效职位<br />
</p>
</div>


<div class="seltpye_y">

	<div class="tit link_lan">
	<strong>职位列表</strong><span>(共找到<?php echo $this->_vars['totaljob']; ?>
条)</span>
	<a href="?">[恢复默认]</a>
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
	  <div class="t">有效状态</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("jobtype:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['jobtype'] == "1"): ?>class="select"<?php endif; ?>>有效职位<span>(<?php echo $this->_vars['total']['0']; ?>
)</span></a>
		<a href="<?php echo $this->_run_modifier("jobtype:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['jobtype'] == "2"): ?>class="select"<?php endif; ?>>无效职位<span>(<?php echo $this->_vars['total']['1']; ?>
)</span></a>
	  </div>
    </div>
	  <?php if ($_GET['jobtype'] == "2"): ?>
	<div class="list">
	  <div class="t">审核状态</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("audit:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("audit:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "1"): ?>class="select"<?php endif; ?>>审核通过<span>(<?php echo $this->_vars['total']['2']; ?>
)</span></a>
		<a href="<?php echo $this->_run_modifier("audit:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "2"): ?>class="select"<?php endif; ?>>等待审核<span>(<?php echo $this->_vars['total']['3']; ?>
)</span></a>
		<a href="<?php echo $this->_run_modifier("audit:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['audit'] == "3"): ?>class="select"<?php endif; ?>>审核未通过<span>(<?php echo $this->_vars['total']['4']; ?>
)</span></a>
	  </div>
    </div>
	  <?php endif; ?>
	
	
	<div class="list listod" >
	  <div class="t">到期时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("deadline:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<?php if ($_GET['jobtype'] == "2"): ?>		
		<a href="<?php echo $this->_run_modifier("deadline:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "1"): ?>class="select"<?php endif; ?>>已到期</a>
		<a href="<?php echo $this->_run_modifier("deadline:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "2"): ?>class="select"<?php endif; ?>>未到期</a>
		<?php endif; ?>
		<a href="<?php echo $this->_run_modifier("deadline:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("deadline:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("deadline:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['deadline'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
	  </div>
    </div>
	
	<div class="list listod" >
	  <div class="t">推广类型</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("promote:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['promote'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("promote:-1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['promote'] == "-1"): ?>class="select"<?php endif; ?>>未推广</a>
		<?php if (count((array)$this->_vars['cat'])): foreach ((array)$this->_vars['cat'] as $this->_vars['li']): ?>
		<a href="<?php echo $this->_run_modifier($this->_run_modifier("promote:", 'cat', 'plugin', 1, $this->_vars['li']['cat_id']), 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['promote'] == $this->_vars['li']['cat_id']): ?>class="select"<?php endif; ?>><?php echo $this->_vars['li']['cat_name']; ?>
</a>
		<?php endforeach; endif; ?>
	  </div>
    </div>
	
	 
	
	<div class="list" >
	  <div class="t">添加时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("addsettr:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addsettr'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("addsettr:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addsettr'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("addsettr:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addsettr'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("addsettr:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['addsettr'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
	  </div>
    </div>
	
	<div class="list" >
	  <div class="t">刷新时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("settr:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("settr:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "3"): ?>class="select"<?php endif; ?>>三天内</a>
		<a href="<?php echo $this->_run_modifier("settr:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "7"): ?>class="select"<?php endif; ?>>一周内</a>
		<a href="<?php echo $this->_run_modifier("settr:30", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "30"): ?>class="select"<?php endif; ?>>一月内</a>
	  </div>
    </div>
	
	
	
	<div class="clear"></div>
</div>



  
  <form id="form1" name="form1" method="post" action="?act=jobs_perform">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="list" class="link_lan">
    <tr>
      <td   class="admin_list_tit admin_list_first">
      <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>职位名称</label></td>
      <td  class="admin_list_tit"> 发布公司 </td>
	  <td align="center"  width="10%" class="admin_list_tit">审核状态</td>
	  <td align="center" width="5%" class="admin_list_tit">来源</td>
	  <td align="center"  width="10%" class="admin_list_tit">添加时间</td>
      <td align="center" width="10%"  class="admin_list_tit">到期时间</td>
      <td align="center" width="10%"  class="admin_list_tit">刷新时间</td>
	    <td align="center" width="5%" class="admin_list_tit">点击</td>
      
      <td    width="100" align="center"  class="admin_list_tit">操作</td>
	
    </tr>
	<?php if (count((array)$this->_vars['jobs'])): foreach ((array)$this->_vars['jobs'] as $this->_vars['list']): ?>
      <tr>
      <td  class="admin_list admin_list_first">
        <input name="y_id[]" type="checkbox" id="y_id" value="<?php echo $this->_vars['list']['id']; ?>
"  />		
		 <a href="<?php echo $this->_vars['list']['jobs_url']; ?>
" target="_blank"<?php if ($this->_vars['list']['deadline'] < time() || $this->_vars['list']['display'] == "2"): ?>style="color:#999999"<?php endif; ?>  ><?php echo $this->_vars['list']['jobs_name']; ?>
</a>		 
		 <?php if ($this->_vars['list']['emergency'] == "1"): ?>&nbsp;<span style="color: #FF6600">[急聘]</span><?php endif; ?>
		<?php if ($this->_vars['list']['recommend'] == "1"): ?>&nbsp;<span style="color: #339900">[推荐]</span><?php endif; ?>
		<?php if ($this->_vars['list']['stick'] == "1"): ?>&nbsp;<span style="color: #FF3399">[置顶]</span><?php endif; ?>
		<?php if ($this->_vars['list']['highlight'] != ""): ?>&nbsp;<span style="color: #6633CC">[变色]</span><?php endif; ?>
		<?php if ($this->_vars['list']['display'] == "2"): ?>&nbsp;<span style="color: #999999">[已暂停]</span><?php endif; ?>		
	    </td>
        <td class="admin_list">
		<a href="<?php echo $this->_vars['list']['company_url']; ?>
" target="_blank" style="color: #000000" title="<?php echo $this->_vars['list']['companyname']; ?>
"><?php echo $this->_vars['list']['companyname']; ?>
</a>
		</td>
		 <td class="admin_list" align="center">
		<?php if ($this->_vars['list']['audit'] == "1"): ?>
		<span style="color: #009900">审核通过	</span>	
		<?php elseif ($this->_vars['list']['audit'] == "2"): ?>
		<span style="color:#FF0000">等待审核</span>
		<?php elseif ($this->_vars['list']['audit'] == "3"): ?>
		审核未通过
		<?php endif; ?>
		</td>
		<td class="admin_list"align="center" >
				<?php if ($this->_vars['list']['robot'] == "0"): ?>人工<?php endif; ?>
		<?php if ($this->_vars['list']['robot'] == "1"): ?>采集<?php endif; ?>
		</td>
		<td class="admin_list" align="center" >
		<?php echo $this->_run_modifier($this->_vars['list']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		</td>
        <td class="admin_list" align="center" >
		<?php if ($this->_vars['list']['deadline'] > time()): ?>
		<?php echo $this->_run_modifier($this->_vars['list']['deadline'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php else: ?>
			<span style="color:#FF6600">已经过期</span>
		<?php endif; ?>
		</td>
       <td class="admin_list" align="center" >
		<?php echo $this->_run_modifier($this->_vars['list']['refreshtime'], 'date_format', 'plugin', 1, "%m-%d %H:%M"); ?>

		</td>
		  <td class="admin_list" align="center" >
		<?php echo $this->_vars['list']['click']; ?>

		</td>
        
        <td class="admin_list" align="center" >		
		<a href="?act=edit_jobs&id=<?php echo $this->_vars['list']['id']; ?>
">修改</a> 
		&nbsp;
		<a href="?act=management&id=<?php echo $this->_vars['list']['uid']; ?>
"  target="_blank"  class="userinfo"  id="<?php echo $this->_vars['list']['uid']; ?>
">管理</a> 
		</td>
      </tr>
      <?php endforeach; endif; ?>   
  </table>
  <span id="OpAudit"></span>
  <span id="Opdelay"></span>
  </form>
	<?php if (! $this->_vars['list']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
          <input name="ButAudit" type="button" class="admin_submit" id="ButAudit"    value="审核"  />
		  <input type="button" name="Butrefresh"  id="Butrefresh" value="刷新" class="admin_submit"/>
		  <input type="button" name="Butdelay"  id="Butdelay" value="延期" class="admin_submit"/>
		<input type="button" name="ButDlete"  id="ButDlete" value="删除" class="admin_submit"/>
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?act=jobs">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "职位名"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="职位名">职位名</li>
														<li id="2" title="公司名">公司名</li>
														<li id="3" title="职位ID">职位ID</li>
														<li id="4" title="公司ID">公司ID</li>
														<li id="5" title="会员ID">会员ID</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="jobs" />
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
      <td height="30"><strong  style="color:#0066CC; font-size:14px;">将所选职位置为：</strong></td>
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
  
  
<div style="display:none" id="delayset">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="6">
    <tr>
      <td width="20" height="30">&nbsp;</td>
      <td height="30"><strong  style="color:#0066CC; font-size:14px;">延长职位有效期：</strong></td>
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