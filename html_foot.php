<?php
### This is wechall html_foot!

if(defined('WC_HTML_HEAD__DEFINED')) { return; }
define('WC_HTML_HEAD__DEFINED', true);

GWF_Session::commit();
echo '</div></div>';
echo GWF_Template::templateWC('chall_foot.tpl', array('wcfooter' => WC_HTML::displayFooter()) );
