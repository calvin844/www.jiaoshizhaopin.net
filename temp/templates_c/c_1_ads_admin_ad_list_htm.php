<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.cat.php'); $this->register_modifier("cat", "tpl_modifier_cat",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.qishi_parse_url.php'); $this->register_modifier("qishi_parse_url", "tpl_modifier_qishi_parse_url",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-08 09:51 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function()
{
 //点击批量删除	
	$("#ButDlete").click(function(){
		if (confirm('你确定要删除吗？'))
		{
			$("form[name=form1]").submit()
		}
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
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("ads/admin_ad_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
系统将自动下架到期广告。<br />
按广告位显示广告请点击右下角按钮
</p>
</div>

<div class="seltpye_y">

	<div class="tit link_lan">
	<strong>广告列表</strong><span>(共找到<?php echo $this->_vars['total']; ?>
条)</span>
	<a href="?act=list">[恢复默认]</a>
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
	  <div class="t">到期时间</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("settr:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("settr:0", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "0"): ?>class="select"<?php endif; ?>>已经到期</a>
		<a href="<?php echo $this->_run_modifier("settr:3", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "3"): ?>class="select"<?php endif; ?>>三天内到期</a>
		<a href="<?php echo $this->_run_modifier("settr:7", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['settr'] == "7"): ?>class="select"<?php endif; ?>>一周内到期</a>
	  </div>
    </div>
	<div class="list">
	  <div class="t">显示状态</div>	  
	  <div class="txt link_lan">
	 	<a href="<?php echo $this->_run_modifier("is_display:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_display'] == ""): ?>class="select"<?php endif; ?>>不限</a>
		<a href="<?php echo $this->_run_modifier("is_display:1", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_display'] == "1"): ?>class="select"<?php endif; ?>>正常</a>
		<a href="<?php echo $this->_run_modifier("is_display:0", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['is_display'] == "0"): ?>class="select"<?php endif; ?>>停止</a>
	  </div>
    </div>
	
	<div class="list listod" style=" width:200px;">
	  <div class="t">广告位</div>	  
	  <div class="txt link_lan">
	  <a href="<?php echo $this->_run_modifier("category_id:", 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['category_id'] == ""): ?>class="select"<?php endif; ?>>不限</a>
 <?php if (count((array)$this->_vars['ad_category'])): foreach ((array)$this->_vars['ad_category'] as $this->_vars['cli']): ?>
 <a href="<?php echo $this->_run_modifier($this->_run_modifier("category_id:", 'cat', 'plugin', 1, $this->_vars['cli']['id']), 'qishi_parse_url', 'plugin', 1); ?>
" <?php if ($_GET['category_id'] == $this->_vars['cli']['id']): ?>class="select"<?php endif; ?>><?php echo $this->_vars['cli']['categoryname']; ?>
</a>
	<?php endforeach; endif; ?>
	  </div>
    </div>
	
	
	<div class="clear"></div>
</div>

 
   <form id="form1" name="form1" method="post" action="?act=del_ad">
    <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="list" class="link_lan">
    <tr>
      <td   class="admin_list_tit admin_list_first">
     <label id="chkAll"><input type="checkbox" name="" title="全选/反选" id="chk"/>广告标题</label>
	 </td>
	  <td width="16%"  class="admin_list_tit">所属广告位</td>
	  <td width="7%" align="center"  class="admin_list_tit">类型</td>
      <td width="9%" align="center"  class="admin_list_tit">开始时间</td>
      <td width="9%" align="center"  class="admin_list_tit">到期时间</td>	  
      <td width="7%" align="center"  class="admin_list_tit">状态</td>
	  <td width="6%" align="center"  class="admin_list_tit">排序</td>
      <td width="10%" align="center"  class="admin_list_tit">操作</td>
    </tr>
	<?php if (count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
	 <tr>
      <td   class="admin_list admin_list_first">
     <input type="checkbox" name="id[]"  value="<?php echo $this->_vars['li']['id']; ?>
"/>
	<a href="?act=edit_ad&id=<?php echo $this->_vars['li']['id']; ?>
" <?php if ($this->_vars['li']['text_color'] <> ""): ?>style="color:<?php echo $this->_vars['li']['text_color']; ?>
"<?php endif; ?>  <?php if ($this->_vars['li']['type_id'] == "1"): ?>class="vtip" title="<?php echo $this->_vars['li']['text_content']; ?>
" <?php elseif ($this->_vars['li']['type_id'] == "2"): ?>class="vtip" title='<img src="<?php echo $this->_vars['li']['img_path']; ?>
" />'<?php endif; ?>><?php echo $this->_vars['li']['title']; ?>
</a> 
	  <?php if ($this->_vars['li']['note'] <> ""): ?>
	  <img src="images/comment_alert.gif" border="0"  class="vtip" title="<?php echo $this->_vars['li']['note']; ?>
"/>
	  <?php endif; ?>	
	 </td>
	  <td  class="admin_list">
	  <?php echo $this->_vars['li']['categoryname']; ?>

	  </td>
	  <td align="center"  class="admin_list">
	  <?php if ($this->_vars['li']['type_id'] == "1"): ?>文字
	  <?php elseif ($this->_vars['li']['type_id'] == "2"): ?>图片
	  <?php elseif ($this->_vars['li']['type_id'] == "3"): ?>代码
	  <?php elseif ($this->_vars['li']['type_id'] == "4"): ?>FLASH
	  <?php elseif ($this->_vars['li']['type_id'] == "5"): ?>浮动
	  <?php elseif ($this->_vars['li']['type_id'] == "6"): ?>视频
	  <?php endif; ?>	  </td>
      <td align="center"  class="admin_list">
	  <?php if ($this->_vars['li']['starttime'] == "0"): ?>
		无限制
		<?php else: ?>
		<?php echo $this->_run_modifier($this->_vars['li']['starttime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

		<?php endif; ?>	  </td>
      <td align="center"  class="admin_list">
	  <?php if ($this->_vars['li']['deadline'] == "0"): ?>
		无限制
		<?php else: ?>
					<?php if ($this->_vars['li']['deadline'] < time()): ?>
					<span style="color:#FF6600">已经到期</span>
					<?php else: ?>
					<?php echo $this->_run_modifier($this->_vars['li']['deadline'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

					<?php endif; ?>
		<?php endif; ?>	  </td>	  
      <td align="center"  class="admin_list">
	  <?php if ($this->_vars['li']['is_display'] == "1"): ?>
	正常	<?php else: ?>
		<span style="color:#999999">暂停</span>
		<?php endif; ?>	  </td>
	  <td align="center"  class="admin_list"><?php echo $this->_vars['li']['show_order']; ?>
</td>
      <td align="center"  class="admin_list">
	  <a href="?act=edit_ad&id=<?php echo $this->_vars['li']['id']; ?>
">修改</a>
	  &nbsp; &nbsp; 
	  <a href="?act=del_ad&id=<?php echo $this->_vars['li']['id']; ?>
&<?php echo $this->_vars['urltoken']; ?>
"  onclick="return confirm('你确定要删除吗？')">删除</a>	  </td>
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
       <input type="button" name="Submit22" value="添加广告" class="admin_submit"    onclick="window.location='?act=ad_add'"/>
	   
		<input type="button" name="delete" value="删除"   id="ButDlete" class="admin_submit"   />
	  </td>
      <td width="360" align="right">
	  
<form id="formseh" name="formseh" method="get" action="?act=list">	
			<div class="seh">
			    <div class="keybox"><input name="key" type="text"   value="<?php echo $_GET['key']; ?>
" /></div>
			    <div class="selbox">
					<input name="key_type_cn"  id="key_type_cn" type="text" value="<?php echo $this->_run_modifier($_GET['key_type_cn'], 'default', 'plugin', 1, "广告标题"); ?>
" readonly="true"/>
						<div>
								<input name="key_type" id="key_type" type="hidden" value="<?php echo $this->_run_modifier($_GET['key_type'], 'default', 'plugin', 1, "1"); ?>
" />
												<div id="sehmenu" class="seh_menu">
														<ul>
														<li id="1" title="广告标题">广告标题</li>
														</ul>
												</div>
						</div>				
				</div>
				<div class="sbtbox">
				<input name="act" type="hidden" value="list" />
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