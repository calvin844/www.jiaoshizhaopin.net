<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-25 14:51 �й���׼ʱ�� */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
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
	<h2>��ʾ��</h2>
	<p>
 �Զ�����λ�������������� ��QS_����ͷ
</p>
</div>
<div class="toptit">�޸Ĺ��λ��<span style="color:#006699"><?php echo $this->_vars['ad_category']['categoryname']; ?>
</span></div>
  <form action="?act=ad_category_edit_save" method="post"  name="form1" id="form1">
     
    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="link_lan"  >
 
      <tr>
        <td style=" line-height:220%; color:#666666;"><table width="700" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td width="181" align="right">���λ���ƣ�</td>
            <td width="498"><input name="categoryname" type="text" class="input_text_200" id="categoryname" maxlength="50" value="<?php echo $this->_vars['ad_category']['categoryname']; ?>
"/></td>
          </tr>
		  <tr>
            <td width="181" align="right">�������ƣ�</td>
            <td width="498">
			<input name="alias" type="text" class="input_text_200" id="alias" maxlength="50" value="<?php echo $this->_vars['ad_category']['alias']; ?>
"/>
           <span style="color: #999999"> ��������������ģ���е��ô˹��λ</span>
			</td>
          </tr>
          <tr>
            <td align="right">�������ͣ�</td>
            <td>
			 <label><input type="radio" name="type_id" value="1" <?php if ($this->_vars['ad_category']['type_id'] == "1"): ?>checked="checked"<?php endif; ?>/>����</label>
&nbsp;&nbsp;&nbsp;&nbsp;
 <label><input name="type_id" type="radio" value="2" <?php if ($this->_vars['ad_category']['type_id'] == "2"): ?>checked="checked"<?php endif; ?>/>
 ͼƬ</label>
&nbsp;&nbsp;&nbsp;&nbsp;
 <label><input type="radio" name="type_id" value="3" <?php if ($this->_vars['ad_category']['type_id'] == "3"): ?>checked="checked"<?php endif; ?>/>����</label>
&nbsp;&nbsp;&nbsp;&nbsp;
 <label><input type="radio" name="type_id" value="4" <?php if ($this->_vars['ad_category']['type_id'] == "4"): ?>checked="checked"<?php endif; ?>/>FLASH</label>
&nbsp;&nbsp;&nbsp;&nbsp;
 <label><input type="radio" name="type_id" value="5" <?php if ($this->_vars['ad_category']['type_id'] == "5"): ?>checked="checked"<?php endif; ?>/>����</label>
&nbsp;&nbsp;&nbsp;&nbsp;
 <label><input type="radio" name="type_id" value="6" <?php if ($this->_vars['ad_category']['type_id'] == "6"): ?>checked="checked"<?php endif; ?>/>��Ƶ</label>
&nbsp;&nbsp;&nbsp;&nbsp;
		  </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="60">
			<?php echo $this->_vars['inputtoken']; ?>

			<input name="id" type="hidden" value="<?php echo $this->_vars['ad_category']['id']; ?>
" />
			<input type="submit" name="Submit3" value="ȷ���ύ" class="admin_submit"   />
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="submit22" type="button" class="admin_submit"    value="�� ��" onclick="Javascript:window.history.go(-1)"/>
			</td>
          </tr>
        </table></td>
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