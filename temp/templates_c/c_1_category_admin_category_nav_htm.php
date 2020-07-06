<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:39 中国标准时间 */ ?>
<div class="topnav">
<a href="admin_category.php" <?php if ($this->_vars['navlabel'] == 'district'): ?>class="select"<?php endif; ?>><u>地区分类</u></a>
<a href="admin_category.php?act=jobs" <?php if ($this->_vars['navlabel'] == 'jobs'): ?>class="select"<?php endif; ?>><u>职位分类</u></a>
<a href="admin_category.php?act=grouplist" <?php if ($this->_vars['navlabel'] == 'group'): ?>class="select"<?php endif; ?>><u>其他分类组</u></a>
<div class="clear"></div>
</div>