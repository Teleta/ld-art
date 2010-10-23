<?php
$tpl = new e_Template('adm_login', ROOT."/tpl/");
$tpl->parse();
$gt = new e_Template('adm', ROOT."/tpl/");
$gt->assign('title', "Enter to Admin Area");
$gt->assign('content', $tpl->out());
$gt->parse();
echo $gt->out();
exit;
?>
