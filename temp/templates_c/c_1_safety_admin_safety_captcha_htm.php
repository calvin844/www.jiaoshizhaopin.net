<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("safety/admin_safety_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
	验证码可以避免恶意注册及恶意发布，启用验证码会使得部分操作变得繁琐，建议仅在必需时打开
<br />
使用 TTF 字体作为验证码文字，把下载的 TTF 英文字体文件上传到 data/font/en 目录下，系统将随机使用里面的字体文件作为验证码的文字
<br />
使用中文图片验证码前，需要把包含完整中文汉字的 TTF 中文字体文件上传到 data/font/cn 目录下，系统将随机使用里面的字体文件作为验证码的文字
<br />

</p>
</div>

<div class="toptit">启用验证码</div>
<script type="text/javascript" src="js/jquery.iColorPicker.js"></script>
<form action="?act=captcha_save" method="post"  name="form1" id="form1">
<?php echo $this->_vars['inputtoken']; ?>

	  <table width="90%" border="0" cellspacing="0" cellpadding="4"  >
        <tr>
          <td width="120" align="right">会员注册：</td>
          <td width="210">
		  <label> <input name="verify_userreg" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_userreg'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_userreg" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_userreg'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
		  
		  </td>
          <td rowspan="4" valign="top">
		  <span class="admin_note">验证码可以避免恶意注册及恶意发布，启用验证码会使得部分操作变得繁琐，建议仅在必需时打开</span><br />
		  <img src='../include/imagecaptcha.php?t=<?php echo $this->_vars['random']; ?>
' name="getcode" align="absmiddle"  style='cursor:pointer; margin:8px;' title="看不请验证码？点击更换一张" onclick="this.src='../include/imagecaptcha.php?t='+(new Date().getTime());" border="0"/>		  </td>
        </tr>
		<tr>
          <td align="right">会员登录：</td>
          <td>
		  
		  <label> <input name="verify_userlogin" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_userlogin'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_userlogin" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_userlogin'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
		 
		 </td>
        </tr>
			<tr>
          <td align="right">发布招聘：</td>
          <td>
		  
		   <label> <input name="verify_addjob" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_addjob'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_addjob" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_addjob'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		
		<?php if ($this->_vars['captcha']['verify_gifts'] <> ""): ?>
		<tr>
          <td align="right">礼品卡充值：</td>
          <td>
		  
		   <label> <input name="verify_gifts" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_gifts'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_gifts" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_gifts'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		<?php endif; ?>
		
			<tr>
          <td align="right">发布简历：</td>
          <td>
		  
		   <label> <input name="verify_resume" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_resume'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_resume" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_resume'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
			<tr>
          <td align="right">发布微招聘：</td>
          <td>
		  
		   <label> <input name="verify_simple" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_simple'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_simple" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_simple'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		<tr>
          <td align="right">申请友情链接：</td>
          <td>
		  
		   <label> <input name="verify_link" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_link'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_link" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_link'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		<tr>
          <td align="right">找回密码：</td>
          <td>
		  
		   <label> <input name="verify_getpwd" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_getpwd'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_getpwd" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_getpwd'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		<tr>
          <td align="right">发表评论：</td>
          <td>
		  
		   <label> <input name="verify_comment" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_comment'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_comment" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_comment'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
	 </td>
        </tr>
		<tr>
          <td align="right">后台登录：</td>
          <td>
		  
		    <label> <input name="verify_adminlogin" type="radio" value="1" <?php if ($this->_vars['captcha']['verify_adminlogin'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 开启</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="verify_adminlogin" type="radio" value="0" <?php if ($this->_vars['captcha']['verify_adminlogin'] == "0"): ?>checked="checked"<?php endif; ?>/>关闭</label>
		  
		 
		   </td>
        </tr>
	
        <tr>
          <td align="right">&nbsp;</td>
          <td height="50" colspan="2"> 
            <input name="submit" type="submit" class="admin_submit"    value="保存"/>         </td>
        </tr>
    </table>
  </form>
<div class="toptit">验证码设置</div>
  

    <form id="form2" name="form2" method="post"   action="?act=captcha_save" >
	<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="4" cellspacing="0"   >
    <tr>
      <td width="120" height="30" align="right"  >验证码宽度：</td>
      <td  ><input name="captcha_width" type="text" class="input_text_200"   maxlength="3" value="<?php echo $this->_vars['captcha']['captcha_width']; ?>
"/>
	  <span class="admin_note">验证码图片宽度，建议在 60 - 180 范围内取值，0为自适应</span>
       </td>
    </tr>
	   <tr>
      <td width="120" height="30" align="right"  >验证码高度：</td>
      <td  ><input name="captcha_height" type="text" class="input_text_200"   maxlength="3" value="<?php echo $this->_vars['captcha']['captcha_height']; ?>
"/>
	  <span class="admin_note">验证码图片高度，建议在 20 - 50 范围内取值，0为自适应</span>
       </td>
    </tr> 
	    <tr>
      <td height="30" align="right"  >文字颜色：</td>
      <td   >
	  
	  <table width="100%"   border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="245" >
	<input name="captcha_textcolor"  id="captcha_textcolor" type="text" class="iColorPicker input_text_200"  onclick="iColorShow('captcha_textcolor','icp_captcha_textcolor')"  maxlength="7" value="<?php echo $this->_vars['captcha']['captcha_textcolor']; ?>
"/></td>
    <td><span class="admin_note" style="float:left">验证码文字颜色，空 为随机</span></td>
  </tr>
</table>

	 
	  
       </td>
    </tr>
	 <tr>
      <td height="30" align="right"  >文字大小：</td>
      <td   ><input name="captcha_textfontsize" type="text" class="input_text_200"   maxlength="3" value="<?php echo $this->_vars['captcha']['captcha_textfontsize']; ?>
"/>
	  <span class="admin_note">验证码文字大小，建议在 12 - 50 范围内取值</span>
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >字符数量：</td>
      <td   ><input name="captcha_textlength" type="text" class="input_text_200"   maxlength="2" value="<?php echo $this->_vars['captcha']['captcha_textlength']; ?>
"/>
	   <span class="admin_note">验证码字符数量，建议在 2 - 6 范围内取值，0 为随机</span>
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >文字类型：</td>
      <td   >
	  
	 <label> <input name="captcha_lang" type="radio" value="en" <?php if ($this->_vars['captcha']['captcha_lang'] == "en"): ?>checked="checked"<?php endif; ?>/>
	 英文</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="captcha_lang" type="radio" value="cn" <?php if ($this->_vars['captcha']['captcha_lang'] == "cn"): ?>checked="checked"<?php endif; ?>/>中文</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <span class="admin_note">使用中文验证码需要把中文汉字的TTF文件上传到 data/font/cn 目录下，系统将随机使用里面的字体文件作为验证码的文字</span>
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >背景颜色：</td>
      <td   >
	  
	   <table width="100%"   border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="245" >
	<input name="captcha_bgcolor"  id="captcha_bgcolor" type="text" class="iColorPicker input_text_200"  onclick="iColorShow('captcha_bgcolor','icp_captcha_bgcolor')"  maxlength="7" value="<?php echo $this->_vars['captcha']['captcha_bgcolor']; ?>
"/></td>
    <td><span class="admin_note" style="float:left">验证码背景颜色，空 为随机</span></td>
  </tr>
</table>
	  
	 
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >干扰点数量：</td>
      <td   ><input name="captcha_noisepoint" type="text" class="input_text_200"   maxlength="7" value="<?php echo $this->_vars['captcha']['captcha_noisepoint']; ?>
"/>
	   <span class="admin_note">验证码干扰点数量，建议在 0 - 1000 范围内取值 </span>
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >干扰线数量：</td>
      <td   ><input name="captcha_noiseline" type="text" class="input_text_200"   maxlength="7" value="<?php echo $this->_vars['captcha']['captcha_noiseline']; ?>
"/>
	  <span class="admin_note">验证码干扰线数量，建议在 0 - 10 范围内取值 </span>
       </td>
    </tr>
	<tr>
      <td height="30" align="right"  >是否扭曲字符：</td>
      <td   >
	  <label> <input name="captcha_distortion" type="radio" value="1" <?php if ($this->_vars['captcha']['captcha_distortion'] == "1"): ?>checked="checked"<?php endif; ?>/>
	 是</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <label> <input name="captcha_distortion" type="radio" value="0" <?php if ($this->_vars['captcha']['captcha_distortion'] == "0"): ?>checked="checked"<?php endif; ?>/>否</label>&nbsp;&nbsp;&nbsp;&nbsp;
	 <span class="admin_note">验证码字符产生扭曲效果</span>
       </td>
    </tr>
    <tr>
      <td height="30" align="right"  >&nbsp;</td>
      <td height="50"  > 
            <input name="submit3" type="submit" class="admin_submit"    value="保存"/>
    
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