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

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">
	<tr>
      <td width="200" align="right">个人会员允许发布简历：</td>
      <td><input name="resume_max" type="text"  class="input_text_150" id="resume_max" value="<?php echo $this->_vars['config']['resume_max']; ?>
" maxlength="8"    onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/> 
	  份	   </td>
    </tr>
	<tr>
      <td align="right">每天允许申请职位：</td>
      <td><input name="apply_jobs_max" type="text"  class="input_text_150" id="apply_jobs_max" value="<?php echo $this->_vars['config']['apply_jobs_max']; ?>
" maxlength="8"    onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/ > 
	  个	  </td>
    </tr>
	<tr>
      <td align="right">上传简历照片大小不能超过：</td>
      <td><input name="resume_photo_max" type="text"  class="input_text_150" id="resume_photo_max" value="<?php echo $this->_vars['config']['resume_photo_max']; ?>
" maxlength="8"   onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/> KB</td>
    </tr>
		<tr>
		  <td align="right">简历列表数最大为：</td>
		  <td><input name="resume_list_max" type="text"  class="input_text_150"   value="<?php echo $this->_vars['config']['resume_list_max']; ?>
" maxlength="10"  onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>        
			条
			<span class="admin_note">0为不限制</span>
		  </td>
		</tr>
		<tr>
      <td align="right">简历姓名默认显示方式：</td>
      <td><label>
        <input name="resume_privacy" type="radio"   value="1"  <?php if ($this->_vars['config']['resume_privacy'] == "1"): ?>checked=checked <?php endif; ?>/>真实姓名</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="resume_privacy" value="2" <?php if ($this->_vars['config']['resume_privacy'] == "2"): ?>checked=checked<?php endif; ?>/>简历编号</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="resume_privacy" value="3" <?php if ($this->_vars['config']['resume_privacy'] == "3"): ?>checked=checked<?php endif; ?>/>姓氏</label>
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
	<div class="toptit">审核与认证设置</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
      <td width="200" align="right">新发布简历默认审核状态：</td>
      <td>
	  <label>
        <input name="audit_resume" type="radio" id="audit_resume" value="1"  <?php if ($this->_vars['config']['audit_resume'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="audit_resume" type="radio" id="audit_resume" value="2"  <?php if ($this->_vars['config']['audit_resume'] == "2"): ?>checked=checked <?php endif; ?>/>审核中</label>
		&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="audit_resume" type="radio" id="audit_resume" value="3"  <?php if ($this->_vars['config']['audit_resume'] == "3"): ?>checked=checked <?php endif; ?>/>审核未通过</label>
</td>
    </tr>
		<tr>
      <td align="right">修改简历后审核状态变为：</td>
      <td>
	  <label>
        <input name="audit_edit_resume" type="radio" id="audit_edit_resume" value="-1"  <?php if ($this->_vars['config']['audit_edit_resume'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="audit_edit_resume" type="radio" id="audit_edit_resume" value="1"  <?php if ($this->_vars['config']['audit_edit_resume'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="audit_edit_resume" type="radio" id="audit_edit_resume" value="2"  <?php if ($this->_vars['config']['audit_edit_resume'] == "2"): ?>checked=checked <?php endif; ?>/>审核中</label>
		&nbsp;&nbsp;&nbsp;&nbsp;
<label>
        <input name="audit_edit_resume" type="radio" id="audit_edit_resume" value="3"  <?php if ($this->_vars['config']['audit_edit_resume'] == "3"): ?>checked=checked <?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">新增简历照片后照片默认审核状态：</td>
      <td><label>
        <input name="audit_resume_photo" type="radio" id="audit_resume_photo" value="1"  <?php if ($this->_vars['config']['audit_resume_photo'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_resume_photo" value="2" id="audit_resume_photo"  <?php if ($this->_vars['config']['audit_resume_photo'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_resume_photo" value="3" id="audit_resume_photo"  <?php if ($this->_vars['config']['audit_resume_photo'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
</td>
    </tr>
	<tr>
      <td align="right">修改简历的照片后照片审核状态：</td>
      <td>
	  <label>
        <input name="audit_edit_photo" type="radio" id="audit_edit_photo" value="-1"  <?php if ($this->_vars['config']['audit_edit_photo'] == "-1"): ?>checked=checked <?php endif; ?>/>保持不变</label>
&nbsp;&nbsp;&nbsp;&nbsp;
	  <label>
        <input name="audit_edit_photo" type="radio" id="audit_edit_photo" value="1"  <?php if ($this->_vars['config']['audit_edit_photo'] == "1"): ?>checked=checked <?php endif; ?>/>审核通过</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_edit_photo" value="2" id="audit_edit_photo"  <?php if ($this->_vars['config']['audit_edit_photo'] == "2"): ?>checked=checked<?php endif; ?>/>审核中</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="audit_edit_photo" value="3" id="audit_edit_photo"  <?php if ($this->_vars['config']['audit_edit_photo'] == "3"): ?>checked=checked<?php endif; ?>/>审核未通过</label>
	  </td>
    </tr>
	
 		
	<tr>
      <td align="right">强制会员认证手机：</td>
      <td><label>
        <input name="login_per_audit_mobile" type="radio" id="login_per_audit_mobile" value="1"  <?php if ($this->_vars['config']['login_per_audit_mobile'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="login_per_audit_mobile" value="0" id="login_per_audit_mobile"  <?php if ($this->_vars['config']['login_per_audit_mobile'] == "0"): ?>checked=checked<?php endif; ?>/>否</label>
<span class="admin_note">如要设为强制认证手机需开启短信模块</span>

</td>
	</tr>
	<tr>
      <td align="right">强制会员认证email：</td>
      <td><label>
        <input name="login_per_audit_email" type="radio" id="login_per_audit_email" value="1"  <?php if ($this->_vars['config']['login_per_audit_email'] == "1"): ?>checked=checked <?php endif; ?>/>是</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="login_per_audit_email" value="0" id="login_per_audit_email"  <?php if ($this->_vars['config']['login_per_audit_email'] == "0"): ?>checked=checked<?php endif; ?>/>否</label></td>
	</tr>
		<tr>
	  <td align="right" valign="top">&nbsp;</td>
	  <td> 
	    <input name="submit" type="submit" class="admin_submit"    value="保存修改"/>
	  </td>
	  </tr>
	  </table>
  </form>
	<div class="toptit">联系方式设置</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
      <td width="200" align="right">简历联系方式显示方式：</td>
      <td><label>
        <input name="showresumecontact" type="radio" value="0"  <?php if ($this->_vars['config']['showresumecontact'] == "0"): ?>checked=checked <?php endif; ?>/>游客可见</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="showresumecontact" value="1"  <?php if ($this->_vars['config']['showresumecontact'] == "1"): ?>checked=checked<?php endif; ?>/>已登录会员可见
 </label>
 &nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="showresumecontact" value="2"  <?php if ($this->_vars['config']['showresumecontact'] == "2"): ?>checked=checked<?php endif; ?>/>下载后可见
 </label>
 <span class="admin_note">(如此项为“游客可见”或“已登录会员可见”，网站将会失去重要赢利点)</span></td>
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

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
      <td width="200" align="right">简历联系方式：</td>
      <td><label><input name="contact_img_resume" type="radio"   value="1"  <?php if ($this->_vars['config']['contact_img_resume'] == "1"): ?>checked=checked <?php endif; ?>/>文字</label>
	  &nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="contact_img_resume" type="radio"   value="2"  <?php if ($this->_vars['config']['contact_img_resume'] == "2"): ?>checked=checked <?php endif; ?>/>图形化</label>
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
  
  
  	<div class="toptit">简历下载设置</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
      <td width="200" align="right">简历下载要求：</td>
      <td><label>
        <input name="down_resume_limit" type="radio" value="1"  <?php if ($this->_vars['config']['down_resume_limit'] == "1"): ?>checked=checked <?php endif; ?>/>已登录且有发布职位的企业可下载</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="down_resume_limit" value="2"  <?php if ($this->_vars['config']['down_resume_limit'] == "2"): ?>checked=checked<?php endif; ?>/>已登录的企业可下载
 </label>
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
	<div class="toptit">高级人才设置</div>
 
    <form action="?act=set_save" method="post" name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="95%" border="0" cellspacing="5" cellpadding="1" style=" margin-bottom:3px;">	 
	<tr>
	<tr>
      <td align="right">申请高级人才简历完整指数要求：</td>
      <td><label>
        <input name="elite_resume_complete_percent" type="radio"   value="60"  <?php if ($this->_vars['config']['elite_resume_complete_percent'] == "60"): ?>checked=checked <?php endif; ?>/>>=60%</label>
&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="elite_resume_complete_percent" value="73"    <?php if ($this->_vars['config']['elite_resume_complete_percent'] == "73"): ?>checked=checked<?php endif; ?>/>>=73%
</label>

&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="elite_resume_complete_percent" value="86"    <?php if ($this->_vars['config']['elite_resume_complete_percent'] == "86"): ?>checked=checked<?php endif; ?>/>>=86%
</label>

&nbsp;&nbsp;&nbsp;&nbsp;
<label >
<input type="radio" name="elite_resume_complete_percent" value="100"    <?php if ($this->_vars['config']['elite_resume_complete_percent'] == "100"): ?>checked=checked<?php endif; ?>/>100%
</label>
</td>
	</tr>
      <td width="200" align="right" valign="top">申请高级人才说明：</td>
      <td>
	  <textarea name="personal_talent_requirement" style="width:450px; height:160px; font-size:12px; line-height:180%"  id="personal_talent_requirement"><?php echo $this->_vars['text']['personal_talent_requirement']; ?>
</textarea>
	   <script>
 KE.show({
				id : 'personal_talent_requirement',
				resizeMode : 1,
				allowPreviewEmoticons : false,
				allowUpload : false,
				items : [
				'source','fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|',  'link']
			});
		</script>	  </td>
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