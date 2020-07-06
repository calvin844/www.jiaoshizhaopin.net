<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:37 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("set/admin_set_config_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
页面标题设置以及关键字设置等请在页面管理中设置。<br />

网站域名和网站安装目录填写错误可导致网站部分功能不能使用。
</p>
</div>
<div class="toptit">网站配置</div>
  <form action="admin_set.php?act=site_setsave" method="post" enctype="multipart/form-data" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td width="120" align="right">网站名称：</td>
      <td><input name="site_name" type="text"  class="input_text_200" id="site_name" value="<?php echo $this->_vars['config']['site_name']; ?>
" maxlength="30"/></td>
    </tr>
    <tr>
      <td align="right">网站域名：</td>
      <td><input name="site_domain" type="text"  class="input_text_200" id="site_domain" value="<?php echo $this->_vars['config']['site_domain']; ?>
" maxlength="100"/>
      结尾不要加 &quot; / &quot;</td>
    </tr>
    <tr>
      <td align="right">安装目录：</td>
      <td><input name="site_dir" type="text"  class="input_text_200" id="site_dir" value="<?php echo $this->_vars['config']['site_dir']; ?>
" maxlength="40"/>
      ( 以 &quot; / &quot; 开头和结尾, 如果安装在根目录，则为&quot; / &quot;)      </td>
    </tr>
    <tr>
      <td align="right">联系电话(顶部)：</td>
      <td><input name="top_tel" type="text"  class="input_text_400" id="top_tel" value="<?php echo $this->_vars['config']['top_tel']; ?>
" maxlength="80"/></td>
    </tr>
    <tr>
      <td align="right">联系电话(底部)：</td>
      <td><input name="bootom_tel" type="text"  class="input_text_400" id="bootom_tel" value="<?php echo $this->_vars['config']['bootom_tel']; ?>
" maxlength="80"/></td>
    </tr>
	<tr>
      <td align="right">网站底部联系地址：</td>
      <td><input name="address" type="text"  class="input_text_400" id="address" value="<?php echo $this->_vars['config']['address']; ?>
" maxlength="120"/></td>
    </tr>
	<tr>
      <td align="right">网站底部其他说明：</td>
      <td><input name="bottom_other" type="text"  class="input_text_400" id="bottom_other" value="<?php echo $this->_vars['config']['bottom_other']; ?>
" maxlength="200"/></td>
    </tr>
    <tr>
      <td align="right">网站备案号(ICP)：</td>
      <td><input name="icp" type="text"  class="input_text_400" id="icp" value="<?php echo $this->_vars['config']['icp']; ?>
" maxlength="30"  /></td>
    </tr>
    <tr>
      <td align="right">网站Logo： </td>
      <td><input name="web_logo" type="file" id="web_logo" style="width:300px; font-size:12px; padding:3px;" onKeyDown="alert('请点击右侧“浏览”选择您电脑上的图片！');return false"/>&nbsp;&nbsp;&nbsp;<?php if ($this->_vars['config']['web_logo']): ?> <input type="button" name="Submit" value="查看Logo" class="vtip" title="<img src=<?php echo $this->_vars['upfiles_dir'];  echo $this->_vars['config']['web_logo']; ?>
?t=<?php echo $this->_vars['rand']; ?>
  border=0  align=absmiddle>"  style=" font-size:12px;"/><?php endif; ?>         </td>
    </tr>
    <tr>
      <td align="right">暂时关闭网站：</td>
      <td>
	  <label><input name="isclose" type="radio" id="isclose" value="0"  <?php if ($this->_vars['config']['isclose'] == "0"): ?>checked="checked"<?php endif; ?>/>否</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="isclose" value="1" id="isclose1"  <?php if ($this->_vars['config']['isclose'] == "1"): ?>checked="checked"<?php endif; ?>/>是</label>
	   </td>
    </tr>
	
    <tr>
      <td align="right" valign="top">暂时关闭原因：</td>
      <td><textarea name="close_reason" class="input_text_400" id="close_reason" style="height:60px;"><?php echo $this->_vars['config']['close_reason']; ?>
</textarea></td>
    </tr>
    <tr>
      <td align="right" valign="top">第三方流量统计代码：</td>
      <td><textarea name="statistics" class="input_text_400" id="statistics" style="height:60px;"><?php echo $this->_vars['config']['statistics']; ?>
</textarea></td>
    </tr>
    <tr>
      <td align="right">关闭会员注册：</td>
      <td>
	  <label>
        <input name="closereg" type="radio" id="closereg" value="0"  <?php if ($this->_vars['config']['closereg'] == 0): ?>checked=checked <?php endif; ?>/>否</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="closereg" value="1"  <?php if ($this->_vars['config']['closereg'] == "1"): ?>checked=checked<?php endif; ?>/>是</label></td>
    </tr>
	 
	      
	       <tr>
	         <td align="right">&nbsp;</td>
	         <td height="50"> 
	           <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
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