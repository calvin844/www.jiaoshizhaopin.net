<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.cat.php'); $this->register_modifier("cat", "tpl_modifier_cat",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 10:19 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript" src="js/jquery.userinfotip-min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	//点击批量取消	
	$("#ButDelOrder").click(function(){
		if (confirm('你确定要取消吗？'))
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
  <div class="clear"></div>
</div>
  <div class="seltpye_x">
		<div class="left">完成状态</div>	
		<div class="right">
			<a href="<?php echo $this->_run_modifier("is_paid:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_paid'] == ""): ?>class="select"<?php endif; ?>>不限</a>
			<a href="<?php echo $this->_run_modifier("is_paid:2", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_paid'] == "2"): ?>class="select"<?php endif; ?>>已完成</a>
			<a href="<?php echo $this->_run_modifier("is_paid:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_paid'] == "1"): ?>class="select"<?php endif; ?>>待付款</a>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
  </div>
    <div class="seltpye_x">
		<div class="left">付款方式</div>	
		<div class="right">
			<a href="<?php echo $this->_run_modifier("typename:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['typename'] == ""): ?>class="select"<?php endif; ?>>不限</a>
			<?php if (count((array)$this->_vars['payment_list'])): foreach ((array)$this->_vars['payment_list'] as $this->_vars['list']): ?>
			<a href="<?php echo $this->_run_modifier($this->_run_modifier("typename:", 'cat', 'plugin', 1, $this->_vars['list']['typename']), 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['typename'] == $this->_vars['list']['typename']): ?>class="select"<?php endif; ?>><?php echo $this->_vars['list']['byname']; ?>
</a>
			<?php endforeach; endif; ?>
			<div class="clear"></div>
		</div>
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
  <form id="form1" name="form1" method="post" action="?act=order_del">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td  width="10%" class="admin_list_tit admin_list_first">
	   <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>全选</label></td>
      <td class="admin_list_tit" width="10%">金额</td>           
	  <td class="admin_list_tit">公司名称</td>
	  <td class="admin_list_tit">说明</td> 
	  <td align="center" class="admin_list_tit" width="190">单号</td>
	  <td width="10%" align="center" class="admin_list_tit">申请会员</td>
	  <td width="8%" align="center" class="admin_list_tit">申请时间</td>
      <td width="8%" align="center" class="admin_list_tit">支付方式</td>      
      <td width="15%" align="center"  class="admin_list_tit">操作</td>
    </tr>
	<?php if (count((array)$this->_vars['orderlist'])): foreach ((array)$this->_vars['orderlist'] as $this->_vars['list']): ?>
      <tr>
      <td class="admin_list admin_list_first">	 
	  <?php if ($this->_vars['list']['is_paid'] == "1"): ?>
	   <input name="id[]" type="checkbox" id="y_id" value="<?php echo $this->_vars['list']['id']; ?>
"  />
	  <span style="color: #FF6600">待付款...</span>
	  <?php endif; ?>
	  <?php if ($this->_vars['list']['is_paid'] == "2"): ?>
	  <input name="id[]" type="checkbox" id="y_id" value="<?php echo $this->_vars['list']['id']; ?>
" disabled="disabled"/>
		<span style="color: #009900;">已完成</span>
	  <?php endif; ?>
	   </td>
        <td class="admin_list">￥<strong><?php echo $this->_vars['list']['amount']; ?>
</strong>元</td>        
		<td class="admin_list">
		<?php if ($this->_vars['list']['companyname']): ?>
		<?php echo $this->_vars['list']['companyname']; ?>

		<?php else: ?>
		未填写企业资料
		<?php endif; ?>
		</td>   
		<td class="admin_list"><?php echo $this->_vars['list']['description']; ?>
</td>     
		<td align="center" class="admin_list"><?php echo $this->_vars['list']['oid']; ?>
</td>
		<td align="center" class="admin_list">
		<?php if ($this->_vars['list']['username']): ?>
		<?php echo $this->_vars['list']['username']; ?>

		<?php else: ?>
		未填写企业资料
		<?php endif; ?>
		</td>
		<td align="center" class="admin_list"><?php echo $this->_run_modifier($this->_vars['list']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>
</td>    
        <td align="center" class="admin_list"><?php echo $this->_vars['list']['payment_name']; ?>
</td>        
        <td align="center" class="admin_list">
		<?php if ($this->_vars['list']['is_paid'] == 2): ?><a href="?act=show_order&id=<?php echo $this->_vars['list']['id']; ?>
">查看</a><?php endif; ?>
		<?php if ($this->_vars['list']['is_paid'] == 1): ?>
		<a href="?act=order_set&id=<?php echo $this->_vars['list']['id']; ?>
">设置</a> <a href="?act=order_del&id=<?php echo $this->_vars['list']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
" onclick="return confirm('你确定要取消吗？')">取消</a><?php endif; ?>
		&nbsp;
		<a href="?act=management&id=<?php echo $this->_vars['list']['uid']; ?>
"  target="_blank" class="userinfo"  id="<?php echo $this->_vars['list']['uid']; ?>
">管理</a>
		</td>
      </tr>
      <?php endforeach; endif; ?>
  </table>
	<?php if (! $this->_vars['orderlist']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>
  </form>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
          <input name="ButAudit" type="button" class="admin_submit" id="ButDelOrder"  value="取消订单"  />
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "公司名"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="公司名">公司名</li>
														<li id="2" title="会员名">会员名</li>
														<li id="3" title="单号">单号</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="order_list" />
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