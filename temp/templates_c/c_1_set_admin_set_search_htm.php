<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:38 中国标准时间 */  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include("sys/admin_header.htm", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="admin_main_nr_dbox">
 <div class="pagetit">
	<div class="ptit"> <?php echo $this->_vars['pageheader']; ?>
</div>
  <div class="clear"></div>
</div>
<div class="toptip">
	<h2>提示：</h2>
	<p>
您可以根据网站的数据量来选择搜索方式。<br />
全文搜索效率更高，like搜索更加精准。<br />
like只能搜索职位名称和公司名称。<br />
全文搜索能搜索更多的内容，但搜索关键字取决于关键字的字典，字典位置：include/word.txt， 您可以调整字典词语来优化全文搜索。<br />
</p>
</div>
<div class="toptit">职位搜索</div>
  <form action="?act=search_save" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td width="120" align="right">允许关键字搜索：</td>
      <td>
	   <label><input name="jobsearch_purview" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_purview'] == "1"): ?>checked="checked"<?php endif; ?>/>任何用户</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="jobsearch_purview" value="2"  <?php if ($this->_vars['config']['jobsearch_purview'] == "2"): ?>checked="checked"<?php endif; ?>/>仅已登录会员</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">搜索模式：</td>
      <td>
	   <label><input name="jobsearch_type" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_type'] == "1"): ?>checked="checked"<?php endif; ?>/>全文搜索</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="jobsearch_type" value="2"  <?php if ($this->_vars['config']['jobsearch_type'] == "2"): ?>checked="checked"<?php endif; ?>/>like</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">搜索结果排序：</td>
      <td>
	   <label><input name="jobsearch_sort" type="radio"  value="1"  <?php if ($this->_vars['config']['jobsearch_sort'] == "1"): ?>checked="checked"<?php endif; ?>/>默认</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label><input name="jobsearch_sort" type="radio" value="2"  <?php if ($this->_vars['config']['jobsearch_sort'] == "2"): ?>checked="checked"<?php endif; ?>/>刷新时间</label>	
	   <span class="admin_note">按刷新时间排序会影响搜索效率</span>
	   </td>
    </tr>
	 
	      
	       <tr>
	         <td align="right">&nbsp;</td>
	         <td height="50"> 
	           <input name="submit" type="submit" class="admin_submit"    value="保存"/>             </td>
      </tr>
  </table>
  </form>
  <div class="toptit">简历搜索</div>
  <form action="?act=search_save" method="post"  name="form1" id="form1">
 <?php echo $this->_vars['inputtoken']; ?>

    <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td width="120" align="right">允许关键字搜索：</td>
      <td>
	   <label><input name="resumesearch_purview" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_purview'] == "1"): ?>checked="checked"<?php endif; ?>/>任何用户</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="resumesearch_purview" value="2"  <?php if ($this->_vars['config']['resumesearch_purview'] == "2"): ?>checked="checked"<?php endif; ?>/>仅已登录会员</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">搜索模式：</td>
      <td>
	   <label><input name="resumesearch_type" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_type'] == "1"): ?>checked="checked"<?php endif; ?>/>全文搜索</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label ><input type="radio" name="resumesearch_type" value="2"  <?php if ($this->_vars['config']['resumesearch_type'] == "2"): ?>checked="checked"<?php endif; ?>/>like</label>	  </td>
    </tr>
	 <tr>
      <td width="120" align="right">搜索结果排序：</td>
      <td>
	   <label><input name="resumesearch_sort" type="radio"  value="1"  <?php if ($this->_vars['config']['resumesearch_sort'] == "1"): ?>checked="checked"<?php endif; ?>/>默认</label>
       &nbsp;&nbsp;&nbsp;&nbsp; 
	   <label><input name="resumesearch_sort" type="radio" value="2"  <?php if ($this->_vars['config']['resumesearch_sort'] == "2"): ?>checked="checked"<?php endif; ?>/>刷新时间</label>
	   <span class="admin_note">按刷新时间排序会影响搜索效率</span>
	   </td>
    </tr>
	       <tr>
	         <td align="right">&nbsp;</td>
	         <td height="50"> 
	           <input name="submit" type="submit" class="admin_submit"    value="保存"/>
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