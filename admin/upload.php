<?php
$gt->assign('title', 'Add new image');
$tpl = new e_Template('adm_upload', ROOT."/tpl/");
$tpl->parse('script');
$gt->assign('morehead', $tpl->out('script'));

$tpl->parse('form');
$output .= $tpl->out('form');
?>
