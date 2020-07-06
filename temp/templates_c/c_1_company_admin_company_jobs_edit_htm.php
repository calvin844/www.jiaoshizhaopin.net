<?php require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.default.php'); $this->register_modifier("default", "tpl_modifier_default",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\function.qishi_get_classify.php'); $this->register_function("qishi_get_classify", "tpl_function_qishi_get_classify",false);  require_once('D:\web\www.91jiaoshi.com\include\template_lite\plugins\modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format",false);  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-07-09 10:19 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script src="<?php echo $this->_vars['QISHI']['site_dir']; ?>
data/cache_classify.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function()
{
	showmenulayer("#category_cn","#menu1","#category","#subclass",QS_jobs);
	showmenulayer("#district_cn","#menu2","#district","#sdistrict",QS_city);
	showmenu("#education_cn","#menu3","#education");
	showmenu("#experience_cn","#menu4","#experience");
	showmenu("#wage_cn","#menu5","#wage");
	//设置性别中文字段
	$("input[name='sex']").click(function(){$("#sex_cn").val($(this).attr("title"))	});
	
	//设置职位性质中文字段
	$("input[name='nature']").click(function(){$("#nature_cn").val($(this).attr("title"))	})
});
</script>
<div class="admin_main_nr_dbox">
<div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptit">修改职位- <span style="color:#0066CC"><?php echo $this->_vars['jobs']['jobs_name']; ?>
</span> </div>
<form id="Form1" name="Form1" method="post" action="?act=editjobs_save"  onsubmit="return chk_form();">
<?php echo $this->_vars['inputtoken']; ?>

  <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF"  >
<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">所属会员：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
<?php echo $this->_vars['jobs']['user']['username']; ?>

		 </td>
      </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">所属企业：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed" class="link_lan">
            <a href="<?php echo $this->_vars['jobs']['company_url']; ?>
" target="_blank"><?php echo $this->_vars['jobs']['companyname']; ?>
</a>
			 </td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">刷新时间：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed" class="link_lan">
          <?php echo $this->_run_modifier($this->_vars['jobs']['refreshtime'], 'date_format', 'plugin', 1, "%Y-%m-%d"); ?>

			 </td>
        </tr>
		 <tr>
          <td width="110" height="30" align="right" bgcolor="#FFFFFF"  style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 职位名称：</td>
          <td  style=" border-bottom:1px #CCCCCC dashed"><input name="jobs_name" type="text" class="input_text_200" id="jobs_name" value="<?php echo $this->_vars['jobs']['jobs_name']; ?>
" maxlength="50" />
               </td>
        </tr>
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span>  招聘状态：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <label><input name="display"  type="radio" value="1" <?php if ($this->_vars['jobs']['display'] == "1"): ?>checked="checked"<?php endif; ?> />招聘中</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <label><input name="display"  type="radio" value="2" <?php if ($this->_vars['jobs']['display'] == "2"): ?>checked="checked"<?php endif; ?> />暂停招聘</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 </td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 审核状态：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		   <label><input name="audit"  type="radio" value="1" <?php if ($this->_vars['jobs']['audit'] == "1"): ?>checked="checked"<?php endif; ?> />审核通过</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <label><input name="audit"  type="radio" value="2" <?php if ($this->_vars['jobs']['audit'] == "2"): ?>checked="checked"<?php endif; ?> />审核中</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 
		   <label><input name="audit"  type="radio" value="3" <?php if ($this->_vars['jobs']['audit'] == "3"): ?>checked="checked"<?php endif; ?> />审核未通过</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 </td>
        </tr>
       
		 
		<tr>
          <td width="110" height="30" align="right" bgcolor="#FFFFFF"  style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 性别要求：</td>
          <td  style=" border-bottom:1px #CCCCCC dashed">
		  
		   <label><input name="sex"  type="radio" value="1"  title="男" <?php if ($this->_vars['jobs']['sex'] == "1"): ?>checked="checked"<?php endif; ?> />男</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <label><input name="sex"  type="radio" value="2"  title="女"  <?php if ($this->_vars['jobs']['sex'] == "2"): ?>checked="checked"<?php endif; ?> />女</label>
		  
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <label><input name="sex"  type="radio" value="3"  title="不限" <?php if ($this->_vars['jobs']['sex'] == "3"): ?>checked="checked"<?php endif; ?> />不限</label>
		   <input name="sex_cn" type="hidden" value="<?php echo $this->_vars['jobs']['sex_cn']; ?>
" id="sex_cn" />
		  </td>
        </tr>
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 职位性质：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <?php echo tpl_function_qishi_get_classify(array('set' => "列表名:nature,类型:QS_jobs_nature"), $this);?>
<?php if (count((array)$this->_vars['nature'])): foreach ((array)$this->_vars['nature'] as $this->_vars['list']): ?>
<label><input type="radio" name="nature" value="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
" <?php if ($this->_vars['jobs']['nature'] == $this->_vars['list']['id']): ?>checked="checked"<?php endif; ?> ><?php echo $this->_vars['list']['categoryname']; ?>
</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php endforeach; endif; ?>
<input name="nature_cn" type="hidden" value="<?php echo $this->_vars['jobs']['nature_cn']; ?>
"  id="nature_cn"/>
		  </td>
        </tr>
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold;">*</span> 招聘人数：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="amount" type="text" class="input_text_200" id="amount" value="<?php echo $this->_vars['jobs']['amount']; ?>
" maxlength="3" onKeyUp="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');"onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/>
            人</td>
        </tr>
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF"style=" border-bottom:1px #CCCCCC dashed">有效日期：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">发布日期：<?php echo $this->_run_modifier($this->_vars['jobs']['addtime'], 'date_format', 'plugin', 1, "%Y-%m-%d %H:%M"); ?>
，截止日期：<?php echo $this->_run_modifier($this->_vars['jobs']['deadline'], 'date_format', 'plugin', 1, "%Y-%m-%d %H:%M"); ?>
<input name="olddeadline" type="hidden" value="<?php echo $this->_vars['jobs']['deadline']; ?>
" /></td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style="border-bottom:1px #CCCCCC dashed; color:#0033CC">延长有效时间：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="days" type="text"  class="input_text_200" id="days" onKeyUp="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');check_deadline(this.value);total_pointst()" value="0" maxlength="3" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"  style="width:50px;"/> 天&nbsp;&nbsp;(0表示不延长) </td>
        </tr>
        <tr>
          <td height="30" align="right"   bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 职位类别：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		   
<div>
		<?php echo tpl_function_qishi_get_classify(array('set' => "列表名:joblist,类型:QS_jobs"), $this);?>
		<input type="text" value="<?php echo $this->_run_modifier($this->_vars['jobs']['category_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="category_cn" id="category_cn" class="input_text_200 input_text_selsect"/>
		<input name="category" id="category" type="hidden" value="<?php echo $this->_vars['jobs']['category']; ?>
" />
		<input name="subclass" id="subclass" type="hidden" value="<?php echo $this->_vars['jobs']['subclass']; ?>
" />
		<div id="menu1" class="dmenu shadow">
			<ul>
			<?php echo tpl_function_qishi_get_classify(array('set' => "列表名:joblist,类型:QS_jobs"), $this);?>
			<?php if (count((array)$this->_vars['joblist'])): foreach ((array)$this->_vars['joblist'] as $this->_vars['list']): ?>
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
"><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		<div id="menu1_s" class="dmenu shadow" style="display:none"></div>
		  </div>
		  </td>
        </tr>
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 工作地区：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <div>
				<input type="text" value="<?php echo $this->_run_modifier($this->_vars['jobs']['district_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="district_cn" id="district_cn" class="input_text_200 input_text_selsect"/>
		<input name="district" id="district" type="hidden" value="<?php echo $this->_vars['jobs']['district']; ?>
" />
		<input name="sdistrict" id="sdistrict" type="hidden" value="<?php echo $this->_vars['jobs']['sdistrict']; ?>
" />
		<div id="menu2" class="dmenu shadow">
			<ul>
			<?php echo tpl_function_qishi_get_classify(array('set' => "列表名:c_province,类型:QS_district,id:0"), $this);?>
			<?php if (count((array)$this->_vars['c_province'])): foreach ((array)$this->_vars['c_province'] as $this->_vars['list']): ?>
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
"><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		<div id="menu2_s" class="dmenu shadow" style="display:none"></div>
		  </div>

		  </td>
        </tr>
		   <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"> 学历要求：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <div>
	<?php echo tpl_function_qishi_get_classify(array('set' => "类型:QS_education,列表名:li"), $this);?>
		<input type="text" value="<?php echo $this->_run_modifier($this->_vars['jobs']['education_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="education_cn" id="education_cn" class="input_text_200 input_text_selsect"/>
		<input name="education" id="education" type="hidden" value="<?php echo $this->_vars['jobs']['education']; ?>
" />
		<div id="menu3" class="menu">
			<ul>
			<?php if (count((array)$this->_vars['li'])): foreach ((array)$this->_vars['li'] as $this->_vars['list']): ?>
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
"><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		  </div>
		   </td>
          </tr>
				<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">工作经验：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		 
		 <div>
	<?php echo tpl_function_qishi_get_classify(array('set' => "类型:QS_experience,列表名:li"), $this);?>
		<input type="text" value="<?php echo $this->_run_modifier($this->_vars['jobs']['experience_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="experience_cn" id="experience_cn" class="input_text_200 input_text_selsect"/>
		<input name="experience" id="experience" type="hidden" value="<?php echo $this->_vars['jobs']['experience']; ?>
" />
		<div id="menu4" class="menu">
			<ul>
			<?php if (count((array)$this->_vars['li'])): foreach ((array)$this->_vars['li'] as $this->_vars['list']): ?>
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
"><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		  </div>
		 
		  </td>
		        </tr>
				
        <tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <span style="color:#FF3300; font-weight:bold">*</span> 月薪范围：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">
		  <div>
	<?php echo tpl_function_qishi_get_classify(array('set' => "类型:QS_wage,列表名:li"), $this);?>
		<input type="text" value="<?php echo $this->_run_modifier($this->_vars['jobs']['wage_cn'], 'default', 'plugin', 1, "请选择"); ?>
"  readonly="readonly" name="wage_cn" id="wage_cn" class="input_text_200 input_text_selsect"/>
		<input name="wage" id="wage" type="hidden" value="<?php echo $this->_vars['jobs']['wage']; ?>
" />
		<div id="menu5" class="menu">
			<ul>
			<?php if (count((array)$this->_vars['li'])): foreach ((array)$this->_vars['li'] as $this->_vars['list']): ?>
			<li id="<?php echo $this->_vars['list']['id']; ?>
" title="<?php echo $this->_vars['list']['categoryname']; ?>
"><?php echo $this->_vars['list']['categoryname']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		</div>
		  </div>
		  </td>
        </tr>

        <tr>
          <td align="right" valign="top" bgcolor="#FFFFFF" >
		  <span style="color:#FF3300; font-weight:bold">*</span> 职位描述：</td>
          <td bgcolor="#FFFFFF"  >
		  <textarea name="contents" class="company_mb_textarea" id="contents" style="width:580px; height:180px;" ><?php echo $this->_vars['jobs']['contents']; ?>
</textarea>
            <br />
            <div style="line-height:30px; height:30px;">&nbsp;说明：请详细描述该职位，内容可包括：职位要求，岗位职责等。</div> 
		</td>
        </tr>
		<tr>
          <td height="30" align="right"  >
		<span style="color:#FF3300; font-weight:bold">*</span> 应届生应聘：</td>
          <td  >	 
		  <label><input name="graduate" type="radio" value="0"  <?php if ($this->_vars['jobs']['graduate'] == "0"): ?>checked="checked"<?php endif; ?> />不允许</label>
		   <label><input type="radio" name="graduate" value="1"  <?php if ($this->_vars['jobs']['graduate'] == "1"): ?>checked="checked"<?php endif; ?> />允许</label>
		  </td>
        </tr>
		
		</table>
		<div class="toptit">联系方式</div>
		  <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF"  >
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 联 系 人：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="contact" type="text" class="input_text_200" id="contact" value="<?php echo $this->_vars['jobs']['contact']['contact']; ?>
" maxlength="15" /></td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold"> </span>联系人QQ号码：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="qq" type="text" class="input_text_200" id="qq" maxlength="50" value="<?php echo $this->_vars['jobs']['contact']['qq']; ?>
" />
           </td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 联系电话：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="telephone" type="text" class="input_text_200" id="telephone" maxlength="35" value="<?php echo $this->_vars['jobs']['contact']['telephone']; ?>
" /></td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 联系地址：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><input name="address" type="text" class="input_text_200" id="address" maxlength="50"  value="<?php echo $this->_vars['jobs']['contact']['address']; ?>
"/></td>
        </tr>
		<tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="color:#FF3300; font-weight:bold">*</span> 联系邮箱：</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><label>
          <input name="email" type="text" class="input_text_200" id="email" maxlength="80"  value="<?php echo $this->_vars['jobs']['contact']['email']; ?>
"/>
          </label></td>
        </tr>
          <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">用邮箱接收简历?</td>
          <td bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><label>
            <input name="notify" type="radio" value="1" <?php if ($this->_vars['jobs']['contact']['notify'] == 1): ?>checked="checked"<?php endif; ?> /> 接收
          </label> &nbsp;&nbsp;&nbsp;
            <label>
            <input type="radio" name="notify" value="0" <?php if ($this->_vars['jobs']['contact']['notify'] == "0"): ?>checked="checked"<?php endif; ?>/>不接收            </label> 
            <span style="color: #666666"> &nbsp;&nbsp;&nbsp;(默认为接收：当有人给此岗位投递简历时，系统会发一份通知邮件提醒查看)</span></td>
        </tr>
		
		
		<?php if ($this->_vars['QISHI']['subsite'] == "1"): ?>
	<tr>
    <td align="right">添加到：</td>
    <td>
       <label ><input name="subsite_id" type="radio" value="0" <?php if ($this->_vars['jobs']['subsite_id'] == "0"): ?>checked="checked"<?php endif; ?>/>全站</label>
		&nbsp;&nbsp;&nbsp;
		<?php if (count((array)$this->_vars['subsite'])): foreach ((array)$this->_vars['subsite'] as $this->_vars['li']): ?>
		<label ><input name="subsite_id" type="radio" value="<?php echo $this->_vars['li']['s_id']; ?>
" <?php if ($this->_vars['jobs']['subsite_id'] == $this->_vars['li']['s_id']): ?>checked="checked"<?php endif; ?>/><?php echo $this->_vars['li']['s_districtname']; ?>
</label>
		&nbsp;&nbsp;&nbsp;
		<?php endforeach; endif; ?>
	  
	  </td>
    </tr>
	 <?php endif; ?>
		
		<tr>
            <td height="30" align="right" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed">&nbsp;</td>
            <td height="120" bgcolor="#FFFFFF" style=" border-bottom:1px #CCCCCC dashed"><span style="font-size:14px;">
              <input type="hidden" name="id"  value="<?php echo $this->_vars['jobs']['id']; ?>
" />
			  <input type="hidden" name="company_id"  value="<?php echo $this->_vars['jobs']['company_id']; ?>
" />			  
			  <input type="hidden" name="url"  value="<?php echo $this->_vars['url']; ?>
" />
              <input name="submit3" type="submit" class="admin_submit"    value="保存修改"/> 
              <input name="submit22" type="button" class="admin_submit"    value="返 回" onclick="window.location.href='<?php echo $this->_vars['url']; ?>
'"/>
            </span></td>
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