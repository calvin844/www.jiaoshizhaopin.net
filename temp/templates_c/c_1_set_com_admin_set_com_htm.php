<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:37 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script  charset="utf-8" src="kindeditor/kindeditor.js"></script>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("set_com/admin_set_com_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
不同的运营阶段您可以选择不同的设置。<br />

</p>
</div>

<div class="toptit">基本设置</div>
  <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
<tr>
      <td width="200" align="right">发布招聘默认有效期：</td>
      <td><input name="company_add_days" type="text"  class="input_text_150" id="company_add_days" value="<?php echo $this->_vars['config']['company_add_days']; ?>
" maxlength="3"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>
      天<span  style="color:#666666"></span></td>
    </tr>
	<tr>
      <td align="right">招聘有效时间最少为：</td>
      <td><input name="company_add_days_min" type="text"  class="input_text_150" id="company_add_days_min" value="<?php echo $this->_vars['config']['company_add_days_min']; ?>
" maxlength="3"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>
      天<span  style="color:#666666"></span></td>
    </tr>
	<tr>
      <td align="right">上传营业执照文件限制：</td>
      <td><input name="certificate_max_size" type="text"  class="input_text_150" id="certificate_max_size" value="<?php echo $this->_vars['config']['certificate_max_size']; ?>
" maxlength="10"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>        
        kb</td>
    </tr>
		<tr>
      <td align="right">企业LOGO文件限制：</td>
      <td><input name="logo_max_size" type="text"  class="input_text_150" id="logo_max_size" value="<?php echo $this->_vars['config']['logo_max_size']; ?>
" maxlength="10"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>        
        kb</td>
    </tr>
	<tr>
      <td align="right">职位列表数最大为：</td>
      <td><input name="jobs_list_max" type="text"  class="input_text_150"   value="<?php echo $this->_vars['config']['jobs_list_max']; ?>
" maxlength="10"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>        
        条
		<span class="admin_note">0为不限制</span>
		</td>
    </tr>
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
  </table>
    </form>
  <div class="toptit">查看联系方式设置</div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
 
	<tr>
      <td align="right" width="200">允许查看职位和企业的联系方式：</td>
      <td>
	  
	  <label><input name="showjobcontact" type="radio"   value="0"  <?php if ($this->_vars['config']['showjobcontact'] == "0"): ?>checked=checked <?php endif; ?>/>游客</label>
	  &nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="showjobcontact" type="radio"   value="1"  <?php if ($this->_vars['config']['showjobcontact'] == "1"): ?>checked=checked <?php endif; ?>/>已登录会员</label>
   &nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="showjobcontact" type="radio"   value="2"  <?php if ($this->_vars['config']['showjobcontact'] == "2"): ?>checked=checked <?php endif; ?>/>已登录且发布了有效简历</label>

        </td>
    </tr>
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
	  </table>
	  
	    </form>
		  <div class="toptit">联系方式图形化 <span class="admin_note">图形化需要将中文字体文件上传到data/contactimgfont/，字体文件命名为“cn.ttc”</span></div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
 
	<tr>
      <td align="right" width="200">企业联系方式：</td>
      <td>
	  
	  <label><input name="contact_img_com" type="radio"   value="1"  <?php if ($this->_vars['config']['contact_img_com'] == "1"): ?>checked=checked <?php endif; ?>/>文字</label>
	  &nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="contact_img_com" type="radio"   value="2"  <?php if ($this->_vars['config']['contact_img_com'] == "2"): ?>checked=checked <?php endif; ?>/>图形化</label>


        </td>
    </tr>
	<tr>
      <td align="right" width="200">职位联系方式：</td>
      <td>
	  
	  <label><input name="contact_img_job" type="radio"   value="1"  <?php if ($this->_vars['config']['contact_img_job'] == "1"): ?>checked=checked <?php endif; ?>/>文字</label>
	  &nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="contact_img_job" type="radio"   value="2"  <?php if ($this->_vars['config']['contact_img_job'] == "2"): ?>checked=checked <?php endif; ?>/>图形化</label>


        </td>
    </tr>
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
	  </table>
	  
	    </form>
	 <div class="toptit">认证与审核状态设置</div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
	<tr>
	<tr>
      <td align="right"  width="200">企业认证状态默认：</td>
      <td><label>
        <input name="audit_add_com" type="radio" id="audit_add_com" value="0"  <?php if ($this->_vars['config']['audit_add_com'] == "0"): ?>checked=checked <?php endif; ?>/>未认证</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_add_com" value="1" id="audit_add_com"  <?php if ($this->_vars['config']['audit_add_com'] == "1"): ?>checked=checked<?php endif; ?>/>认证通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_add_com" value="2" id="audit_add_com"  <?php if ($this->_vars['config']['audit_add_com'] == "2"): ?>checked=checked<?php endif; ?>/>认证中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_add_com" value="3" id="audit_add_com"  <?php if ($this->_vars['config']['audit_add_com'] == "3"): ?>checked=checked<?php endif; ?>/>认证失败</label>
</td>
    </tr>
	<tr>
      <td align="right">修改企业资料后认证状态：</td>
      <td>
	  <label>
        <input name="audit_edit_com" type="radio" id="audit_edit_com" value="-1"  <?php if ($this->_vars['config']['audit_edit_com'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="audit_edit_com" type="radio" id="audit_edit_com" value="0"  <?php if ($this->_vars['config']['audit_edit_com'] == "0"): ?>checked=checked <?php endif; ?>/>未认证</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_edit_com" value="1" id="audit_edit_com"  <?php if ($this->_vars['config']['audit_edit_com'] == "1"): ?>checked=checked<?php endif; ?>/>认证通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_edit_com" value="2" id="audit_edit_com"  <?php if ($this->_vars['config']['audit_edit_com'] == "2"): ?>checked=checked<?php endif; ?>/>认证中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_add_com" value="3" id="audit_edit_com"  <?php if ($this->_vars['config']['audit_edit_com'] == "3"): ?>checked=checked<?php endif; ?>/>认证失败</label>
</td>
    </tr>
	<tr>
      <td align="right">已认证企业发布职位后审核状态：</td>
      <td><label>
        <input name="audit_verifycom_addjob" type="radio"   value="1"  <?php if ($this->_vars['config']['audit_verifycom_addjob'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_verifycom_addjob" value="2"   <?php if ($this->_vars['config']['audit_verifycom_addjob'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_verifycom_addjob" value="3"   <?php if ($this->_vars['config']['audit_verifycom_addjob'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">已认证企业修改职位后审核状态为：</td>
      <td>
	  <label>
        <input name="audit_verifycom_editjob" type="radio"   value="-1"  <?php if ($this->_vars['config']['audit_verifycom_editjob'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变
		</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="audit_verifycom_editjob" type="radio"   value="1"  <?php if ($this->_vars['config']['audit_verifycom_editjob'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过
		</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_verifycom_editjob" value="2"   <?php if ($this->_vars['config']['audit_verifycom_editjob'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_verifycom_editjob" value="3"   <?php if ($this->_vars['config']['audit_verifycom_editjob'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">未认证企业发布职位后审核状态为：</td>
      <td><label>
        <input name="audit_unexaminedcom_addjob" type="radio"   value="1"  <?php if ($this->_vars['config']['audit_unexaminedcom_addjob'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_unexaminedcom_addjob" value="2"   <?php if ($this->_vars['config']['audit_unexaminedcom_addjob'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_unexaminedcom_addjob" value="3"   <?php if ($this->_vars['config']['audit_unexaminedcom_addjob'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">未认证企业修改职位后审核状态为：</td>
      <td>
	  <label>
        <input name="audit_unexaminedcom_editjob" type="radio"   value="-1"  <?php if ($this->_vars['config']['audit_unexaminedcom_editjob'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变
		</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="audit_unexaminedcom_editjob" type="radio"   value="1"  <?php if ($this->_vars['config']['audit_unexaminedcom_editjob'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过
		</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_unexaminedcom_editjob" value="2"   <?php if ($this->_vars['config']['audit_unexaminedcom_editjob'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_unexaminedcom_editjob" value="3"   <?php if ($this->_vars['config']['audit_unexaminedcom_editjob'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">强制会员认证手机：</td>
      <td><label>
        <input name="login_com_audit_mobile" type="radio" id="login_com_audit_mobile" value="1"  <?php if ($this->_vars['config']['login_com_audit_mobile'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="login_com_audit_mobile" value="0" id="login_com_audit_mobile"  <?php if ($this->_vars['config']['login_com_audit_mobile'] == "0"): ?>checked=checked<?php endif; ?>/>否</label>

<span class="admin_note">如要设为强制认证手机需开启短信模块</span>
</td>
	</tr>
	<tr>
      <td align="right">强制会员认证email：</td>
      <td><label>
        <input name="login_com_audit_email" type="radio" id="login_com_audit_email" value="1"  <?php if ($this->_vars['config']['login_com_audit_email'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="login_com_audit_email" value="0" id="login_com_audit_email"  <?php if ($this->_vars['config']['login_com_audit_email'] == "0"): ?>checked=checked<?php endif; ?>/>否</label></td>
	</tr>
	
	
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	 </td>
	  </tr>
		  </table>
	  
	    </form>
	 <div class="toptit">过期信息设置</div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
	<tr>
      <td align="right" width="200">是否显示过期招聘信息：</td>
      <td><label>
        <input name="outdated_jobs" type="radio" id="outdated_jobs" value="1"  <?php if ($this->_vars['config']['outdated_jobs'] == "1"): ?>checked=checked <?php endif; ?>/>否</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="outdated_jobs" value="0" id="outdated_jobs"  <?php if ($this->_vars['config']['outdated_jobs'] == "0"): ?>checked=checked<?php endif; ?>/>是</label>


  <span class="admin_note">职位过期是指职位有效期过期或服务有效期过期，修改此项仅对新发布的职位有效</span>
</td>
	</tr>
	
		<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
		  </table>
	  
	    </form>
		
	<div class="toptit">点评设置</div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
	<tr>
      <td align="right" width="200">允许会员对职位点评：</td>
      <td><label>
        <input name="open_comment" type="radio"  value="1"  <?php if ($this->_vars['config']['open_comment'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="open_comment" value="0"   <?php if ($this->_vars['config']['open_comment'] == "0"): ?>checked=checked<?php endif; ?>/>否</label></td>
	</tr>
	
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
  </table>

    
  </form>	
		
		
	 <div class="toptit">其他设置</div>
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
	<tr>
      <td align="right" width="200">允许公司名称重复：</td>
      <td><label>
        <input name="company_repeat" type="radio" id="company_repeat" value="1"  <?php if ($this->_vars['config']['company_repeat'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="company_repeat" value="0" id="company_repeat"  <?php if ($this->_vars['config']['company_repeat'] == "0"): ?>checked=checked<?php endif; ?>/>否</label></td>
	</tr>
	
	<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td>
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