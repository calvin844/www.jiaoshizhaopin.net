<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-29 16:42 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
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
echo $this->_fetch_compile_include("set/admin_set_config_nav.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
  <div class="clear"></div>
</div> 

<div class="toptit">会员注册协议</div>
  <form action="?act=set_save" method="post"   name="form1" id="form1">
  <?php echo $this->_vars['inputtoken']; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0"  >

            <tr>
              <td width="441"><textarea name="agreement" style=" width:700px;height:400px; font-size:12px; line-height:180%"  id="agreement"><?php echo $this->_vars['text']['agreement']; ?>
</textarea>
                  <script>
 KE.show({
				id : 'agreement',
				resizeMode : 1,
				allowPreviewEmoticons : false,
				allowUpload : false,
				items : [
				'source','fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|',  'link']
			});
		    </script>              </td>
            </tr>
            <tr>
              <td height="50">
			  
                <input name="submit2" type="submit" class="admin_submit"    value="保存修改"/>
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