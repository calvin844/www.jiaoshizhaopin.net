<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-02-18 02:37 中国标准时间 */ ?>
<div class="topnav">
<a href="?act=" <?php if ($this->_vars['navlabel'] == 'set'): ?>class="select"<?php endif; ?>><u>配置</u></a>
<a href="?act=modeselect" <?php if ($this->_vars['navlabel'] == 'modeselect'): ?>class="select"<?php endif; ?>><u>运营模式</u></a>
<a href="?act=set_points" <?php if ($this->_vars['navlabel'] == 'set_points'): ?>class="select"<?php endif; ?>><u>配置积分模式</u></a>
<a href="?act=set_meal" <?php if ($this->_vars['navlabel'] == 'set_meal'): ?>class="select"<?php endif; ?>><u>配置套餐模式</u></a>
<div class="clear"></div>
</div>