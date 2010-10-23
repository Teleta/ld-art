<?php
$tpl = new e_Template('adm_ex', ROOT."/tpl/");
$tpl->parse('script');
$gt->assign('morehead', $tpl->out('script'));

$l = $DB->query("SELECT id, description_e de, description_r dr,
	location_e le, location_r lr,
	date_from df, date_to dt
	FROM `".PREF."exhibitions`
	ORDER BY `date_from` DESC, `date_to` DESC");
while($li = $DB->fetch($l)){
	$tpl->assign('l', array(
		'id' => $li['id'],
		'eng' => $li['de'],
		'rus' => $li['dr'],
		'loc_e' => $li['le'],
		'loc_r' => $li['lr'],
		'd1' => $li['df'],
		'd2' => $li['dt'],
	));
	$tpl->parse('form.row');
}

$tpl->parse('form');
$output .= $tpl->out('form');
$gt->assign('title', 'Exhibitions');
?>