<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2013-11-24 21:41 中国标准时间 */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="templates/css/common.css" rel="stylesheet" type="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<link rel="shortcut icon" href="<?php echo $this->_vars['QISHI']['site_dir']; ?>
favicon.ico" />
<title>系统提示 - 骑士PHP人才系统(www.74cms.com)</title>
</head>
<body>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:8px;">
    <tr>
      <td width="186" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0"  class="left_table_right_dot">
          <tr>
            <td> <?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("left.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?> </td>
          </tr>
      </table></td>
      <td valign="top"><table width="98%" border="0" align="center" cellpadding="6" cellspacing="0">
          <tr>
            <td bgcolor="#F7FBFC" style=" font-size:13px; padding-left:15px;  "><strong>系统提示</strong> </td>
          </tr>
          <tr>
            <td style="line-height:200%;"><table width="100%" border="0" cellpadding="0" cellspacing="7"  >
              <tr>
                <td height="30" align="center"><strong style=" color: #009900 ; font-size:14px;"><?php echo $this->_vars['msg']; ?>
</strong></td>
              </tr>
              <tr>
                <td align="center"> <?php if ($this->_vars['gourl'] == 'goback'): ?>
                  <p class="marginbot"><a class="lightlink" href="javascript:history.back();">点击这里返回</a></p>
                  <?php else: ?>
                  <p><a class="lightlink" href="<?php echo $this->_vars['gourl']; ?>
">如果您的浏览器没有反应，请点击这里</a></p>
                  <script language="JavaScript" type="text/javascript">setTimeout("location.replace('<?php echo $this->_vars['gourl']; ?>
')",'2000');</script>
                  <?php endif; ?> </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
</body>
</html>
