<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:43 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
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
	<h2>��ʾ��</h2>
	  <p>�������Ŀ���ö���֪ͨ������ϵͳ��Դ��<br />
	  </p>
		</div>
	<div class="toptit">���ŷ��͹���</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
       <tr>
        <td height="30" style="font-size:12px;padding-left:20px;">
		<form action="?act=sms_rule_save" method="post"   name="form1" id="form1">
		<?php echo $this->_vars['inputtoken']; ?>

		<table width="700" border="0" cellspacing="7" cellpadding="1" style=" margin-bottom:3px;" class="link_lan">
           
            <tr>
              <td align="right" width="137">����ְλ��</td>
              <td>
			   <label>
                <input name="set_applyjobs" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_applyjobs'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="set_applyjobs" value="0"  <?php if ($this->_vars['sms_config']['set_applyjobs'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(����������ְλ�Ƿ�֪ͨ��ҵ)</td>
            </tr>
            <tr>
              <td align="right">�������ԣ�</td>
              <td>
			  <label>
                <input name="set_invite" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_invite'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_invite" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_invite'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(��ҵ������������Ƿ�֪ͨ����)</td>
            </tr>
            <tr>
              <td align="right">����������</td>
              <td>
			    <label>
                <input name="set_order" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_order'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_order" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_order'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(��Ա�³�ֵ�����Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">��ֵ�ɹ���</td>
              <td>
			   <label>
                <input name="set_payment" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_payment'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_payment" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_payment'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(��Ա��ֵ�ɹ��Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">�޸����룺</td>
              <td>
			  <label>
                <input name="set_editpwd" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_editpwd'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_editpwd" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_editpwd'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(��Ա�޸�������Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">ְλ���ͨ����</td>
              <td>
			    <label>
                <input name="set_jobsallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_jobsallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_jobsallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_jobsallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(ְλ���ͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">ְλ���δͨ����</td>
              <td>
			    <label>
                <input name="set_jobsnotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_jobsnotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_jobsnotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_jobsnotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(ְλ���δͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">Ӫҵִ�����ͨ����</td>
              <td>
			  <label>
                <input name="set_licenseallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_licenseallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_licenseallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_licenseallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(Ӫҵִ�����ͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">Ӫҵִ�����δͨ����</td>
              <td>
			    <label>
                <input name="set_licensenotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_licensenotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_licensenotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_licensenotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(Ӫҵִ�����δͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">��ͨ���ӵ�ͼ��</td>
              <td> <label>
                <input name="set_addmap" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_addmap'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_addmap" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_addmap'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label></td>
              <td  style="color:#999999">(��ҵ��ͨ���ӵ�ͼ�Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">�������ͨ����</td>
              <td>
			  <label>
                <input name="set_resumeallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_resumeallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_resumeallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_resumeallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(���˼������ͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">�������δͨ����</td>
              <td>
			    <label>
                <input name="set_resumenotallow" type="radio" value="1"  <?php if ($this->_vars['sms_config']['set_resumenotallow'] == "1"): ?> checked="checked" <?php endif; ?>/>
                ֪ͨ</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                <input name="set_resumenotallow" type="radio"  value="0"  <?php if ($this->_vars['sms_config']['set_resumenotallow'] == "0"): ?> checked="checked" <?php endif; ?> />
              ��֪ͨ</label>			  </td>
              <td  style="color:#999999">(���˼������δͨ���Ƿ�֪ͨ)</td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td height="55"><span style="font-size:14px;">
                <input name="save" type="submit" class="admin_submit"    value="�����޸�"/>
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