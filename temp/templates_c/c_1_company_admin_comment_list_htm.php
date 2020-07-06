<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-09 14:48 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
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
   
  <form id="form1" name="form1" method="post" action="?act=comment_del">
  <?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td  width="20%" class="admin_list_tit admin_list_first">
	   <label id="chkAll"><input type="checkbox" name=" " title="全选/反选" id="chk"/>全选</label></td>
      <td width="150" class="admin_list_tit">评论时间</td>           
	  <td class="admin_list_tit">评论内容</td>
	  </tr>
	<?php if (count((array)$this->_vars['clist'])): foreach ((array)$this->_vars['clist'] as $this->_vars['list']): ?>
      <tr>
      <td class="admin_list admin_list_first">	 
	   <input name="id[]" type="checkbox" id="y_id" value="<?php echo $this->_vars['list']['id']; ?>
"  />
	 <?php echo $this->_vars['list']['username']; ?>
 <span style="color: #CCCCCC; padding-left:10px;">[uid:<?php echo $this->_vars['list']['uid']; ?>
]</span>	   </td>
        <td class="admin_list">
		<?php echo $this->_run_modifier($this->_vars['list']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d %H:%M"); ?>
		</td>        
		<td class="admin_list vtip" title=' <?php echo $this->_vars['list']['username']; ?>
说：<br /><?php echo $this->_vars['list']['content']; ?>
'>
		<?php echo $this->_vars['list']['content_']; ?>

		 </td>   
	  </tr>
      <?php endforeach; endif; ?>
  </table>
	<?php if (! $this->_vars['clist']): ?>
	<div class="admin_list_no_info">没有任何信息！</div>
	<?php endif; ?>
  </form>
	<table width="100%" border="0" cellspacing="10" cellpadding="0" class="admin_list_btm">
      <tr>
        <td>
          <input name="ButAudit" type="button" class="admin_submit" id="ButDelOrder"  value="删除"  />
		</td>
        <td width="305" align="right">
		<form id="formseh" name="formseh" method="get" action="?">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "用户名"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="用户名">用户名</li>
														<li id="2" title="内容">内容</li>
														<li id="3" title="uid">uid</li>
														<li id="4" title="职位id">职位id</li>
														<li id="5" title="公司id">公司id</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="comment_list" />
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