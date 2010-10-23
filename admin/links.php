<?php
$tpl = new e_Template('adm_links', ROOT."/tpl/");
$tpl->parse('script');
$gt->assign('morehead', $tpl->out('script'));

$l = $DB->query("SELECT id, url, description_e e, description_r r
	FROM `".PREF."links`
	ORDER BY `sort`");
while($li = $DB->fetch($l)){
	$tpl->assign('l', array(
		'id' => $li['id'],
		'url' => $li['url'],
		'eng' => $li['e'],
		'rus' => $li['r']
	));
	$tpl->parse('form.row.url');
	$tpl->parse('form.row');
}

$tpl->parse('form');
$output .= $tpl->out('form');
$gt->assign('title', 'Links');
?>