<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sms/admin_sms_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div>
	<div class="toptip">
	<h2>提示：</h2>
	  <p>过多的项目设置短信通知会消耗系统资源。<br />
	  </p>
		</div>
	<div class="toptit">短信发送规则</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
       <tr>
        <td height="30" style="font-size:12px;padding-left:20px;">
		<form action="?act=sms_rule_save" method="post"   name="form1" id="form1">
		<?php echo $this->_vars['inputtoken']; ?>

		<table width="700" border="0" cellspacing="7" cellpadding="1" style=" margin-bottom:3px;" class="link_lan">
           
            <tr>
              <td align="right" width="137">申请职位：</td>
              <td>
			   <label>
                <input name="set_applyjobs" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_applyjobs'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="set_applyjobs" value="0"  <?php if ($this->_vars['sms_config']['set_applyjobs'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(个人申请了职位是否通知企业)</td>
            </tr>
            <tr>
              <td align="right">邀请面试：</td>
              <td>
			  <label>
                <input name="set_invite" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_invite'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_invite" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_invite'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(企业邀请个人面试是否通知个人)</td>
            </tr>
            <tr>
              <td align="right">新增订单：</td>
              <td>
			    <label>
                <input name="set_order" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_order'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_order" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_order'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(会员下充值订单是否通知)</td>
            </tr>
            <tr>
              <td align="right">充值成功：</td>
              <td>
			   <label>
                <input name="set_payment" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_payment'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_payment" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_payment'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(会员充值成功是否通知)</td>
            </tr>
            <tr>
              <td align="right">修改密码：</td>
              <td>
			  <label>
                <input name="set_editpwd" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_editpwd'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_editpwd" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_editpwd'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(会员修改密码后是否通知)</td>
            </tr>
            <tr>
              <td align="right">职位审核通过：</td>
              <td>
			    <label>
                <input name="set_jobsallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_jobsallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_jobsallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_jobsallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(职位审核通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">职位审核未通过：</td>
              <td>
			    <label>
                <input name="set_jobsnotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_jobsnotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_jobsnotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_jobsnotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(职位审核未通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">营业执照审核通过：</td>
              <td>
			  <label>
                <input name="set_licenseallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_licenseallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_licenseallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_licenseallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(营业执照审核通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">营业执照审核未通过：</td>
              <td>
			    <label>
                <input name="set_licensenotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_licensenotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_licensenotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_licensenotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(营业执照审核未通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">开通电子地图：</td>
              <td> <label>
                <input name="set_addmap" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_addmap'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_addmap" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_addmap'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label></td>
              <td  style="color:#999999">(企业开通电子地图是否通知)</td>
            </tr>
            <tr>
              <td align="right">简历审核通过：</td>
              <td>
			  <label>
                <input name="set_resumeallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_resumeallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_resumeallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_resumeallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(个人简历审核通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">简历审核未通过：</td>
              <td>
			    <label>
                <input name="set_resumenotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_resumenotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                通知</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_resumenotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_resumenotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              不通知</label>			  </td>
              <td  style="color:#999999">(个人简历审核未通过是否通知)</td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td height="55"><span style="font-size:14px;">
                <input name="save" type="submit" class="admin_submit"    value="保存修改"/>
              </span></td>
              <td>&nbsp;</td>
            </tr>
          </table>
		  </form>		  </td>
      </tr>
    </table>
  
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_footer.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</body>
</html>